<?php
namespace Practice\Waitlist\Model;

use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Area;

class Mailer
{
    private $transportBuilder;
    private $inlineTranslation;
    private $storeManager;

    public function __construct(
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        StoreManagerInterface $storeManager
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->storeManager = $storeManager;
    }

    public function sendAdminEmail($data, $adminEmail)
    {
        $this->inlineTranslation->suspend();
        try {
            $transport = $this->transportBuilder
                ->setTemplateIdentifier(2)
                ->setTemplateOptions([
                    'area'  => Area::AREA_FRONTEND,
                    'store' => $this->storeManager->getStore()->getId()
                ])
                ->setTemplateVars([
                    'customer_name'    => $data['customer_name'],
                    'customer_email'   => $data['customer_email'],
                    'customer_phone'   => $data['customer_phone'],
                    'customer_comment' => $data['customer_comment'],
                    'product_name'     => $data['product_name'],
                    'product_id'       => $data['product_id']
                ])
                ->setFrom([
                    'email' => $adminEmail,
                    'name'  => 'Waitlist Notification'
                ])
                ->addTo($adminEmail)
                ->getTransport();

            $transport->sendMessage();
        } finally {
            $this->inlineTranslation->resume();
        }
    }

    public function sendCustomerEmail($data, $adminEmail)
    {
        $this->inlineTranslation->suspend();
        try {
            $transport = $this->transportBuilder
                ->setTemplateIdentifier(3)
                ->setTemplateOptions([
                    'area'  => Area::AREA_FRONTEND,
                    'store' => $this->storeManager->getStore()->getId()
                ])
                ->setTemplateVars([
                    'customer_name'     => $data['customer_name'],
                    'customer_email'    => $data['customer_email'],
                    'customer_phone'    => $data['customer_phone'],
                    'product_name'      => $data['product_name'],
                    'product_sku'       => $data['product_sku'],
                    'product_price'     => $data['product_price'],
                    'product_image_url' => $data['product_image_url']
                ])
                ->setFrom([
                    'email' => $adminEmail,
                    'name'  => 'Waitlist Notification'
                ])
                ->addTo($data['customer_email'], $data['customer_name'])
                ->getTransport();

            $transport->sendMessage();
        } finally {
            $this->inlineTranslation->resume();
        }
    }
}