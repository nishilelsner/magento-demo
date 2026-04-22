<?php
namespace Webkul\BlogManager\Block\Adminhtml\Blog;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * @var string
     */
    protected $_objectId;

    /**
     * @var string
     */
    protected $_blockGroup;

    /**
     * @var string
     */
    protected $_controller;

    /**
     * @var Magento\Backend\Block\Widget\Button\ButtonList
     */
    protected $buttonList;

    /**
     * Dependency Initilization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'Webkul_BlogManager';
        $this->_controller = 'adminhtml_blog';
        $this->buttonList->remove('delete');
        parent::_construct();
    }
}