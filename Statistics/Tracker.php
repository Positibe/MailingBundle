<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MailingBundle\Statistics;

use Doctrine\ORM\EntityManager;
use Positibe\Bundle\MailingBundle\Entity\Statistics;


/**
 * Class Tracker
 * @package Positibe\Bundle\MailingBundle\Statistics
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class Tracker
{
    protected $em;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /***
     * @param $token
     */
    public function clicked($token)
    {
        if ($statistics = $this->getStatistics($token)) {
            $statistics->received();
            $statistics->opened();
            $statistics->clicked();

            $this->em->persist($statistics);
            $this->em->flush();
        }
    }

    /**
     * @param $token
     */
    public function viewed($token)
    {
        if ($statistics = $this->getStatistics($token)) {
            $statistics->received();
            $statistics->opened();

            $this->em->persist($statistics);
            $this->em->flush();
        }
    }



    /**
     * @param $token
     * @return \Positibe\Bundle\MailingBundle\Entity\Statistics
     */
    public function getStatistics($token)
    {
        return $this->em->getRepository('PositibeMailingBundle:Statistics')->findOneBy(['token' => $token]);
    }
} 