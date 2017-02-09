<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MailingBundle\Entity\Interfaces;


/**
 * Interface MessageInterface
 * @package Positibe\Bundle\MailingBundle\Entity\Interfaces
 *
 * @author Pedro Carlos ABreu <pcabreus@gmail.com>
 */
interface MessageInterface extends SwiftMailerMessageInterface
{
    /**
     * Get receivers
     *
     * @return array
     */
    public function getReceivers();

    /**
     * @return string
     */
    public function getMessageError();

    /**
     * @param string $messageError
     */
    public function setMessageError($messageError);

    /**
     * @return string
     */
    public function getBodyHtml();

    /**
     * @param string $bodyHtml
     */
    public function setBodyHtml($bodyHtml);

    /**
     * @return string
     */
    public function getSubjectHtml();

    /**
     * @param string $subjectHtml
     */
    public function setSubjectHtml($subjectHtml);
}