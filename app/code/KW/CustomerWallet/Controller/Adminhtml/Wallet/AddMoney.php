<?php

namespace KW\CustomerWallet\Controller\Adminhtml\Wallet;

use KW\CustomerWallet\Api\WalletRepositoryInterface;
use KW\CustomerWallet\Model\WalletService;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;

class AddMoney extends Action
{

    public function __construct(
        Action\Context $context,
        private WalletRepositoryInterface $walletRepository,
        private WalletService $walletService,
    ) {
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        if (!$this->getRequest()->isPost()) {
            throw new LocalizedException(__('Request method is not allowed.'));
        }

        $data = $this->getRequest()->getPostValue();
        $walletId = (int) $this->getRequest()->getParam('wallet_id');

        $wallet = $this->walletRepository->getById($walletId);
        if (!$wallet) {
            $this->messageManager->addErrorMessage(__('Wallet not found.'));
            return $this->_redirect('*/wallets/index');
        }

        $this->walletService->addFundsToWallet($walletId, $data['amount'], $data['note']);
        $this->messageManager->addSuccessMessage(__('Successfully added money to wallet.'));

        return $this->_redirect('*/*/edit', ['wallet_id' => $walletId]);
    }
}
