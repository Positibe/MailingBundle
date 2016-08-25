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

/**
 * Class ContainerSender
 * @package Positibe\Bundle\MailingBundle\Sender
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class ContainerSender implements SenderInterface
{
    /** @var  SenderInterface[] */
    protected $senders;

    public function addSender(SenderInterface $sender)
    {
        $this->senders[$sender->getName()] = $sender;

        return $this;
    }

    /**
     * @param string $name
     * @param array $recipients
     * @param array $data
     * @return \Positibe\Bundle\MailingBundle\Entity\Mail
     */
    public function send($name, array $recipients, array $data = [])
    {
        return $this->getSender($name, $data)->send($name, $recipients, $data);
    }

    /**
     * @param $name
     * @param array $data
     * @return SenderInterface
     */
    public function getSender($name, array $data = [])
    {
        if (isset($data['template'])) {
            return $this->senders['templating'];
        } elseif (isset($data['tracking']) && $data['tracking']) {
            return $this->senders['tracking'];
        } else {
            return $this->senders['standard'];
        }
    }

    public function getName()
    {
        return 'container';
    }

} 