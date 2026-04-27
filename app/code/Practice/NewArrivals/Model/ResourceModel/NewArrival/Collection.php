<?php
namespace Practice\NewArrivals\Model\ResourceModel\NewArrival;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        // Link the Model and the ResourceModel together for this collection
        $this->_init(
            \Practice\NewArrivals\Model\NewArrival::class,
            \Practice\NewArrivals\Model\ResourceModel\NewArrival::class
        );
    }
}
