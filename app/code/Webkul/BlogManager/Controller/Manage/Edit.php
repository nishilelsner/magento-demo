<?php
namespace Webkul\BlogManager\Controller\Manage;

use Magento\Customer\Controller\AbstractAccount;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session;

class Edit extends AbstractAccount
{
    /**
     * @var Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Webkul\BlogManager\Model\BlogFactory
     */
    protected $blogFactory;

    /**
     * @var Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * Dependency Initilization
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param \Webkul\BlogManager\Model\BlogFactory $blogFactory
     * @param Session $customerSession
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Webkul\BlogManager\Model\BlogFactory $blogFactory,
        Session $customerSession,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->blogFactory = $blogFactory;
        $this->customerSession = $customerSession;
        $this->messageManager = $messageManager;
        parent::__construct($context);
    }

    /**
     * Provides content
     *
     * @return Magento\Framework\View\Result\Page
     * @return Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $blogId = $this->getRequest()->getParam('id');
        $customerId = $this->customerSession->getCustomerId();
        $isAuthorised = $this->blogFactory->create()
                                    ->getCollection()
                                    ->addFieldToFilter('user_id', $customerId)
                                    ->addFieldToFilter('entity_id', $blogId)
                                    ->getSize();
        if (!$isAuthorised) {
            $this->messageManager->addError(__('You are not authorised to edit this blog.'));
            return $this->resultRedirectFactory->create()->setPath('blog/manage');
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Edit Blog'));
        $layout = $resultPage->getLayout();
        return $resultPage;
    }
}