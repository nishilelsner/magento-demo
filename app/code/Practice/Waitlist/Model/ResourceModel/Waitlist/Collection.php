<?php
namespace Practice\Waitlist\Model\ResourceModel\Waitlist;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        // Link the Model and the ResourceModel together for this collection
        $this->_init(
            \Practice\Waitlist\Model\Waitlist::class,
            \Practice\Waitlist\Model\ResourceModel\Waitlist::class
        );
    }
}
