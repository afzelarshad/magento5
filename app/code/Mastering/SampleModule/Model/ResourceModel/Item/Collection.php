<?php
namespace Mastering\SampleModule\Model\ResourceModel\Item;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'mastering_sample_item_collection';
    protected $_eventObject = 'item_collection';

    /**
     * Define the resource model & the model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Mastering\SampleModule\Model\Item::class', 'Mastering\SampleModule\Model\ResourceModel\Item::class');
    }
}
