<?php
namespace Webkul\BlogManager\Model\ResourceModel\Blog\Grid;

use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Search\AggregationInterface;
use Webkul\BlogManager\Model\ResourceModel\Blog\Collection as BlogCollection;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Collection extends BlogCollection implements SearchResultInterface
{
    /**
     * Dependency Initilization
     *
     * @param EntityFactoryInterface $entityFactory
     * @param LoggerInterface $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param ManagerInterface $eventManager
     * @param [type] $mainTable
     * @param [type] $resourceModel
     * @param [type] $model
     * @param \Magento\Framework\DB\Adapter\AdapterInterface|null $connection
     * @param AbstractDb|null $resource
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        $mainTable,
        $resourceModel,
        $model = \Magento\Framework\View\Element\UiComponent\DataProvider\Document::class,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        AbstractDb $resource = null
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $connection,
            $resource
        );
        $this->_init($model, $resourceModel);
        $this->setMainTable($mainTable);
    }

    /**
     * Get Agrigations
     *
     * @return \Magento\Framework\Api\Search\AggregationInterface
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * Set Agrigations
     *
     * @param \Magento\Framework\Api\Search\AggregationInterface $aggregations $aggregations
     * @return \Magento\Framework\Api\Search\SearchResultInterface
     */
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;
    }

    /**
     * Get Search Criteria
     *
     * @return null
     */
    public function getSearchCriteria()
    {
        return null;
    }
    
    /**
     * Set Search Criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface|null $searchCriteria
     * @return this
     */
    public function setSearchCriteria(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null
    ) {
        return $this;
    }

    /**
     * Returns the total count of the bolg
     *
     * @return int
     */
    public function getTotalCount()
    {
        return $this->getSize();
    }

    /**
     * Sets the total count of the bolg
     *
     * @param int $totalCount
     * @return this
     */
    public function setTotalCount($totalCount)
    {
        return $this;
    }

    /**
     * Set Item
     *
     * @param array|null $items
     * @return int
     */
    public function setItems(array $items = null)
    {
        return $this;
    }

    /**
     * Render Filter before
     *
     * @return void
     */
    protected function _renderFiltersBefore()
    {
        $cgfTable = $this->getTable('customer_grid_flat');
        $this->getSelect()->joinLeft(
            $cgfTable.' as cgf',
            'main_table.user_id = cgf.entity_id',
            [
                'user_name'=>'cgf.name'
            ]
        );
        parent::_renderFiltersBefore();
    }
    
    /**
     * Initilize Select
     *
     * @return void
     */
    protected function _initSelect()
    {
        $this->addFilterToMap('user_name', 'cgf.name');
        parent::_initSelect();
    }
}