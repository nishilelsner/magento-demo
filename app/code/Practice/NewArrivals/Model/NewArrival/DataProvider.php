<?php
/**
 * DataProvider.php
 * The DataProvider acts as the bridge between the Database Collection and the UI Form.
 * When the form loads, it asks this class: "Do you have any data for me to display?"
 */
namespace Practice\NewArrivals\Model\NewArrival;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Practice\NewArrivals\Model\ResourceModel\NewArrival\CollectionFactory;

class DataProvider extends AbstractDataProvider
{
    /**
     * Constructor
     * Injects the CollectionFactory so we can talk to the practice_new_arrivals table.
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        // Initialize the collection using the factory
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * This method is called automatically by Magento when rendering the form.
     * It returns an array of data that pre-fills the input fields.
     */
    public function getData()
    {
        // For a NEW product, there is no existing data, so we return an empty array.
        // Later, we will add logic here to fetch data if we are EDITING an existing product.
        return [];
    }
}
