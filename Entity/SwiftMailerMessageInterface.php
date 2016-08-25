<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\MailingBundle\Entity;


/**
 * Class SwiftMailerMessageInterface
 * @package Positibe\Bundle\MailingBundle\Entity
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
interface SwiftMailerMessageInterface
{
    public function setSwiftMailerMessage($swiftMailerMessage);

    public function getSwiftMailerMessage();
}