<?php

namespace KW\CustomerWallet\Controller\Adminhtml\Wallet;

use KW\CustomerWallet\Api\Data\WalletInterfaceFactory;
use KW\CustomerWallet\Api\WalletRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;

class AddMoney extends Action
{
    private WalletRepositoryInterface $walletRepository;
    private WalletInterfaceFactory $walletFactory;

    public function __construct(
        Action\Context $context,
        WalletRepositoryInterface $walletRepository,
        WalletInterfaceFactory $walletFactory
    ) {
        parent::__construct($context);
        $this->walletRepository = $walletRepository;
        $this->walletFactory = $walletFactory;
    }

    public function execute()
    {
        if (!$this->getRequest()->isPost()) {
            throw new LocalizedException(__('Request method is not allowed.'));
        }

        $data = $this->getRequest()->getPostValue();
        $walletId = (int) $this->getRequest()->getParam('wallet_id');

        try {
            if ($walletId) {
                $wallet = $this->walletRepository->getById($walletId);
            } else {
                $wallet = $this->walletFactory->create();
            }

            $wallet->addMoney($data['amount'], '');
            $this->messageManager->addSuccessMessage(__('Successfully added money to wallet.'));

        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error: %1', $e->getMessage()));
        }

        return $this->_redirect('*/*/edit', ['wallet_id' => $walletId]);
    }
}
