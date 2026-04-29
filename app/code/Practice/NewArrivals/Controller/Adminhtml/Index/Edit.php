<?php
namespace Practice\NewArrivals\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action
{
    // Security check (same as Add.php)
    const ADMIN_RESOURCE = 'Practice_NewArrivals::newarrivals';

    /**
     * @var PageFactory
     * We define a protected variable to store the PageFactory.
     * 'protected' means only this class (and child classes) can touch it.
     */
    protected $resultPageFactory;

    /**
     * The Constructor!
     * This runs automatically when Magento creates this class.
     * We ask Magento to "inject" the tools we need: Context (the toolbox) and PageFactory.
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        // We MUST pass $context to the parent class so standard Magento stuff works
        parent::__construct($context);

        // We save the injected PageFactory into our protected variable so we can use it later
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * This runs when the URL is /admin/newarrivals/index/edit
     * (OR when Add.php forwards to it!)
     */
    public function execute()
    {
        // 1. Ask the PageFactory to create a blank, empty page object
        $resultPage = $this->resultPageFactory->create();

        // 2. Tell Magento to keep "New Arrivals" highlighted in the left admin sidebar
        $resultPage->setActiveMenu('Practice_NewArrivals::main');

        /**
         * 3. Check the URL for an 'id'. 
         * If the URL is /edit/id/5, then $id will be 5.
         * If we came from Add.php, there is no ID in the URL, so $id will be null.
         */
        $id = $this->getRequest()->getParam('id');

        /**
         * 4. Set the Page Title dynamically!
         * We use a shorthand if/else statement (ternary operator).
         * If $id exists -> Title is "Edit New Arrival Product"
         * If $id is null -> Title is "Add New Arrival Product"
         */
        $resultPage->getConfig()->getTitle()->prepend(
            $id ? __('Edit New Arrival Product') : __('Add New Arrival Product')
        );

        // 5. Finally, return the constructed page back to the browser!
        return $resultPage;
    }
}
