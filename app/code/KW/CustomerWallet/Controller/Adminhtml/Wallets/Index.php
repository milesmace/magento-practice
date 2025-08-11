<?php

namespace KW\CustomerWallet\Controller\Adminhtml\Wallets;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $resultPageFactory;

    public const ADMIN_RESOURCE = 'KW_CustomerWallet::wallet_list';

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
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
