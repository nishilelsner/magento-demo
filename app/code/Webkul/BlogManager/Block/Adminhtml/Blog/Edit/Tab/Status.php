<?php
namespace Webkul\BlogManager\Block\Adminhtml\Blog\Edit\Tab;

class Status extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Webkul\BlogManager\Model\Blog\Status
     */
    protected $blogStatus;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerFactory;

    /**
     * Dependency Initilization
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Webkul\BlogManager\Model\Blog\Status $blogStatus
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Webkul\BlogManager\Model\Blog\Status $blogStatus,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        array $data = []
    ) {
        $this->blogStatus = $blogStatus;
        $this->customerFactory = $customerFactory;
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
            ['legend' => __('Edit Blog'), 'class' => 'fieldset-wide']
        );

        $fieldset->addField(
            'status',
            'select',
            [
                'name' => 'status',
                'label' => __('Status'),
                'options' => [0=>__('Disabled'), 1=>__('Enabled')],
                'id' => 'status',
                'title' => __('Status'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'user_id',
            'select',
            [
                'name' => 'user_id',
                'label' => __('Author'),
                'options' => $this->getAuthorOptions(),
                'id' => 'user_id',
                'title' => __('Author'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Provides Author options
     *
     * @return array
     */
    private function getAuthorOptions()
    {
        $collection = $this->customerFactory->create()->getCollection();
        $authors = [0=>'Admin'];
        foreach ($collection as $model) {
            $authors[$model->getId()] = $model->getName();
        }
        return $authors;
    }
}