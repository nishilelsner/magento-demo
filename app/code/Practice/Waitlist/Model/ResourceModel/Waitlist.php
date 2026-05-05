<?php
namespace Practice\Waitlist\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Waitlist extends AbstractDb
{
    protected function _construct()
    {
        // Tell Magento the table name ('practice_waitlist') and the primary key ('entity_id')
        $this->_init('practice_waitlist', 'entity_id');
    }
}
