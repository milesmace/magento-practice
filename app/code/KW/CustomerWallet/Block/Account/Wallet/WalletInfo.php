<?php

namespace KW\CustomerWallet\Block\Account\Wallet;

use KW\CustomerWallet\Api\Data\WalletInterface;
use KW\CustomerWallet\Api\WalletRepositoryInterface;
use KW\CustomerWallet\Model\WalletService;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;

class WalletInfo extends Template
{
    public const string TRANSACTION_TYPE_CREDIT = 'credit';
    public const string TRANSACTION_TYPE_DEBIT = 'debit';

    private WalletInterface|null $_wallet = null;

    public function __construct(
        Template\Context $context,
        private CurrentCustomer $currentCustomer,
        private PriceHelper $priceHelper,
        private WalletRepositoryInterface $walletRepository,
        private WalletService $walletService,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function getWallet(): WalletInterface
    {
        if ($this->_wallet && $this->_wallet->getId()) {
            return $this->_wallet;
        }

        $wallet = $this->walletRepository->getByCustomerId($this->currentCustomer->getCustomerId());
        $this->_wallet = $wallet;

        return $this->_wallet;
    }

    public function getWalletBalance(): string
    {
        $wallet = $this->getWallet();
        if ($wallet->getIsActive()) {
            return $this->priceHelper->currency($wallet->getBalance());
        }

        return 'Nan';
    }

    public function getTransferLink(): string
    {
        return $this->getUrl('customer/wallet/transfer');
    }

    public function getAddMoneyLink(): string
    {
        return $this->getUrl('customer/wallet/topup');
    }

    public function getWalletTotalOrders(): string
    {
        $wallet = $this->getWallet();
        if ($wallet->getIsActive()) {
            return 0;
        }

        return 'Nan';
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getTransactions(): array
    {
        $walletId = $this->getWallet()->getId();

        $transactions = $this->walletService->getWalletTransactions($walletId);
        foreach ($transactions as $transaction) {
            $transaction['type'] = $transaction->getWalletId() == $walletId
                ? self::TRANSACTION_TYPE_DEBIT
                : self::TRANSACTION_TYPE_CREDIT;

            if ($transaction->getRelatedWalletId() == $walletId) {
                $wallet = $this->walletRepository->getById($transaction->getWalletId());
            } else {
                $wallet = $this->walletRepository->getById($transaction->getRelatedWalletId());
            }
            $transaction['related_wallet_id'] = $wallet->getPublicHash();
        }

        return $transactions->getItems();
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getTopups(): array
    {
        $walletId = $this->getWallet()->getId();

        $topups = $this->walletService->getWalletTopups($walletId);

        return $topups->getItems();
    }

    public function getEmptyTransactionsMessage(): string
    {
        return __('You have no Wallet Transactions.');
    }

    public function getEmptyTopupsMessage(): string
    {
        return __('You have no Wallet Topups.');
    }

    public function getPriceHelper(): PriceHelper
    {
        return $this->priceHelper;
    }
}
