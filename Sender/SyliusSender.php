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

use Sylius\Component\Mailer\Sender\Sender;


/**
 * Class SyliusSender
 * @package Positibe\Bundle\MailingBundle\Sender
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class SyliusSender implements SenderInterface
{
    protected $emailSender;

    public function __construct(Sender $sender)
    {
        $this->emailSender = $sender;
    }

    public function send($name, array $recipients, array $data = [])
    {
        $this->emailSender->send($name, $recipients, $data);
    }

    /**
     * Name of sender
     *
     * @return mixed
     */
    public function getName()
    {
        return 'sylius';
    }


} 