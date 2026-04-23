<?php
namespace Webkul\BlogManager\Block;

class BlogList extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Webkul\BlogManager\Model\ResourceModel\Blog\CollectionFactory
     */
    protected $blogCollection;

    /**
     * @var \Webkul\BlogManager\Helper\Data
     */
    protected $helper;

    /**
     * @var \Webkul\BlogManager\Model\Blog\Status
     */
    protected $blogStatus;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $date;

    /**
     * Dependency Initilization
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Webkul\BlogManager\Model\ResourceModel\Blog\CollectionFactory $blogCollection
     * @param \Webkul\BlogManager\Helper\Data $helper
     * @param \Webkul\BlogManager\Model\Blog\Status $blogStatus
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $date
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Webkul\BlogManager\Model\ResourceModel\Blog\CollectionFactory $blogCollection,
        \Webkul\BlogManager\Helper\Data $helper,
        \Webkul\BlogManager\Model\Blog\Status $blogStatus,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $date,
        array $data = []
    ) {
        $this->blogCollection = $blogCollection;
        $this->helper = $helper;
        $this->blogStatus = $blogStatus;
        $this->date = $date;
        parent::__construct($context, $data);
    }

    /**
     * Get Blog List
     *
     * @return \Webkul\BlogManager\Model\ResourceModel\Blog\Collection
     */
    public function getBlogs()
    {
        $customerId = $this->helper->getCustomerId();
        $collection = $this->blogCollection->create();
        $collection->addFieldToFilter('user_id', ['eq'=>$customerId])->setOrder('updated_at', 'DESC');
        return $collection;
    }

    /**
     * Get Blog Status
     *
     * @return boolean
     */
    public function getStatuses()
    {
        $statuses = [];
        foreach ($this->blogStatus->toOptionArray() as $status) {
            $statuses[$status['value']] = $status['label'];
        }
        return $statuses;
    }

    /**
     * Get Formatted Date
     *
     * @param date $date
     * @return date
     */
    public function getFormattedDate($date)
    {
        return $this->date->date($date)->format('d/m/y H:i');
    }
}