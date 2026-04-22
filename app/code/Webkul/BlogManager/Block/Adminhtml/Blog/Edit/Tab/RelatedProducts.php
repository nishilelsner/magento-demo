<?php
namespace Webkul\BlogManager\Block\Adminhtml\Blog\Edit\Tab;

class RelatedProducts extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * Dependency Initilization
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        array $data = []
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form data
     *
     * @return \Magento\Backend\Block\Widget\Form
     */
    protected function _prepareForm()
    {
       
        $model = $this->_coreRegistry->registry('blog_data');

        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('blogmanager_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Related Products'), 'class' => 'fieldset-wide']
        );

        $fieldset->addField(
            'products',
            'multiselect',
            [
                'name' => 'products',
                'label' => __('Products'),
                'values' => $this->getProductOptions(),
                'id' => 'products',
                'title' => __('Products'),
                'required' => false,
            ]
        );

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Get Product Options
     *
     * @return array
     */
    private function getProductOptions()
    {
        $collection = $this->productCollectionFactory->create()->addAttributeToSelect('name');
        $products = [];
        foreach ($collection as $model) {
            $products[] = ['value'=>$model->getId(), 'label'=>$model->getName()];
        }
        return $products;
    }
}