<?php
namespace Webkul\BlogManager\Controller\Manage;

use Magento\Customer\Controller\AbstractAccount;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;

class Delete extends AbstractAccount
{
    /**
     * @var \Webkul\BlogManager\Model\BlogFactory
     */
    protected $blogFactory;

    /**
     * @var Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $jsonData;

    /**
     * Dependency Initilization
     *
     * @param Context $context
     * @param \Webkul\BlogManager\Model\BlogFactory $blogFactory
     * @param Session $customerSession
     * @param \Magento\Framework\Serialize\Serializer\Json $jsonData
     */
    public function __construct(
        Context $context,
        \Webkul\BlogManager\Model\BlogFactory $blogFactory,
        Session $customerSession,
        \Magento\Framework\Serialize\Serializer\Json $jsonData
    ) {
        $this->blogFactory = $blogFactory;
        $this->customerSession = $customerSession;
        $this->jsonData = $jsonData;
        parent::__construct($context);
    }

    /**
     * Provides content
     *
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
            $msg=__('You are not authorised to delete this blog.');
            $success=0;
        } else {
            $model = $this->blogFactory->create()->load($blogId);
            $model->delete();
            $msg=__('You have successfully deleted the blog.');
            $success=1;
        }     
        $this->getResponse()->setHeader('Content-type', 'application/javascript');
        $this->getResponse()->setBody(
            $this->jsonData->serialize(
                    [
                        'success' => $success,
                        'message' => $msg
                    ]
                ));
    }
}