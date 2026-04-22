<?php
namespace Webkul\BlogManager\Controller\Adminhtml\Manage;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

class Edit extends Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var \Webkul\BlogManager\Model\BlogFactory
     */
    protected $blogFactory;

    /**
     * Dependency Initilization
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Webkul\BlogManager\Model\BlogFactory $blogFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Webkul\BlogManager\Model\BlogFactory $blogFactory
    ) {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->blogFactory = $blogFactory;
    }

    /**
     * Provides content
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $blogId = $this->getRequest()->getParam('id');
        $blogModel = $this->blogFactory->create()->load($blogId);
        $this->coreRegistry->register('blog_data', $blogModel);

        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__("Edit Blog"));
        return $resultPage;
    }

    /**
     * Check Autherization
     *
     * @return boolean
     */
    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_BlogManager::edit');
    }
}