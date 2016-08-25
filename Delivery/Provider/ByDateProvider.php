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

use Doctrine\ORM\EntityManager;
use Positibe\Bundle\MailingBundle\Repository\MailRepository;


/**
 * Class ByDateProvider
 * @package Positibe\Bundle\MailingBundle\Sender\Provider
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class ByDateProvider implements ProviderInterface
{
    private $em;
    private $mailClass;

    public function __construct(EntityManager $entityManager, $mailClass = 'PositibeMailingBundle:Mail')
    {
        $this->em = $entityManager;
        $this->mailClass = $mailClass;
    }

    public function getMails(array $options)
    {
        return $this->getMailRepository()->findInQueueByDate($options['date']);
    }

    /**
     * @return MailRepository
     */
    public function getMailRepository()
    {
        return $this->em->getRepository($this->mailClass);
    }

    /**
     * @param array $options
     * @return bool|mixed
     */
    public function validateOptions(array $options)
    {
        if (isset($options['date'])) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'by_date_sender';
    }


} 