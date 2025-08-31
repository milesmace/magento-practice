<?php
namespace KW\CustomerWallet\Api\Data;

interface WalletTransactionInterface
{
    /**#@+
     * Constants for keys
     */
    const TRANSACTION_ID     = 'transaction_id';
    const WALLET_ID          = 'wallet_id';
    const TID               = 'tid';
    const AMOUNT             = 'amount';
    const RELATED_WALLET_ID  = 'related_wallet_id';
    const ORDER_ID           = 'order_id';
    const DESCRIPTION        = 'description';
    const CREATED_AT         = 'created_at';
    /**#@-*/

    public function getTransactionId();
    public function setTransactionId($transactionId): WalletTransactionInterface;

    public function getWalletId();
    public function setWalletId($walletId): WalletTransactionInterface;

    public function getTid();
    public function setTid($tid): WalletTransactionInterface;

    public function getAmount();
    public function setAmount($amount): WalletTransactionInterface;

    public function getRelatedWalletId();
    public function setRelatedWalletId($relatedWalletId): WalletTransactionInterface;

    public function getOrderId();
    public function setOrderId($orderId): WalletTransactionInterface;

    public function getDescription();
    public function setDescription($description): WalletTransactionInterface;

    public function getCreatedAt();
    public function setCreatedAt($createdAt): WalletTransactionInterface;
}
