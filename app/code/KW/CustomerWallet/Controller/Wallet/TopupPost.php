<?php

namespace KW\CustomerWallet\Controller\Wallet;

use KW\CustomerWallet\Api\WalletRepositoryInterface;
use KW\CustomerWallet\Model\WalletService;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;

class TopupPost extends Action
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

        $amount = (float) $data['amount'];
        $note = $data['note'];
        if ($amount <= 0) {
            $this->messageManager->addErrorMessage(__('Amount must be greater than zero.'));
            return $this->_redirect('*/*/transfer');
        }

        try {
            $this->walletService->addFundsToWallet($wallet->getId(), $amount, $note);
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $this->_redirect('*/*/topup');
        }

        $this->messageManager->addSuccessMessage(__('%1 added to your wallet', $amount));
        return $this->_redirect('customer/wallet/index');
    }
}
