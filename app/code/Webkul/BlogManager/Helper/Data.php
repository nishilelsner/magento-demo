<?php
namespace Webkul\BlogManager\Helper;

use Magento\Customer\Model\Session;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var Magento\Customer\Model\Session
     */
    protected $customerSession;
    
    /**
     * Dependency Initilization
     *
     * @param Session $customerSession
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        Session $customerSession,
        \Magento\Framework\App\Helper\Context $context
    ) {
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }

    /**
     * Get Customer Id
     *
     * @return int
     */
    public function getCustomerId()
    {
        $customerId = $this->customerSession->getCustomerId();
        return $customerId;
    }
}