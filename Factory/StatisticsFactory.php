<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MailingBundle\Factory;

use Positibe\Bundle\MailingBundle\Entity\Mail;
use Positibe\Bundle\MailingBundle\Entity\Statistics;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;


/**
 * Class StatisticsFactory
 * @package Positibe\Bundle\MailingBundle\Factory
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class StatisticsFactory implements FactoryInterface
{
    protected $tokenGenerator;

    public function __construct(TokenGeneratorInterface $tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * @return Statistics
     */
    public function createNew()
    {
        $statistics = new Statistics();
        $statistics->setToken($this->tokenGenerator->generateToken());

        return $statistics;
    }

    /**
     * @param Mail $mail
     * @param $email
     * @param $name
     * @return Statistics
     */
    public function createNewByData(Mail $mail, $email, $name)
    {
        $statistics = $this->createNew();
        $statistics->setMail($mail);
        $statistics->setEmail($email);
        $statistics->setName($name);

        return $statistics;
    }
} 