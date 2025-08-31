<?php

namespace KW\CustomerWallet\Controller\Adminhtml\Wallets;

use KW\CustomerWallet\Controller\Adminhtml\BaseController;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends BaseController
{
    public function __construct(
        Context $context,
        private PageFactory $resultPageFactory,
    ) {
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magento_Customer::customer');
        $resultPage->getConfig()->getTitle()->prepend(__('Wallets'));
        return $resultPage;
    }
}
