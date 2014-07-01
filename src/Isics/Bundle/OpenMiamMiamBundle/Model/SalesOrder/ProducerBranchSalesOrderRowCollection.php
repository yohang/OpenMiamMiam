<?php

namespace Isics\Bundle\OpenMiamMiamBundle\Model\SalesOrder;

use Isics\Bundle\OpenMiamMiamBundle\Entity\Branch;
use Isics\Bundle\OpenMiamMiamBundle\Entity\BranchOccurrence;
use Isics\Bundle\OpenMiamMiamBundle\Entity\Producer;
use Isics\Bundle\OpenMiamMiamBundle\Entity\SalesOrderRow;

/**
 * Represent a SalesOrderRow collection specific to a producer and a branch
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class ProducerBranchSalesOrderRowCollection
{
    /**
     * @var Producer
     */
    private $producer;

    /**
     * @var Branch
     */
    private $branch;

    /**
     * @var SalesOrderRow[]
     */
    private $salesOrderRows;

    /**
     * @param Producer        $producer
     * @param Branch          $branch
     * @param SalesOrderRow[] $salesOrderRows
     */
    public function __construct(Producer $producer, Branch $branch, array $salesOrderRows)
    {
        $this->producer = $producer;
        $this->branch   = $branch;

        $this->salesOrderRows = array_filter(
            $salesOrderRows,
            function (SalesOrderRow $salesOrderRow) use ($producer, $branch) {
                return
                    $salesOrderRow->getProducer()->getId() === $producer->getId() &&
                    $salesOrderRow->getSalesOrder()->getBranchOccurrence()->getBranch()->getId() === $branch->getId();
            }
        );
    }

    /**
     * @return Branch
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * @return Producer
     */
    public function getProducer()
    {
        return $this->producer;
    }

    /**
     * @return BranchOccurrenceSalesOrderRowCollection[]
     */
    public function getBranchOccurrenceSalesOrderRowCollections()
    {
        return array_map(
            function (BranchOccurrence $branchOccurrence) {
                return new BranchOccurrenceSalesOrderRowCollection($branchOccurrence, $this->salesOrderRows);
            },
            array_unique(
                array_map(
                    function (SalesOrderRow $salesOrderRow) {
                        return $salesOrderRow->getSalesOrder()->getBranchOccurrence();
                    },
                    $this->salesOrderRows
                ),
                SORT_REGULAR
            )
        );
    }

    /**
     * @return int[]
     */
    public function getTimestamps()
    {
        return array_map(
            function (BranchOccurrenceSalesOrderRowCollection $collection) {
                return $collection->getBranchOccurrence()->getBegin()->getTimestamp();
            },
            $this->getBranchOccurrenceSalesOrderRowCollections()
        );
    }

    /**
     * @param int $timestamp
     *
     * @return float
     */
    public function getTotalForTimestamp($timestamp)
    {
        foreach ($this->getBranchOccurrenceSalesOrderRowCollections() as $collection) {
            if ($collection->getBranchOccurrence()->getBegin()->getTimestamp() === $timestamp) {
                return $collection->getTotal();
            }
        }

        return null;
    }
}
