<?php
/**
 * NewArrivalActions.php
 * This class builds the Action links (like Edit/Delete) for each row in the UI Component Grid.
 */
namespace Practice\NewArrivals\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class NewArrivalActions extends Column
{
    /**
     * @var UrlInterface
     * We need the UrlBuilder to generate URLs with the secret key attached.
     */
    protected $urlBuilder;

    /**
     * Constructor
     * Injects the standard UI Component dependencies along with our UrlBuilder.
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * prepareDataSource
     * This method intercepts the grid data right before it is displayed on the screen.
     * We loop through every row and attach the 'Edit' URL.
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        // Check if there are any rows (items) in the grid
        if (isset($dataSource['data']['items'])) {
            // Loop through each row by reference (&$item) so we can modify it directly
            foreach ($dataSource['data']['items'] as & $item) {
                // If the row has an entity_id...
                if (isset($item['entity_id'])) {
                    // Create the 'edit' action for this specific row
                    // $this->getData('name') gets the name of this column from the XML ("actions")
                    $item[$this->getData('name')] = [
                        'edit' => [
                            // Generate the secure URL: /admin/newarrivals/index/edit/id/5/
                            'href' => $this->urlBuilder->getUrl(
                                'newarrivals/index/edit',
                                ['id' => $item['entity_id']]
                            ),
                            'label' => __('Edit') // The text that appears in the dropdown
                        ]
                    ];
                }
            }
        }
        
        // Return the modified data back to Magento to display on the screen
        return $dataSource;
    }
}
