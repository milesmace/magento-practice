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

    /**
     * @param int $customerId
     * @return Wallet|null
     */
    public function getByCustomerId(int $customerId): ?Wallet;


    /**
     * @param string $publicHash
     * @return Wallet|null
     */
    public function getByPublicHash(string $publicHash): ?Wallet;
}
