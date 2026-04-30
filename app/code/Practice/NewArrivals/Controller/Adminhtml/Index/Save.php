<?php
/**
 * Save.php
 * This controller catches the POST data when the admin clicks the "Save Product" button.
 */
namespace Practice\NewArrivals\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Practice\NewArrivals\Model\NewArrivalFactory;

class Save extends Action
{
    // Security check
    const ADMIN_RESOURCE = 'Practice_NewArrivals::newarrivals';

    /**
     * @var NewArrivalFactory
     * We use a Factory to generate empty Models so we can save data into them.
     */
    protected $newArrivalFactory;

    public function __construct(
        Context $context,
        NewArrivalFactory $newArrivalFactory
    ) {
        parent::__construct($context);
        $this->newArrivalFactory = $newArrivalFactory;
    }

    public function execute()
    {
        // 1. Get all the data submitted by the UI Form
        $data = $this->getRequest()->getPostValue();

        // 2. Prepare a "Redirect" object so we can send the user back to the Grid later
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            $id = $this->getRequest()->getParam('entity_id');

            // 3. Create a fresh, empty Model object
            $model = $this->newArrivalFactory->create();

            // 4. If an ID exists, we are editing. Load the existing DB row into the Model first.
            if ($id) {
                $model->load($id);
                if (!$model->getId()) {
                    $this->messageManager->addErrorMessage(__('This product no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            if (empty($data['entity_id'])) {
                unset($data['entity_id']);
            }

            // 5. Take the Form array data and inject it into the Model
            $model->setData($data);

            try {
                // 6. The Magic! Save the Model to the database.
                $model->save();

                // Show a green success banner at the top of the page
                $this->messageManager->addSuccessMessage(__('You successfully saved the New Arrival Product.'));

                // Redirect back to the Grid table
                return $resultRedirect->setPath('*/*/index');

            } catch (\Exception $e) {
                // If MySQL crashes or something fails, catch it and show a red error banner
                $this->messageManager->addErrorMessage($e->getMessage());

                // Redirect them back to the form so they don't lose their typed data
                return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
            }
        }

        // If someone visits /admin/newarrivals/index/save directly without submitting a form, kick them back
        return $resultRedirect->setPath('*/*/');
    }
}
