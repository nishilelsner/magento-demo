<?php
namespace Practice\Waitlist\Model;

use Magento\Framework\Model\AbstractModel;

class Waitlist extends AbstractModel
{
    protected function _construct()
    {
        // Tell Magento which ResourceModel handles the DB talk for this model
        $this->_init(\Practice\Waitlist\Model\ResourceModel\Waitlist::class);
    }
}