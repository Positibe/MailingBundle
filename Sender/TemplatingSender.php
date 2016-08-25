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


/**
 * Class TemplatingSender
 * @package Positibe\Bundle\MailingBundle\Sender
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class TemplatingSender implements SenderInterface
{
    protected $mailer;
    protected $twig;
    protected $from;
    protected $fromName;

    public function __construct(
        \Swift_Mailer $mailer,
        \Twig_Environment $twig,
        $from,
        $fromName
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->from = $from;
        $this->fromName = $fromName;
    }

    public function send($name, array $recipients, array $data = [])
    {
        $template = $this->twig->loadTemplate($data['template']);

        $subject = $template->renderBlock('subject', $data['variables']);
        $bodyHtml = $template->renderBlock('body', $data['variables']);
        $bodyText = $template->renderBlock('body_text', $data['variables']);

        try {
            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($this->from, $this->fromName)
                ->setTo($recipients)
                ->setBody($bodyHtml, 'text/html')
                ->addPart($bodyText, 'text/plain');

            foreach ($this->getOption($data, ['attachments'], []) as $attachment) {

                $message->attach(\Swift_Attachment::fromPath($attachment));
            }

            $response = $this->mailer->send($message);

        } catch (\Exception $ex) {
            return $ex->getMessage();
        }

        return $response;
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

    public function getName()
    {
        return 'templating';
    }

} 