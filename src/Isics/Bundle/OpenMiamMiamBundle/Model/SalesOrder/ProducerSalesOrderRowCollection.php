<?php

namespace Isics\Bundle\OpenMiamMiamBundle\Model\SalesOrder;

use Isics\Bundle\OpenMiamMiamBundle\Entity\Branch;
use Isics\Bundle\OpenMiamMiamBundle\Entity\Producer;
use Isics\Bundle\OpenMiamMiamBundle\Entity\SalesOrderRow;

/**
 *
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class ProducerSalesOrderRowCollection
{
    /**
     * @var Producer
     */
    private $producer;

    /**
     * @var SalesOrderRow
     */
    private $salesOrderRows;

    /**
     * @param Producer        $producer
     * @param SalesOrderRow[] $salesOrderRows
     */
    public function __construct(Producer $producer, array $salesOrderRows)
    {
        $this->producer       = $producer;
        $this->salesOrderRows = array_filter(
            $salesOrderRows,
            function (SalesOrderRow $salesOrderRow) use ($producer) {
                return $salesOrderRow->getProducer()->getId() === $producer->getId();
            }
        );
    }

    /**
     * @return ProducerBranchSalesOrderRowCollection[]
     */
    public function getProducerBranchSalesOrderRowCollections()
    {
        return array_map(
            function (Branch $branch) {
                return new ProducerBranchSalesOrderRowCollection(
                    $this->producer,
                    $branch,
                    $this->salesOrderRows
                );
            },
            $this->producer->getBranches()->toArray()
        );
    }

    /**
     * @return array
     */
    public function getChartData()
    {
        $producerBranchSalesOrderRowCollections = $this->getProducerBranchSalesOrderRowCollections();
        $timestamps = array_unique(call_user_func_array(
            'array_merge',
            array_map(
                function (ProducerBranchSalesOrderRowCollection $collection) {
                    return $collection->getTimestamps();
                },
                $producerBranchSalesOrderRowCollections
            )
        ));

        $firstRow = ['Date'];
        foreach ($producerBranchSalesOrderRowCollections as $collection) {
            $firstRow[] = $collection->getBranch()->getName();
        }

        $data = [$firstRow];
        foreach ($timestamps as $timestamp) {
            $row = [ (new \DateTime)->setTimestamp($timestamp)->format('d/m/Y') ];
            foreach ($producerBranchSalesOrderRowCollections as $collection) {
                $row[] = $collection->getTotalForTimestamp($timestamp);
            }

            $data[] = $row;
        }

        return $data;
    }
}
