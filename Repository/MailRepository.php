<?php

namespace Positibe\Bundle\MailingBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Positibe\Bundle\MailingBundle\Entity\Mail;

/**
 * MailRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MailRepository extends EntityRepository
{
    /**
     * @param $date
     * @return array
     */
    public function findInQueueByDate($date)
    {
        $qb = $this->createQueryBuilder('n')
            ->addSelect('a')
            ->leftJoin('n.attachments', 'a')
            ->where('n.sendAt <= :date')
            ->andWhere('n.state = :readyToSentState')
            ->orderBy('n.createdAt', 'DESC')
            ->setParameters(
                array(
                    'date' => $date,
                    'readyToSentState' => Mail::STATE_IN_QUEUE
                )
            );
        $query = $qb->getQuery();

        return $query->getResult();
    }
}
