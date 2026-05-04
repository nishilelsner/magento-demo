<?php
/**
 * @author Elsner Team
 * @copyright Technologies Pvt. Ltd (https://www.elsner.com/)
 * @package Elsnertech_Multidiscount
 */
namespace Elsnertech\Multidiscount\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @var \Magento\Framework\Module\ModuleListInterface
     */
    protected $_moduleList;

    /**
     * Strings
     */
    public const MULTI_ENABLE = 'multiline/general/activate';
    
    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\Module\ModuleListInterface $moduleList
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Module\ModuleListInterface $moduleList
    ) {
        $this->_logger                  = $context->getLogger();
        $this->_moduleList              = $moduleList;
        parent::__construct($context);
    }

    /**
     * Check Module is Enable or not
     *
     * @return bool
     */
    public function isEnable()
    {
        return $this->getConfig(self::MULTI_ENABLE);
    }

    /**
     * GetConfig
     *
     * @param varchar $identifier
     * @return Store Config Data
     */
    public function getConfig($identifier)
    {
        return $this->scopeConfig->getValue(
            $identifier,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
