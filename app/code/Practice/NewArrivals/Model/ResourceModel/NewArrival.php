<?php
namespace Practice\NewArrivals\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class NewArrival extends AbstractDb
{
    protected function _construct()
    {
        // Tell Magento the table name ('practice_new_arrivals') and the primary key ('entity_id')
        $this->_init('practice_new_arrivals', 'entity_id');
    }
}
