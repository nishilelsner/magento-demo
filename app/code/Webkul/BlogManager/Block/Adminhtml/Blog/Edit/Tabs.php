<?php
namespace Webkul\BlogManager\Block\Adminhtml\Blog\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Dependency Initilization
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Backend\Model\Auth\Session $authSession
     * @param \Magento\Framework\Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $jsonEncoder, $authSession, $data);
    }

    /**
     * Dependency Initilization
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('blog_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Blog Data'));
    }

    /**
     * Prepare form data
     *
     * @return \Magento\Backend\Block\Widget\Form
     */
    protected function _prepareLayout()
    {
        $this->addTab(
            'main',
            [
                'label' => __('Blog Data'),
                'content' => $this->getLayout()->createBlock(
                    'Webkul\BlogManager\Block\Adminhtml\Blog\Edit\Tab\Main'
                )->toHtml(),
                'active' => true
            ]
        );

        $this->addTab(
            'status',
            [
                'label' => __('Blog Status'),
                'content' => $this->getLayout()->createBlock(
                    'Webkul\BlogManager\Block\Adminhtml\Blog\Edit\Tab\Status'
                )->toHtml()
            ]
        );

        return parent::_prepareLayout();
    }
}