<?php
namespace Webkul\BlogManager\Block\Adminhtml\Blog\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * Prepare form data
     *
     * @return \Magento\Backend\Block\Widget\Form
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('blog_data');

        $form = $this->_formFactory->create(
            ['data' => [
                            'id' => 'edit_form',
                            'enctype' => 'multipart/form-data',
                            'action' => $this->getData('action'),
                            'method' => 'post'
                        ]
            ]
        );

        $form->setHtmlIdPrefix('blogmanager_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Blog Data'), 'class' => 'fieldset-wide']
        );

        $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);

        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'label' => __('Title'),
                'id' => 'title',
                'title' => __('Title'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'content',
            'textarea',
            [
                'name' => 'content',
                'label' => __('Content'),
                'id' => 'content',
                'title' => __('Content'),
                'class' => 'required-entry',
                'required' => true,
            ]
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
        
        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}