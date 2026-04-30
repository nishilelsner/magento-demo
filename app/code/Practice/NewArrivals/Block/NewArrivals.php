<?php
namespace Practice\NewArrivals\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Practice\NewArrivals\Model\ResourceModel\NewArrival\CollectionFactory as CustomCollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Catalog\Helper\Image as ImageHelper;

class NewArrivals extends Template
{
    protected $customCollectionFactory;
    protected $productCollectionFactory;
    protected $imageHelper;

    public function __construct(
        Context $context,
        CustomCollectionFactory $customCollectionFactory,
        ProductCollectionFactory $productCollectionFactory,
        ImageHelper $imageHelper,
        array $data = []
    ) {
        $this->customCollectionFactory = $customCollectionFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->imageHelper = $imageHelper;
        parent::__construct($context, $data);
    }

    public function getProducts()
    {
        // 1. Get all active items from our custom database table
        $customCollection = $this->customCollectionFactory->create()
            ->addFieldToFilter('is_active', 1)
            ->setOrder('sort_order', 'ASC'); // Order them exactly how you set it in the admin!

        // Collect all the Product IDs you entered in the admin
        $productIds = [];
        foreach ($customCollection as $item) {
            $productIds[] = $item->getProductId();
        }

        // If the table is empty, return an empty array
        if (empty($productIds)) {
            return [];
        }

        // 2. Ask Magento for the REAL products that match those IDs
        $productCollection = $this->productCollectionFactory->create()
            ->addAttributeToSelect(['name', 'price', 'thumbnail', 'url_key'])
            ->addFieldToFilter('entity_id', ['in' => $productIds]);

        // 3. Format them into the array that our existing .phtml template expects!
        $formattedProducts = [];
        foreach ($productCollection as $product) {
            $formattedProducts[] = [
                'name' => $product->getName(),
                'product_id' => $product->getId(),
                'add_to_cart_url' => $this->getUrl('checkout/cart/add', ['product' => $product->getId(), '_secure' => true]),
                'price' => (float) $product->getPrice(),
                'special_price' => $product->getSpecialPrice() ? (float) $product->getSpecialPrice() : null,
                'image' => $this->imageHelper->init($product, 'product_thumbnail_image')->getUrl(),
                'product_url' => $product->getProductUrl(),
                'rating' => 5, // We will hardcode rating for now to keep it simple
                'review_count' => rand(1, 10), // Random review count for visual design
                'is_new' => true
            ];
        }

        return $formattedProducts;
    }
}
