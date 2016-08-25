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
 * Class SenderInterface
 * @package Positibe\Bundle\MailingBundle\Sender
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
interface SenderInterface
{
    /**
     * Send an email
     *
     * @param string $name
     * @param array $recipients
     * @param array $data
     * @return Mail
     */
    public function send($name, array $recipients, array $data = []);

    /**
     * Name of sender
     *
     * @return mixed
     */
    public function getName();
} 