<?php
/**
 * @author Elsner Team
 * @copyright Elsner Technologies Pvt. Ltd (https://www.elsner.com/)
 * @package Elsnertech_Multidiscount
 */
namespace Elsnertech\Multidiscount\Model\Total;

use Magento\Framework\App\ResourceConnection;

class MultilineDiscount extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
     /**
      * @var ResourceConnection
      */
    protected $resourceConnection;

    /**
     * Custom Country finder
     *
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->setCode('multiline_discount');
    }
    /**
     * For collect function
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return void
     */
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);
        $total->setData('multiline_discount', "");
        
        return $this;
    }

    /**
     * For fetch function
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return array
     */
    public function fetch(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        $result = null;
        if ($quote->getMultilineDiscount()) {
            $result = $quote->getMultilineDiscount();
        }

        return [
           'code' => $this->getCode(),
           'title' => $this->getLabel(),
           'area' =>  $result,
           'value' => $result
        ];
    }
    
    /**
     * For get label function
     *
     * @return string
     */
    public function getLabel()
    {
        return __('Multiline Discount');
    }
}
