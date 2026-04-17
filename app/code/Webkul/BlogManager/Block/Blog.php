<?php
namespace Webkul\BlogManager\Block;

class Blog extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Webkul\BlogManager\Model\BlogFactory
     */
    protected $blogFactory;

    /**
     * Dependency Initilization
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Webkul\BlogManager\Model\BlogFactory $blogFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Webkul\BlogManager\Model\BlogFactory $blogFactory,
        array $data = []
    ) {
        $this->blogFactory = $blogFactory;
        parent::__construct($context, $data);
    }

    /**
     * Get Blog
     *
     * @return \Webkul\BlogManager\Model\Blog
     */
    public function getBlog()
    {
        $blogId = $this->getRequest()->getParam('id');
        return $this->blogFactory->create()->load($blogId);
    }
}