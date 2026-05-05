<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Practice\Waitlist\Controller\Manage;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Practice\Waitlist\Model\WaitlistFactory;


class Submit extends Action implements HttpPostActionInterface
{
    protected $resultJsonFactory;

    public function __construct(
    Context $context,
    JsonFactory $resultJsonFactory,
    WaitlistFactory $waitlistFactory
) {
    parent::__construct($context);
    $this->resultJsonFactory = $resultJsonFactory;
    $this->waitlistFactory = $waitlistFactory;
}

    public function execute(){
       $result = $this->resultJsonFactory->create();
    
       if (!$this->getRequest()->isPost()) {
           return $result->setData(['success' => false, 'message' => 'Invalid request']);
       }
    try {
        // 1. Get form data
        $data = $this->getRequest()->getParams();
    
        // 2. Save to database using your Model
        $model = $this-> waitlistFactory->create();
        $model->setData($data);
        $model->save();

        // 3. Return success
        $result->setData(['success' => true, 'message' => 'Added to waitlist!']);

    } catch (\Exception $e) {
        // Return error
        $result->setData(['success' => false, 'message' => 'Something went wrong!']);
    }

    return $result;

    }
}