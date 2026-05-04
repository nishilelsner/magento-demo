<?php
/**
 * @author Elsner Team
 * @copyright Elsner Technologies Pvt. Ltd (https://www.elsner.com/)
 * @package Elsnertech_Multidiscount
 */
namespace Elsnertech\Multidiscount\Block\Sales\Order;

use Elsnertech\Multidiscount\Helper\Data as MultidiscountHelper;
use Magento\Store\Model\ScopeInterface;

class Totals extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\DataObject
     */
    protected $_source;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_scopeConfig;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        $this->_scopeConfig = $context->getScopeConfig();
        parent::__construct($context, $data);
    }

    /**
     * InitTotals
     */
    public function initTotals()
    {
        $parent = $this->getParentBlock();
        $this->_source = $parent->getSource();

        if ((double)$this->_source->getDiscountAmount() != 0) {

            $multiDiscount = $this->_source->getMultilineDiscount();
            if ($multiDiscount != '' && $this->_scopeConfig
                ->getValue(MultidiscountHelper::MULTI_ENABLE, ScopeInterface::SCOPE_STORE)) {
                
                $multiDiscount = json_decode($multiDiscount);

                foreach ($multiDiscount as $discount) {
                    $total = new \Magento\Framework\DataObject(
                        [
                            'code' => 'discount_'.$discount->rule_id,
                            'strong' => false,
                            'value' => (-1) * $discount->value,
                            'label' => $discount->label
                        ]
                    );
                    $parent->addTotal($total, 'discount');
                }
                
                $parent->removeTotal('discount');
            }
        }
    }
}
