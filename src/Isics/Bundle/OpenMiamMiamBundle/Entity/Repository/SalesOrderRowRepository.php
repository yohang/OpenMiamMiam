<?php

namespace Isics\Bundle\OpenMiamMiamBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Isics\Bundle\OpenMiamMiamBundle\Entity\Producer;

/**
 * Doctrine ORM repository for SalesOrderRow
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class SalesOrderRowRepository extends EntityRepository
{
    /**
     * @param Producer  $producer
     * @param \DateTime $date
     *
     * @return \Isics\Bundle\OpenMiamMiamBundle\Entity\SalesOrderRow[]
     */
    public function findByProducerAfterDate(Producer $producer, \DateTime $date)
    {
        return $this->createQueryBuilder('sor')
            ->innerJoin('sor.salesOrder', 'so')
            ->where('sor.producer = :producer')
            ->andWhere('so.date > :date')
//            ->andWhere('so.date < CURRENT_DATE()')
            ->setParameter('producer', $producer)
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }
}
