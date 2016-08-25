<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MailingBundle\Sender;

use Positibe\Bundle\MailingBundle\Entity\Mail;
use Positibe\Bundle\MailingBundle\Entity\Statistics;
use Positibe\Bundle\MailingBundle\Factory\StatisticsFactory;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


/**
 * Class TrackingSender
 * @package Positibe\Bundle\MailingBundle\Sender
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class TrackingSender extends StandardSender
{
    /**
     * @var StatisticsFactory
     */
    protected $statisticsFactory;

    public function setStatisticsFactory(StatisticsFactory $statisticsFactory)
    {
        $this->statisticsFactory = $statisticsFactory;

        return $this;
    }

    /**
     * @var UrlGeneratorInterface
     */
    protected $urlGenerator;

    public function setUrlGenerator(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;

        return $this;
    }

    /**
     * @param $name
     * @param array $recipients
     * @param array $data
     * @return Mail
     */
    public function send($name, array $recipients, array $data = [])
    {
        $mail = $this->getMail($name);

        if (count($recipients) > 0) {
            $data = array_merge(['receivers' => $recipients], $data);
        }

        $mail = $this->mergeData($mail, $data);

        try {


            foreach ($mail->getReceivers() as $email => $name) {
                $statistics = $this->statisticsFactory->createNewByData($mail, $email, $name);

                $subjectHtml = $this->getSubjectHtml($mail, ['user_name' => $name, 'user_email' => $email]);
                $bodyHtml = $this->getBodyHtml($mail, ['user_name' => $name, 'user_email' => $email]);

                $bodyHtml = str_replace(
                    'href="',
                    sprintf(
                        'href="%s?url=',
                        $this->urlGenerator->generate(
                            'positibe_mailing_tracking_clicked',
                            ['token' => $statistics->getToken()],
                            UrlGeneratorInterface::ABSOLUTE_URL
                        )
                    ),
                    $bodyHtml
                );

                $this->twig->setLoader($loader = new \Twig_Loader_Filesystem(__DIR__ . '/../Resources/views/Tracking'));
                $bodyHtml = $bodyHtml . $this->twig->render(
                        'link_to_viewed.html.twig',
                        array('token' => $statistics->getToken())
                    );

//                $mail->setBodyHtml($bodyHtml);
//                $mail->setSubjectHtml($subjectHtml);

                $response = $this->createMessage(
                    $statistics,
                    $subjectHtml,
                    $mail->getResponseTo(),
                    $mail->getFromName(),
                    $bodyHtml
                );;

                if ($response === 0) {
                    $mail->addFailure($email, $name);
                } else {

                    $statistics->setSentAt(new \DateTime());
                    $statistics->setState(Statistics::STATE_SENT);

                    $mail->addStatistics($statistics);
                }
            }

            $mail->setState(Mail::STATE_SENT);
            $mail->setSentAt(new \DateTime());

        } catch (\Exception $ex) {
            $mail->setState(Mail::STATE_ERROR);
            $mail->setMessageError('Error fatal: ' . $ex->getMessage());

            return $mail;
        }

        $this->logMail($mail, $data);

        return $mail;
    }

    public function getName()
    {
        return 'tracking';
    }

} 