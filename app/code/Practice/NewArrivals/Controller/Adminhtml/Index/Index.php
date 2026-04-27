<?php
namespace Practice\NewArrivals\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /**
     * Permission check via ACL resource
     */
    const ADMIN_RESOURCE = 'Practice_NewArrivals::newarrivals';

    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Practice_NewArrivals::main');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage New Arrivals'));

        return $resultPage;
    }
}
