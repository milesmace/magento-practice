<?php

namespace KW\CustomerWallet\Api;

use KW\CustomerWallet\Model\Wallet;

interface WalletRepositoryInterface
{

    /**
     * @param $walletId
     * @return Wallet | null
     */
    public function getById($walletId): ?Wallet;
}
