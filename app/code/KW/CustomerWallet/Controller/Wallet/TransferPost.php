<?php

namespace KW\CustomerWallet\Controller\Wallet;

use KW\CustomerWallet\Api\WalletRepositoryInterface;
use KW\CustomerWallet\Model\WalletService;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\Result\Redirect;

class TransferPost extends Action
{
    public function __construct(
        Context $context,
        private CurrentCustomer $currentCustomer,
        private WalletRepositoryInterface $walletRepository,
        private WalletService $walletService
    ) {
        parent::__construct($context);
    }

    /**
     * Execute wallet transfer
     */
    public function execute(): Redirect
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!$this->getRequest()->isPost()) {
            $this->messageManager->addErrorMessage(
                __('Request method is not allowed.')
            );
            return $resultRedirect->setPath('*/*/transfer');
        }

        $customerId = $this->currentCustomer->getCustomerId();

        try {
            $wallet = $this->walletRepository->getByCustomerId($customerId);
            if (!$wallet) {
                throw new LocalizedException(
                    __('No wallet associated with your account.')
                );
            }

            $data = $this->getRequest()->getPostValue();
            $amount = isset($data['amount']) ? (float)$data['amount'] : 0;
            $note = $data['note'] ?? '';
            $receiverHash = $data['receiver_public_hash'] ?? null;

            if (!$receiverHash) {
                throw new LocalizedException(
                    __('Receiver wallet public hash is required.')
                );
            }

            $receiverWallet = $this->walletRepository->getByPublicHash(
                $receiverHash
            );
            if (!$receiverWallet) {
                throw new LocalizedException(
                    __('No wallet found with the given public hash.')
                );
            }

            if (!$receiverWallet->getIsActive()) {
                throw new LocalizedException(
                    __('Receiver wallet is disabled.')
                );
            }

            if ($amount <= 0) {
                throw new LocalizedException(
                    __('Amount must be greater than zero.')
                );
            }

            // Perform the transfer
            $this->walletService->transfer(
                $wallet->getId(),
                $receiverWallet->getId(),
                $amount,
                $note
            );

            $this->messageManager->addSuccessMessage(
                __('Wallet transfer completed successfully.')
            );

            return $resultRedirect->setPath('customer/wallet/index');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            // Catch any unexpected exception
            $this->messageManager->addErrorMessage(
                __('Something went wrong during the transfer.')
            );
        }

        return $resultRedirect->setPath('*/*/transfer');
    }
}
