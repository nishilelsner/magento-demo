<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Practice\Waitlist\Controller\Manage;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Practice\Waitlist\Model\WaitlistFactory;

class Submit extends Action implements HttpPostActionInterface, CsrfAwareActionInterface
{
    protected $resultJsonFactory;
    protected $waitlistFactory;
    protected $mailer;
    protected $scopeConfig;
    protected $productRepository;
    protected $storeManager;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        WaitlistFactory $waitlistFactory,
        \Practice\Waitlist\Model\Mailer $mailer,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->waitlistFactory = $waitlistFactory;
        $this->mailer = $mailer;
        $this->scopeConfig = $scopeConfig;
        $this->productRepository = $productRepository;
        $this->storeManager = $storeManager;
    }

    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }

    public function execute(){
       $result = $this->resultJsonFactory->create();
       $adminEmail = $this->scopeConfig->getValue(
        'trans_email/ident_general/email',
        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
       $baseUrl = $this->storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        );

       if (!$this->getRequest()->isPost()) {
           return $result->setData(['success' => false, 'message' => 'Invalid request']);
       }
    try {
        // 1. Get form data
        $data = $this->getRequest()->getParams();
        
        // Fetch product details
        $product = $this->productRepository->getById($data['product_id']);
        $data['product_name'] = $product->getName();
        $data['product_sku'] = $product->getSku();
        $data['product_price'] = $product->getPrice();
        $data['product_image_url'] = $baseUrl . 'catalog/product' . $product->getImage();
    
        // 2. Save to database using your Model
        $model = $this-> waitlistFactory->create();
        $model->setData($data);
        $model->save();

        $this->mailer->sendAdminEmail($data, $adminEmail);
        $this->mailer->sendCustomerEmail($data, $adminEmail);

        // 3. Return success
        $result->setData(['success' => true, 'message' => 'Added to waitlist!']);

    } catch (\Exception $e) {
        // Return error
        $result->setData(['success' => false, 'message' => 'Something went wrong!']);
    }

    return $result;

    }
}