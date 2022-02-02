<?php
namespace Techone\AjaxTutorial\Controller\Result;

use \Magento\Framework\App\Action\Action;
use \Magento\Framework\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
       \Magento\Framework\App\Action\Context $context,
       \Magento\Framework\View\Result\PageFactory $pageFactory, JsonFactory $resultJsonFactory
    )
    {
        $this->_pageFactory = $pageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        return parent::__construct($context);
    }
    /**
     * View page action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $numone = $this->getRequest()->getParam('numone');
        $numtwo = $this->getRequest()->getParam('numtwo');
        $result = $this->resultJsonFactory->create();
        $resultPage = $this->resultPageFactory->create();

        $block = $resultPage->getLayout()
                ->createBlock('Techone\Ajaxtutorial\Block\Index')
                ->setTemplate('Techone_Ajaxtutorial::result.phtml')
                ->setData('numone',$numone)
                ->setData('numtwo',$numtwo)
                ->toHtml();
        

        $result->setData(['output' => $block]);
        return $result;
    }
}
