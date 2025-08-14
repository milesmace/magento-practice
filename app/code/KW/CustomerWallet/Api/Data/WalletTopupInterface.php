<?php
namespace KW\CustomerWallet\Api\Data;

interface WalletTopupInterface
{
    /**#@+
     * Constants for keys
     */
    const WALLET_ID  = 'wallet_id';
    const AMOUNT     = 'amount';
    const NOTE       = 'note';
    const CREATED_AT = 'created_at';
    /**#@-*/

    public function getWalletId();
    public function setWalletId($walletId);

    public function getAmount();
    public function setAmount($amount);

    public function getNote();
    public function setNote($note);

    public function getCreatedAt();
    public function setCreatedAt($createdAt);
}
