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

use Doctrine\ORM\EntityManager;
use Positibe\Bundle\MailingBundle\Entity\Mail;
use Positibe\Bundle\MailingBundle\Entity\Statistics;
use Positibe\Bundle\MailingBundle\Entity\Interfaces\SwiftMailerMessageInterface;
use Positibe\Bundle\OrmMediaBundle\Entity\Media;


/**
 * Class StandardSender
 * @package Positibe\Bundle\MailingBundle\Sender
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class StandardSender implements SenderInterface
{
    protected $mailer;
    protected $twig;
    protected $em;
    protected $senderName;
    protected $senderAddress;
    protected $attachmentsPath;
    protected $baseUrl;

    public function __construct(
        \Swift_Mailer $mailer,
        \Twig_Environment $twig,
        EntityManager $manager,
        $senderName,
        $senderAddress,
        $attachmentsPath,
        $baseUrl
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->em = $manager;
        $this->senderName = $senderName;
        $this->senderAddress = $senderAddress;
        $this->attachmentsPath = $attachmentsPath;
        $this->baseUrl = $baseUrl;
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

            $mail->setSubjectHtml($this->getSubjectHtml($mail));
            $mail->setBodyHtml($this->getBodyHtml($mail));

            $response = $this->createMessage(
                $mail,
                $mail->getSubjectHtml(),
                $mail->getResponseTo(),
                $mail->getFromName(),
                $mail->getBodyHtml(),
                $mail->getAttachments(),
                $mail->getVariable('ccs', [])
            );

            if ($response === 0) {
                $mail->setState(Mail::STATE_ERROR);
                $mail->setMessageError('No se envió ningún correo');
            } else {
                $mail->setState(Mail::STATE_SENT);
                $mail->setSentAt(new \DateTime());
                $mail->setSentCount($response);
            }
        } catch (\Twig_Error $ex) {
            $mail->setState(Mail::STATE_ERROR);
            $mail->setMessageError($ex->getRawMessage());

            return $mail;
        } catch (\Exception $ex) {
            $mail->setState(Mail::STATE_ERROR);
            $mail->setMessageError('Error fatal: '.$ex->getMessage());

            return $mail;
        }

        $this->logMail($mail, $data);

        return $mail;
    }

    protected function createMessage(
        SwiftMailerMessageInterface $mail,
        $subject,
        $responseTo,
        $fromName,
        $body,
        $attachments = [],
        $ccs = []
    ) {
        /** @var \Swift_Message $message */
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom(
                $responseTo,
                $fromName
            )
            ->setBody($body, 'text/html')
            ->addPart(strip_tags($body), 'text/plain');

        if ($mail instanceof Statistics) {
            if ($mail->getEmail() !== null && $mail->getName() !== null) {
                $message->setTo($mail->getEmail(), $mail->getName());
            } else {
                $message->setTo($mail->getEmail());
            }
        } elseif ($mail instanceof Mail) {
            $message->setTo($mail->getReceivers());
        }
        $message->setCc($ccs);

        /** @var Media $attachment */
        foreach ($attachments as $attachment) {
            $file = $this->attachmentsPath.$attachment->getPath();
            $message->attach(\Swift_Attachment::fromPath($file)->setFilename($attachment->getName()));
        }

        $mail->setSwiftMailerMessage($message->getId());

        return $this->mailer->send($message);
    }

    /**
     * @param $name
     * @param array $data
     * @return Mail
     */
    public function getMail($name, array $data = [])
    {
        if ($name instanceof Mail) {
            $mail = $name;
        } else {
            $template = $this->em->getRepository('PositibeMailingBundle:Template')->findOneBy(array('code' => $name));

            $mail = new Mail();
            $mail->setTracking(false);
            $mail->setCode($name);
            $mail->setResponseTo($this->senderAddress);
            $mail->setFromName($this->senderName);

            if ($template) {
                $mail->setSubject($template->getSubject());
                $mail->setBody($template->getBody());
            }
        }

        return $mail;
    }

    public function logMail(Mail $mail, $data = [])
    {
        if (!isset($data['log']) || $data['log']) {
            $this->em->persist($mail);
            $this->em->flush();
        }
    }

    /**
     * @param Mail $mail
     * @param array $data
     * @return Mail
     */
    public function mergeData(Mail $mail, array $data = [])
    {
        $mail->setBody($this->getOption($data, 'body', $mail->getBody()));
        $mail->setSubject($this->getOption($data, 'subject', $mail->getSubject()));
        $mail->setResponseTo($this->getOption($data, 'responseTo', $mail->getResponseTo() ?: $this->senderAddress));
        $mail->setFromName($this->getOption($data, 'fromName', $mail->getFromName() ?: $this->senderName));
        $mail->setReceivers($this->getOption($data, 'receivers', $mail->getReceivers()));
        $mail->setVariables(
            $this->getOption($data, 'variables', is_array($mail->getVariables()) ? $mail->getVariables() : [])
        );
        $mail->setAttachments(
            $this->getOption($data, 'attachments', $mail->getAttachments() ?: [])
        );

        $mail->addVariable('ccs', $this->getOption($data, 'ccs', []));
        $mail->addVariable('fromName', $mail->getFromName());
        $mail->addVariable('responseTo', $mail->getResponseTo());
        $mail->addVariable('base_url', $this->baseUrl);

        $mail->setState(Mail::STATE_SENDING);

        return $mail;
    }

    /**
     * @param Mail $mail
     * @param array $variables
     * @return string
     */
    public function getBodyHtml(Mail $mail, array $variables = [])
    {
        $this->twig->setLoader($loader = new \Twig_Loader_String());

        $bodyHtml = $this->twig->render(
            $mail->getBody(),
            array_merge($mail->getVariables(), $variables)
        );

        return $bodyHtml;
    }

    /**
     * @param Mail $mail
     * @param array $variables
     * @return string
     */
    public function getSubjectHtml(Mail $mail, array $variables = [])
    {
        $this->twig->setLoader($loader = new \Twig_Loader_String());

        $bodyHtml = $this->twig->render(
            $mail->getSubject(),
            array_merge($mail->getVariables(), $variables)
        );

        return $bodyHtml;
    }

    /**
     * @param $data
     * @param $option
     * @param null $default
     * @return null
     */
    public function getOption($data, $option, $default = null)
    {
        if (isset($data[$option]) && $data[$option] !== null) {
            return $data[$option];
        }

        return $default;
    }

    /**
     * Name of sender
     *
     * @return mixed
     */
    public function getName()
    {
        return 'standard';
    }


}