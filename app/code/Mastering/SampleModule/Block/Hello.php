<?php
namespace Mastering\SampleModule\Block;

use Magento\SampleModule\Model\ResourceModel\Item\Collection;
use MAgento\FrameWork\View\Element\Template;
use Mastering\SampleModule\Model\ResourceModel\Item\CollectionFactory;


class Hello extends \Magento\Framework\View\Element\Template
{
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    private $collectionFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory; 
        parent::__construct($context, $data);
    }
    public function getItems()
    {
        return $this->collectionFactory->create()->getItems();
    }
}
