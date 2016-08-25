<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MailingBundle\Delivery;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Positibe\Bundle\MailingBundle\Delivery\Provider\ProviderInterface;
use Positibe\Bundle\MailingBundle\Entity\Mail;
use Positibe\Bundle\MailingBundle\Sender\SenderInterface;


/**
 * Class SenderContainer
 * @package Positibe\Bundle\MailingBundle\Sender
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MailDelivery
{
    /**
     * @var ProviderInterface[]
     */
    private $provider;

    private $emailSender;
    private $entityManager;

    public function __construct(
        EntityManager $entityManager,
        SenderInterface $sender
    ) {
        $this->provider = new ArrayCollection();
        $this->emailSender = $sender;
        $this->entityManager = $entityManager;
    }

    /**
     * Send all configured mail
     *
     * @return array
     */
    public function deliver()
    {
        $mails = $this->getMails(array('date' => new \DateTime()));

        $deliveries = [];

        foreach ($mails as $mail) {
            $mail = $this->emailSender->send($mail, [], ['log' => true, 'tracking' => $mail->isTracking()]);

            if ($mail->isState(Mail::STATE_SENT)) {
                $deliveries[] = $mail;
            }
        }

        return $deliveries;
    }

    /**
     * @param array $options
     * @return Mail[]
     */
    protected function getMails(array $options = [])
    {
        /** @var Mail[] $mails */
        $mails = array();

        foreach ($this->provider as $provider) {
            $mails = array_merge(
                $mails,
                $provider->getMails($options)
            );
        }

        return $mails;
    }

    /**
     * @param ProviderInterface $provider
     * @return ProviderInterface
     */
    public function addProvider(ProviderInterface $provider)
    {
        $this->provider[$provider->getName()] = $provider;

        return $provider;
    }

    /**
     * @return ProviderInterface[]
     */
    public function getProvider()
    {
        return $this->provider;
    }
} 