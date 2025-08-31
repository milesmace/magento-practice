<?php

namespace KW\CustomerWallet\Controller\Wallet;

use KW\CustomerWallet\Api\WalletRepositoryInterface;
use KW\CustomerWallet\Model\WalletService;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;

class TransferPost extends Action
{
    public function __construct(
        Context $context,
        private CurrentCustomer $currentCustomer,
        private WalletRepositoryInterface $walletRepository,
        private WalletService $walletService,
    ) {
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        if (!$this->getRequest()->isPost()) {
            throw new LocalizedException(__('Request method is not allowed.'));
        }

        $customerId = $this->currentCustomer->getCustomerId();
        $wallet = $this->walletRepository->getByCustomerId($customerId);

        if (!$wallet) {
            $this->messageManager->addErrorMessage(__('No wallet associated with the current user.'));
            return $this->_redirect('customer/account/index');
        }

        $data = $this->getRequest()->getPostValue();

        $receiverWallet = $this->walletRepository->getByPublicHash($data['receiver_public_hash']);
        if (!$receiverWallet) {
            $this->messageManager->addErrorMessage(__('No wallet found with the given public hash.'));
        }

        try {
            $this->walletService->transfer(
                $wallet->getId(),
                $receiverWallet->getId(),
                (float)$data['amount'],
                $data['note']
            );
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $this->_redirect('*/*/transfer');
        }

        $this->messageManager->addSuccessMessage(__('Wallet money transfer complete.'));
        return $this->_redirect('customer/wallet/index');
    }
}
