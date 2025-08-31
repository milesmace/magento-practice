<?php

namespace KW\CustomerWallet\Block\Account\Wallet;

use KW\CustomerWallet\Api\Data\WalletInterface;
use KW\CustomerWallet\Api\WalletRepositoryInterface;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;

class WalletInfo extends Template
{

    private WalletInterface|null $_wallet = null;

    public function __construct(
        Template\Context $context,
        private CurrentCustomer $currentCustomer,
        private PriceHelper $priceHelper,
        private WalletRepositoryInterface $walletRepository,
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

    public function getTransactions(): array
    {
        $walletId = $this->getWallet()->getId();

        $transactions = $this->getWallet()->getTransactions();
        foreach ($transactions as $transaction) {
            $transaction['type'] = $transaction->getWalletId() == $walletId ? __('Debit') : __('Credit');
        }

        return $transactions;
    }

    public function getEmptyTransactionsMessage(): string
    {
        return __('You have no Wallet Transactions.');
    }

    public function getEmptyTopupsMessage(): string
    {
        return __('You have no Wallet Topups.');
    }

    public function getTotalOrders(): int
    {
        return 0;
    }

    public function getPriceHelper(): PriceHelper
    {
        return $this->priceHelper;
    }
}
