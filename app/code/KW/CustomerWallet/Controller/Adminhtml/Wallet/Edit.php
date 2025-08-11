<?php

namespace KW\CustomerWallet\Controller\Adminhtml\Wallet;

use KW\CustomerWallet\Api\WalletRepositoryInterface;
use KW\CustomerWallet\Controller\Adminhtml\RegistryConstants;
use Magento\Backend\App\Action;
use Magento\Customer\Controller\RegistryConstants as CustomerRegistryConstants;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action
{
    protected $resultPageFactory;
    protected $walletRepository;
    protected $coreRegistry;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        WalletRepositoryInterface $walletRepository,
        Registry $coreRegistry
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->walletRepository = $walletRepository;
        $this->coreRegistry = $coreRegistry;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('KW_CustomerWallet::wallet');
    }

    public function execute()
    {
        $walletId = (int) $this->getRequest()->getParam('wallet_id');

        if (!$walletId) {
            throw new LocalizedException(__('Provide a valid wallet ID.'));
        }

        $wallet = $this->walletRepository->getById($walletId);

        if (!$wallet || !$wallet->getWalletId()) {
            throw new LocalizedException(__('Wallet not found.'));
        }

        $customer = $wallet->getCustomer();
        $customerName = $customer ? $customer->getFirstname() . ' ' . $customer->getLastname() : __('Unknown Customer');
        $customerId = $customer->getId();

        // Store wallet in registry for UI component/form
        $this->coreRegistry->register(RegistryConstants::CURRENT_WALLET, $wallet);
        $this->coreRegistry->register(CustomerRegistryConstants::CURRENT_CUSTOMER_ID, $customerId);

        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__("%1's Wallet", $customerName));

        return $resultPage;
    }
}
