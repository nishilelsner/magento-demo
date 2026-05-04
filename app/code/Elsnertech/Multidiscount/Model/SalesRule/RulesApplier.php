<?php
/**
 * @author Elsner Team
 * @copyright Technologies Pvt. Ltd (https://www.elsner.com/)
 * @package Elsnertech_Multidiscount
 */
namespace Elsnertech\Multidiscount\Model\SalesRule;

use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\SalesRule\Model\Data\RuleDiscount;
use Magento\SalesRule\Model\Quote\ChildrenValidationLocator;
use Magento\Framework\App\ObjectManager;
use Magento\SalesRule\Model\Rule\Action\Discount\CalculatorFactory;
use Magento\SalesRule\Model\Rule\Action\Discount\Data;
use Magento\SalesRule\Model\Rule\Action\Discount\DataFactory;
use Magento\SalesRule\Api\Data\RuleDiscountInterfaceFactory;
use Magento\SalesRule\Api\Data\DiscountDataInterfaceFactory;
use Elsnertech\Multidiscount\Helper\Data as MultidiscountHelper;

class RulesApplier extends \Magento\SalesRule\Model\RulesApplier
{
    /**
     * Application Event Dispatcher
     *
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $_eventManager;

    /**
     * @var \Magento\SalesRule\Model\Utility
     */
    protected $validatorUtility;

    /**
     * @var ChildrenValidationLocator
     */
    private $childrenValidationLocator;

    /**
     * @var CalculatorFactory
     */
    private $calculatorFactory;

    /**
     * @var \Magento\SalesRule\Model\Rule\Action\Discount\DataFactory
     */
    protected $discountFactory;

    /**
     * @var RuleDiscountInterfaceFactory
     */
    private $discountInterfaceFactory;

    /**
     * @var DiscountDataInterfaceFactory
     */
    private $discountDataInterfaceFactory;

    /**
     * @var array
     */
    private $discountAggregator;

    /**
     * @var array;
     */
    protected $_appliedDiscount;

    /**
     * @var MultidiscountHelper
     */
    protected $helper;
    /**
     * Constructor
     *
     * @param CalculatorFactory $calculatorFactory
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\SalesRule\Model\Utility $utility
     * @param ChildrenValidationLocator|null $childrenValidationLocator
     * @param DataFactory|null $discountDataFactory
     * @param RuleDiscountInterfaceFactory|null $discountInterfaceFactory
     * @param DiscountDataInterfaceFactory|null $discountDataInterfaceFactory
     * @param MultidiscountHelper|null $helper
     */
    public function __construct(
        \Magento\SalesRule\Model\Rule\Action\Discount\CalculatorFactory $calculatorFactory,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\SalesRule\Model\Utility $utility,
        ?ChildrenValidationLocator $childrenValidationLocator = null,
        ?DataFactory $discountDataFactory = null,
        ?RuleDiscountInterfaceFactory $discountInterfaceFactory = null,
        ?DiscountDataInterfaceFactory $discountDataInterfaceFactory = null,
        ?MultidiscountHelper $helper = null
    ) {
        $this->calculatorFactory = $calculatorFactory;
        $this->validatorUtility = $utility;
        $this->_eventManager = $eventManager;
        $this->childrenValidationLocator = $childrenValidationLocator
             ?: ObjectManager::getInstance()->get(ChildrenValidationLocator::class);
        $this->discountFactory = $discountDataFactory ?: ObjectManager::getInstance()->get(DataFactory::class);
        $this->discountInterfaceFactory = $discountInterfaceFactory
            ?: ObjectManager::getInstance()->get(RuleDiscountInterfaceFactory::class);
        $this->discountDataInterfaceFactory = $discountDataInterfaceFactory
            ?: ObjectManager::getInstance()->get(DiscountDataInterfaceFactory::class);
        $this->helper = $helper ?: ObjectManager::getInstance()->get(MultidiscountHelper::class);
        $this->_appliedDiscount = [];
        parent::__construct(
            $calculatorFactory,
            $eventManager,
            $utility,
            $childrenValidationLocator,
            $discountDataFactory,
            $discountInterfaceFactory,
            $discountDataInterfaceFactory
        );
    }
    
