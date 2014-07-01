<?php

namespace Isics\Bundle\OpenMiamMiamBundle\Model\SalesOrder;

use Isics\Bundle\OpenMiamMiamBundle\Entity\BranchOccurrence;
use Isics\Bundle\OpenMiamMiamBundle\Entity\SalesOrderRow;

/**
 * Represent a SalesOrderRow collection specific to a branch occurence
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class BranchOccurrenceSalesOrderRowCollection
{
    /**
     * @var BranchOccurrence
     */
    private $branchOccurrence;

    /**
     * @var SalesOrderRow[]
     */
    private $salesOrderRows;

    /**
     * @param BranchOccurrence $branchOccurrence
     * @param SalesOrderRow[]  $salesOrderRows
     */
    public function __construct(BranchOccurrence $branchOccurrence, array $salesOrderRows)
    {
        $this->branchOccurrence = $branchOccurrence;
        $this->salesOrderRows   = array_filter(
            $salesOrderRows,
            function (SalesOrderRow $salesOrderRow) use ($branchOccurrence) {
                return $salesOrderRow->getSalesOrder()->getBranchOccurrence()->getId() === $branchOccurrence->getId();
            }
        );
    }

    /**
     * @return BranchOccurrence
     */
    public function getBranchOccurrence()
    {
        return $this->branchOccurrence;
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        return array_sum(
            array_map(
                function (SalesOrderRow $salesOrderRow) {
                    return $salesOrderRow->getTotal();
                },
                $this->salesOrderRows
            )
        );
    }
}
