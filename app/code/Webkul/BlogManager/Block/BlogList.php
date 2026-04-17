<?php
namespace Webkul\BlogManager\Block;

use Magento\Customer\Model\SessionFactory;

class BlogList extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Webkul\BlogManager\Model\ResourceModel\Blog\CollectionFactory
     */
    protected $blogCollection;

    /**
     * @var Magento\Customer\Model\SessionFactory
     */
    protected $customerSession;

    /**
     * Dependency Initilization
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Webkul\BlogManager\Model\ResourceModel\Blog\CollectionFactory $blogCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Webkul\BlogManager\Model\ResourceModel\Blog\CollectionFactory $blogCollection,
        SessionFactory $customerSession,
        array $data = []
    ) {
        $this->blogCollection = $blogCollection;
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    /**
     * Get Blog List
     *
     * @return \Webkul\BlogManager\Model\ResourceModel\Blog\Collection
     */
    public function getBlogs()
    {
        $customerId = $this->customerSession->create()->getCustomer()->getId();
        $collection = $this->blogCollection->create();
        $collection->addFieldToFilter('user_id', ['eq'=>$customerId])->setOrder('updated_at', 'DESC')   ;
        return $collection;
    }
}