    /**
     * Calculate discount data for item and rule
     *
     * @param \Magento\Quote\Model\Quote\Item\AbstractItem $item
     * @param \Magento\SalesRule\Model\Rule $rule
     * @param \Magento\Quote\Model\Quote\Address $address
     * @param array $couponCodes
     * @return \Magento\SalesRule\Model\Rule\Action\Discount\Data
     */
    protected function getDiscountData($item, $rule, $address, array $couponCodes = [])
    {
        $address = $item->getAddress();
        $qty = $this->validatorUtility->getItemQty($item, $rule, $address);
        $itemDiscountAmountBeforeRule = (float)$item->getDiscountAmount();

        $discountCalculator = $this->calculatorFactory->create($rule->getSimpleAction());
        $qty = $discountCalculator->fixQuantity($qty, $rule);
        $discountData = $discountCalculator->calculate($rule, $item, $qty);

        $this->eventFix($discountData, $item, $rule, $qty);
        $this->validatorUtility->deltaRoundingFix($discountData, $item);

        /**
         * We can't use row total here because row total not include tax
         * Discount can be applied on price included tax
         */
        $this->validatorUtility->minFix($discountData, $item, $qty);

        /* Multiline Discount Start */
        $address = $item->getAddress();
        $ruleLabel = $rule->getStoreLabel($address->getQuote()->getStore());
        $label = '';
        if ($ruleLabel) {
            $label = $ruleLabel;
        }
        $ruleStoreLabel = $label;
        if ($label == '') {
            $ruleStoreLabel = $rule->getName();
        }
        if ($rule->getCouponType() != \Magento\SalesRule\Model\Rule::COUPON_TYPE_NO_COUPON && !empty($couponCodes)) {
            $couponCode = reset($couponCodes);
            if ($couponCode && strpos($ruleStoreLabel, $couponCode) === false) {
                $ruleStoreLabel .= ' (' . $couponCode . ')';
            }
        }

        $discountAmount = $discountData->getOriginalAmount();
        if (!$discountAmount) {
            $discountAmount = max(0, $discountData->getAmount() - $itemDiscountAmountBeforeRule);
        }
        
        if ($discountAmount && $this->helper->isEnable()) {
            $this->_appliedDiscount[$rule->getId()][] = [
                'rule_id'=>$rule->getId(),
                'label'=>$ruleStoreLabel,
                'value'=> $discountAmount,
                'item_id'=> [$item->getId()]
            ];
        }

        /* Multiline Discount End */

        return $discountData;
    }

    /**
     * For set applied rule ids function
     *
     * @param \Magento\Quote\Model\Quote\Item\AbstractItem $item
     * @param array $appliedRuleIds
     * @return $this
     */
    public function setAppliedRuleIds(\Magento\Quote\Model\Quote\Item\AbstractItem $item, array $appliedRuleIds)
    {
        $address = $item->getAddress();
        $quote = $item->getQuote();

        $item->setAppliedRuleIds(join(',', $appliedRuleIds));
        $address->setAppliedRuleIds($this->validatorUtility->mergeIds($address->getAppliedRuleIds(), $appliedRuleIds));
        $quote->setAppliedRuleIds($this->validatorUtility->mergeIds($quote->getAppliedRuleIds(), $appliedRuleIds));

        $discountArray = [];
        foreach ($this->_appliedDiscount as $key => $value) {
            $itemArray = [];
            $amount = 0;
            foreach ($value as $keyItem => $valueArray) {
                $itemArray[$key] = $valueArray;
                $amount = $amount+$valueArray['value'];
            }
            $itemArray[$key]['value'] = $amount;
            $discountArray[] = $itemArray[$key];
        }

        $actualDiscountAmount = 0;
        foreach ($quote->getAllVisibleItems() as $quoteItem) {
            $actualDiscountAmount += (float)$quoteItem->getDiscountAmount();
        }

        $splitDiscountAmount = 0;
        foreach ($discountArray as $discount) {
            $splitDiscountAmount += (float)$discount['value'];
        }

        $discountDifference = $splitDiscountAmount - $actualDiscountAmount;
        if ($actualDiscountAmount > 0 && $discountDifference > 0.0001 && !empty($discountArray)) {
            $lastDiscountKey = count($discountArray) - 1;
            $discountArray[$lastDiscountKey]['value'] = max(
                0,
                (float)$discountArray[$lastDiscountKey]['value'] - $discountDifference
            );
        }
        
        $quote->setMultilineDiscount(json_encode($discountArray));

        return $this;
    }
}
