<?php
/**
 * In Magento, the namespace must perfectly match the folder structure.
 * Practice = Vendor, NewArrivals = Module
 * Controller/Adminhtml/Index = Folder Path
 */
namespace Practice\NewArrivals\Controller\Adminhtml\Index;

// We import the base Action class that all Admin controllers must extend.
use Magento\Backend\App\Action;

class Add extends Action
{
    /**
     * This is the Security Guard! 
     * It checks if the logged-in admin user has the permission we defined in acl.xml.
     * If they don't, Magento automatically blocks them from this page.
     */
    const ADMIN_RESOURCE = 'Practice_NewArrivals::newarrivals';

    /**
     * The execute() method is mandatory. It is the starting point that runs
     * whenever a user visits this specific URL (/admin/newarrivals/index/add).
     */
    public function execute()
    {
        /**
         * We do NOT build the page here.
         * The form to "Add" a product and "Edit" a product are exactly the same!
         * So, we use _forward('edit') to instantly pass the baton to the Edit.php controller.
         * This saves us from having to write the exact same code twice.
         */
        return $this->_forward('edit');
    }
}
