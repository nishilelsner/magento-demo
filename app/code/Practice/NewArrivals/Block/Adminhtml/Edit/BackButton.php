<?php
/**
 * BackButton.php
 * This block defines the "Back" button at the top of the Edit/Add form.
 */
namespace Practice\NewArrivals\Block\Adminhtml\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class BackButton implements ButtonProviderInterface
{
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * We inject the UrlBuilder so we can generate secure admin URLs with the secret key.
     */
    public function __construct(\Magento\Framework\UrlInterface $urlBuilder)
    {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Magento expects this method to return an array of configuration for the button.
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Back'),
            // Use the urlBuilder to generate the secure URL (with secret key) for newarrivals/index/index
            'on_click' => sprintf("location.href = '%s';", $this->urlBuilder->getUrl('*/*/index')),
            'class' => 'back',
            'sort_order' => 10
        ];
    }
}
