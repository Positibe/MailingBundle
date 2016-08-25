<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MailingBundle\Swiftmailer;

use Doctrine\ORM\EntityManager;
use Positibe\Bundle\MailingBundle\Entity\Statistics;
use Swift_Events_SendEvent;
use Swift_Events_TransportExceptionEvent;
use Swift_Plugins_Logger;

/**
 * Class MailingLoggerPlugin
 * @package Positibe\Bundle\MailingBundle\Swiftmailer
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MailingLoggerPlugin implements \Swift_Plugins_Logger, \Swift_Events_SendListener, \Swift_Events_TransportExceptionListener
{
    protected $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }


    /**
     * Add a log entry.
     *
     * @param string $entry
     */
    public function add($entry)
    {
        'dime';
        // TODO: Implement add() method.
    }

    /**
     * Clear the log contents.
     */
    public function clear()
    {
        // TODO: Implement clear() method.
    }

    /**
     * Get this log as a string.
     *
     * @return string
     */
    public function dump()
    {
        'dump';
        // TODO: Implement dump() method.
    }

    /**
     * Invoked as a TransportException is thrown in the Transport system.
     *
     * @param Swift_Events_TransportExceptionEvent $evt
     */
    public function exceptionThrown(Swift_Events_TransportExceptionEvent $evt)
    {
        $e = $evt->getException();
        $message = $e->getMessage();
//        $this->_logger->add(sprintf("!! %s", $message));
        $message .= PHP_EOL;
//        $message .= 'Log data:'.PHP_EOL;
//        $message .= $this->_logger->dump();
//        $evt->cancelBubble();
    }

    /**
     * Invoked immediately before the Message is sent.
     *
     * @param Swift_Events_SendEvent $evt
     */
    public function beforeSendPerformed(Swift_Events_SendEvent $evt)
    {
        // TODO: Implement beforeSendPerformed() method.
    }

    /**
     * Invoked immediately after the Message is sent.
     *
     * @param Swift_Events_SendEvent $evt
     */
    public function sendPerformed(Swift_Events_SendEvent $evt)
    {
        $message = $evt->getMessage();
        if ($evt->getResult() === Swift_Events_SendEvent::RESULT_SUCCESS) {

        }
        if ($evt->getResult() === Swift_Events_SendEvent::RESULT_FAILED) {
            $failures = $evt->getFailedRecipients();
        }
        if ($evt->getResult() === Swift_Events_SendEvent::RESULT_TENTATIVE) {
            $failures = $evt->getFailedRecipients();
        }
        if ($evt->getResult() === Swift_Events_SendEvent::RESULT_PENDING) {

        }
//        sprintf() = $evt->getMessage()->getId();
        $this->em->getConnection()->createQueryBuilder()->update('');
//            'UPDATE MyProject\Model\User u SET u.password = 'new' WHERE u.id IN (1, 2, 3)'
    }

    public function update()
    {
        //This is an example to log what email is really send or bounced.
        //Only is shown how it should work
        $qb = $this->em->createQueryBuilder();
        $qb->update(
            'PositibeMailingBundle:Statistics',
            'statistics'
        )
            ->orWhere('statistics.swiftMailerMessage = ?1')
            ->orWhere('statistics.swiftMailerMessage = ?2')
            ->orWhere('statistics.swiftMailerMessage = ?3')
            ->set('statistics.state', $qb->expr()->literal(Statistics::STATE_BOUNCED))
            ->setParameter(1, 'd1c334647c5a4057a5748636ba32c6be@localhost')
            ->setParameter(2, '5028774317da02478004f56aaac777e4@localhost')
            ->setParameter(3, '66aae32bfbf3dca964626eb91e70ae88@localhost');
//            'UPDATE MyProject\Model\User u SET u.password = 'new' WHERE u.id IN (1, 2, 3)'
        $result = $qb->getQuery()->getResult();
    }

} 