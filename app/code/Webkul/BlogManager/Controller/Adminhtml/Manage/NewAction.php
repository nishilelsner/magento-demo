<?php
namespace Webkul\BlogManager\Controller\Adminhtml\Manage;

use Magento\Backend\App\Action;

class NewAction extends Action
{
    /**
     * Provides content
     *
     * @return void
     */
    public function execute()
    {
        $this->_forward('edit');
    }

    /**
     * Check Autherization
     *
     * @return boolean
     */
    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Webkul_BlogManager::add');
    }
}