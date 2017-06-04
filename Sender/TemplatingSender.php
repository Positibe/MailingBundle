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


/**
 * Class TemplatingSender
 * @package Positibe\Bundle\MailingBundle\Sender
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class TemplatingSender extends StandardSender
{
    /**
     * @param $name
     * @param array $data
     * @return Mail
     */
    public function getMail($name, array $data = [])
    {
        $mail = new Mail();
        $mail->setTracking(false);
        $mail->setCode($name);
        $mail->setResponseTo($this->senderAddress);
        $mail->setFromName($this->senderName);

        $mail->setSubject($name);
        $mail->setBody($name);

        return $mail;
    }

    public function getName()
    {
        return 'templating';
    }

    /**
     * @param Mail $mail
     * @param array $variables
     * @return string
     */
    public function getBodyHtml(Mail $mail, array $variables = [])
    {
        $template = $this->twig->loadTemplate($mail->getBody());

        $bodyHtml = $template->renderBlock('body', array_merge($mail->getVariables(), $variables));

        return $bodyHtml;
    }

    /**
     * @param Mail $mail
     * @param array $variables
     * @return string
     */
    public function getSubjectHtml(Mail $mail, array $variables = [])
    {
        $template = $this->twig->loadTemplate($mail->getBody());

        $subjectHtml = $template->renderBlock('subject', array_merge($mail->getVariables(), $variables));

        return $subjectHtml;
    }
} 