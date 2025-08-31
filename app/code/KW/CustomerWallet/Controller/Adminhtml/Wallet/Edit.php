<?php

namespace KW\CustomerWallet\Controller\Adminhtml\Wallet;

use KW\CustomerWallet\Api\WalletRepositoryInterface;
use KW\CustomerWallet\Controller\Adminhtml\BaseController;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Result\PageFactory;

class Edit extends BaseController
{
    public function __construct(
        private Context $context,
        private PageFactory $resultPageFactory,
        private WalletRepositoryInterface $walletRepository,
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $walletId = (int) $this->getRequest()->getParam('wallet_id');

        if (!$walletId) {
            throw new LocalizedException(__('Provide a valid wallet ID.'));
        }

        $wallet = $this->walletRepository->getById($walletId);

        if (!$wallet || !$wallet->getId()) {
            throw new LocalizedException(__('Wallet not found.'));
        }

        $customer = $wallet->getCustomer();
        $customerName = $customer ? $customer->getFirstname() . ' ' . $customer->getLastname() : __('Unknown Customer');

        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magento_Customer::customer');
        $resultPage->getConfig()->getTitle()->prepend(__("%1's Wallet", $customerName));

        return $resultPage;
    }
}
