<?php
/**
 * SaveButton.php
 * This block defines the "Save Product" button on the Edit/Add form.
 */
namespace Practice\NewArrivals\Block\Adminhtml\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton implements ButtonProviderInterface
{
    /**
     * Returns the configuration array for the Save button.
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save Product'), // Text on the button
            'class' => 'save primary', // 'primary' makes the button orange/blue (the main action)
            'data_attribute' => [
                // This tells Magento's UI Component JS to trigger a "save" event when clicked
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90, // Higher sort order places it to the right of the Back button
        ];
    }
}
