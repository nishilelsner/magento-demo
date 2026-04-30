<?php
namespace Practice\NewArrivals\Model\NewArrival;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Practice\NewArrivals\Model\ResourceModel\NewArrival\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface; // Helps remember data if saving fails

class DataProvider extends AbstractDataProvider
{
    protected $loadedData;
    protected $dataPersistor;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor, // Inject Data Persistor
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        // 1. If we already loaded the data, don't do it again. Just return it!
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        // 2. Fetch the data from the database collection
        $items = $this->collection->getItems();

        foreach ($items as $model) {
            // Magento expects the data in a specific array format based on the ID
            $this->loadedData[$model->getId()] = $model->getData();
        }

        // 3. Catch Data from Session (if saving failed!)
        // Imagine typing a huge form, hitting save, and getting an error. You don't want to lose what you typed!
        $data = $this->dataPersistor->get('practice_new_arrivals_new_arrival');
        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('practice_new_arrivals_new_arrival');
        }

        return $this->loadedData;
    }
}
