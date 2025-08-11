<?php

namespace KW\CustomerWallet\Model;

use KW\CustomerWallet\Api\Data\WalletInterface;
use KW\CustomerWallet\Api\WalletRepositoryInterface;
use KW\CustomerWallet\Model\WalletFactory as WalletFactory;
use KW\CustomerWallet\Model\ResourceModel\Wallet as WalletResource;
use KW\CustomerWallet\Model\ResourceModel\WalletTransaction\CollectionFactory as WalletTransactionCollectionFactory;

class WalletRepository implements WalletRepositoryInterface
{

    public function __construct(
        private WalletResource $walletResource,
        private WalletFactory $walletFactory,
        private WalletTransactionCollectionFactory $walletTransactionCollectionFactory,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getById($walletId): ?Wallet
    {
        $wallet = $this->walletFactory->create();
        $this->walletResource->load($wallet, $walletId, 'wallet_id');

        return $wallet->getWalletId() ? $wallet : null;
    }
}
