<?php
/**
 * @author Elsner Team
 * @copyright Technologies Pvt. Ltd (https://www.elsner.com/)
 * @package Elsnertech_Multidiscount
 */
namespace Elsnertech\Multidiscount\Model\Sales\Order\Pdf;

/**
 * Sales Order Creditmemo PDF model
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Creditmemo extends \Magento\Sales\Model\Order\Pdf\Creditmemo
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Elsnertech\Multidiscount\Helper\Data
     */
    protected $_multidiscountHelper;

    /**
     * Construct function
     *
     * @param \Magento\Payment\Helper\Data $paymentData
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Sales\Model\Order\Pdf\Config $pdfConfig
     * @param \Magento\Sales\Model\Order\Pdf\Total\Factory $pdfTotalFactory
     * @param \Magento\Sales\Model\Order\Pdf\ItemsFactory $pdfItemsFactory
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Sales\Model\Order\Address\Renderer $addressRenderer
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Store\Model\App\Emulation $localeResolver
     * @param \Elsnertech\Multidiscount\Helper\Data $multidiscountHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Sales\Model\Order\Pdf\Config $pdfConfig,
        \Magento\Sales\Model\Order\Pdf\Total\Factory $pdfTotalFactory,
        \Magento\Sales\Model\Order\Pdf\ItemsFactory $pdfItemsFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Sales\Model\Order\Address\Renderer $addressRenderer,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Store\Model\App\Emulation $localeResolver,
        \Elsnertech\Multidiscount\Helper\Data $multidiscountHelper,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        $this->_localeResolver = $localeResolver;
        $this->_multidiscountHelper = $multidiscountHelper;
        parent::__construct(
            $paymentData,
            $string,
            $scopeConfig,
            $filesystem,
            $pdfConfig,
            $pdfTotalFactory,
            $pdfItemsFactory,
            $localeDate,
            $inlineTranslation,
            $addressRenderer,
            $storeManager,
            $localeResolver,
            $data
        );
    }

    /**
     * For insert totals function
     *
     * @param [type] $page
     * @param [type] $source
     * @return void
     */
    protected function insertTotals($page, $source)
    {

        $order = $source->getOrder();
        $totals = $this->_getTotalsList();
        $lineBlock = ['lines' => [], 'height' => 15];
        
        foreach ($totals as $total) {
            $total->setOrder($order)->setSource($source);

            if ($total->canDisplay()) {
                $total->setFontSize(10);
                foreach ($total->getTotalsForDisplay() as $totalData) {

                    $multiDiscount = $order->getMultilineDiscount();

                    if (strpos($totalData['label'], 'Discount') !== false &&
                        $this->_multidiscountHelper->isEnable() &&
                        $multiDiscount != ''
                    ) {
                        $multiDiscount = json_decode($multiDiscount);

                        foreach ($multiDiscount as $discount) {
                            
                            $lineBlock['lines'][] = [
                                [
                                    'text'      => $discount->label,
                                    'feed'      => 475,
                                    'align'     => 'right',
                                    'font_size' => $totalData['font_size'],
                                    'font'      => 'bold'
                                ],
                                [
                                    'text'      => $order->formatPriceTxt((-1) * $discount->value),
                                    'feed'      => 565,
                                    'align'     => 'right',
                                    'font_size' => $totalData['font_size'],
                                    'font'      => 'bold'
                                ],
                            ];
                        }
                    } else {
                        $lineBlock['lines'][] = [
                            [
                                'text' => $totalData['label'],
                                'feed' => 475,
                                'align' => 'right',
                                'font_size' => $totalData['font_size'],
                                'font' => 'bold',
                            ],
                            [
                                'text' => $totalData['amount'],
                                'feed' => 565,
                                'align' => 'right',
                                'font_size' => $totalData['font_size'],
                                'font' => 'bold'
                            ],
                        ];
                    }
                    
                }
            }
        }

        $this->y -= 20;
        $page = $this->drawLineBlocks($page, [$lineBlock]);
        return $page;
    }
}
