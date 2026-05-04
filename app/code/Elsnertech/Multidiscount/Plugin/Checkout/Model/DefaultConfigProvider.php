<?php
/**
 * @author Elsner Team
 * @copyright Elsner Technologies Pvt. Ltd (https://www.elsner.com/)
 * @package Elsnertech_Multidiscount
 */
namespace Elsnertech\Multidiscount\Plugin\Checkout\Model;

use Magento\Framework\App\ResourceConnection;
use Elsnertech\Multidiscount\Helper\Data as MultidiscountHelper;

class DefaultConfigProvider
{
    /**
     * @var ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var MultidiscountHelper
     */
    protected $helper;

    /**
     * Constructor
     *
     * @param ResourceConnection $resourceConnection
     * @param MultidiscountHelper $helper
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        MultidiscountHelper $helper
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->helper = $helper;
    }

    /**
     * @inheritdoc
     */
    public function aroundGetConfig(
        \Magento\Checkout\Model\DefaultConfigProvider $subject,
        \Closure $proceed
    ) {
        $result = $proceed();
        
        $isEnabled = (bool)$this->helper->isEnable();
        $result['multidiscountEnabled'] = $isEnabled;
 
        if ($isEnabled && isset($result['quoteItemData'])) {
            $quoteItemData = $result['quoteItemData'];
            
            $quoteId = 0;
            foreach ($result['quoteItemData'] as $item) {
                $quoteId = $item['quote_id'];
            }

            $quoteTable = $this->resourceConnection->getTableName('quote');
            $connection = $this->resourceConnection->getConnection();
            
            $select = $connection->select()
                ->from(
                    ['q' => $quoteTable],
                    ['q.multiline_discount']
                )->where("q.entity_id = ? ", $quoteId);

            $queryResult = $connection->fetchOne($select);

            $result['quoteData']['multilineDiscount'] = $queryResult;
        }

        return $result;
    }
}
