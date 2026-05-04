<?php
/**
 * @author Elsner Team
 * @copyright Technologies Pvt. Ltd (https://www.elsner.com/)
 * @package Elsnertech_Multidiscount
 */
namespace Elsnertech\Multidiscount\Observer;

use Magento\Checkout\Model\Session as CheckoutSession;
use Elsnertech\Multidiscount\Helper\Data as MultidiscountHelper;

class CheckoutSubmitAllAfter implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var MultidiscountHelper
     */
    protected $helper;

    /**
     * Constructor
     *
     * @param CheckoutSession $checkoutSession
     * @param MultidiscountHelper $helper
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        MultidiscountHelper $helper
    ) {
        $this->_checkoutSession = $checkoutSession;
        $this->helper = $helper;
    }
    /**
     * Observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * Update balance and send store credit email
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->helper->isEnable()) {
            return;
        }

        $quote = $this->getQuote();
        $mitliLineDiscount = $quote->getMultilineDiscount();
        $mitliLineDiscount = $this->filterMultilineDiscount($mitliLineDiscount);
        $order = $observer->getEvent()->getOrder();
       
        if ($order->getMultilineDiscount() == '' && !empty($mitliLineDiscount)) {
            $order->setMultilineDiscount(json_encode($mitliLineDiscount));
            $order->save();
        }
    }

    /**
     * GetQuote
     *
     * @return Magento\Checkout\Model\Session
     */
    public function getQuote()
    {

        return $this->_checkoutSession->getQuote();
    }

    /**
     * FilterMultilineDiscount
     *
     * @param int $multidiscounts
     */
    protected function filterMultilineDiscount($multidiscounts)
    {
        $filteredMultilineDiscount = [];
        $appliedRuleId = [];
        
        if ($multidiscounts != '') {
            $multidiscounts = json_decode($multidiscounts);

            foreach ($multidiscounts as $multidiscount) {
                    
                if (!in_array($multidiscount->rule_id, $appliedRuleId)) {
                    $filteredMultilineDiscount[] = [
                        'rule_id' => $multidiscount->rule_id,
                        'label' => $multidiscount->label,
                        'value' => $multidiscount->value
                    ];
                    $appliedRuleId[] = $multidiscount->rule_id;
                }
            }
        }

        return $filteredMultilineDiscount;
    }
}
