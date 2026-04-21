<?php
namespace Webkul\BlogManager\Model\Blog;

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{
    /**
     * Converts to option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        $options[] = ['label' => 'Disabled', 'value' => 0];
        $options[] = ['label' => 'Enabled', 'value' => 1];
        return $options;
    }
}