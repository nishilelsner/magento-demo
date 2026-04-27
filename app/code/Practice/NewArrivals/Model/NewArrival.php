<?php
namespace Practice\NewArrivals\Model;

use Magento\Framework\Model\AbstractModel;

class NewArrival extends AbstractModel
{
    protected function _construct()
    {
        // Tell Magento which ResourceModel handles the DB talk for this model
        $this->_init(\Practice\NewArrivals\Model\ResourceModel\NewArrival::class);
    }
}
