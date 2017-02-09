<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MailingBundle\Entity\Traits;

/**
 * Class MessageTrait
 * @package Positibe\Bundle\MailingBundle\Entity\Traits
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
trait MessageTrait
{

    /**
     * @var string
     *
     * @ORM\Column(name="swift_mailer_message", type="string", length=255, nullable=TRUE)
     */
    private $swiftMailerMessage;

    /**
     * @var string
     *
     * @ORM\Column(name="subjectHtml", type="string", length=255, nullable=TRUE)
     */
    private $subjectHtml;

    /**
     * @var string
     *
     * @ORM\Column(name="bodyHtml", type="text", nullable=TRUE)
     */
    private $bodyHtml;

    /**
     * @var string
     *
     * @ORM\Column(name="messageError", type="text", nullable=TRUE)
     */
    private $messageError;


    /**
     * @return string
     */
    public function getMessageError()
    {
        return $this->messageError;
    }

    /**
     * @param string $messageError
     */
    public function setMessageError($messageError)
    {
        $this->messageError = $messageError;
    }

    /**
     * @return string
     */
    public function getBodyHtml()
    {
        return $this->bodyHtml;
    }

    /**
     * @param string $bodyHtml
     */
    public function setBodyHtml($bodyHtml)
    {
        $this->bodyHtml = $bodyHtml;
    }

    /**
     * @return string
     */
    public function getSwiftMailerMessage()
    {
        return $this->swiftMailerMessage;
    }

    /**
     * @param string $swiftMailerMessage
     */
    public function setSwiftMailerMessage($swiftMailerMessage)
    {
        $this->swiftMailerMessage = $swiftMailerMessage;
    }

    /**
     * @return string
     */
    public function getSubjectHtml()
    {
        return $this->subjectHtml;
    }

    /**
     * @param string $subjectHtml
     */
    public function setSubjectHtml($subjectHtml)
    {
        $this->subjectHtml = $subjectHtml;
    }
}