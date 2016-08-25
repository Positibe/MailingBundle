<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MailingBundle\Delivery\Provider;

use Positibe\Bundle\MailingBundle\Entity\Mail;

/**
 * Interface ProviderInterface
 * @package Positibe\Bundle\MailingBundle\Delivery\Provider
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
interface ProviderInterface
{
    /**
     * @param array $options
     * @return Mail[]
     */
    public function getMails(array $options);

    /**
     * @param array $options
     * @return bool|mixed
     */
    public function validateOptions(array $options);

    /**
     * @return string
     */
    public function getName();
} 