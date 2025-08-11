<?php
namespace KW\CustomerWallet\Api\Data;

interface WalletTransactionInterface
{
    /**#@+
     * Constants for keys
     */
    const TRANSACTION_ID     = 'transaction_id';
    const WALLET_ID          = 'wallet_id';
    const TYPE               = 'type';
    const AMOUNT             = 'amount';
    const RELATED_WALLET_ID  = 'related_wallet_id';
    const ORDER_ID           = 'order_id';
    const DESCRIPTION        = 'description';
    const CREATED_AT         = 'created_at';
    /**#@-*/

    public function getTransactionId();
    public function setTransactionId($transactionId);

    public function getWalletId();
    public function setWalletId($walletId);

    public function getType();
    public function setType($type);

    public function getAmount();
    public function setAmount($amount);

    public function getRelatedWalletId();
    public function setRelatedWalletId($relatedWalletId);

    public function getOrderId();
    public function setOrderId($orderId);

    public function getDescription();
    public function setDescription($description);

    public function getCreatedAt();
    public function setCreatedAt($createdAt);
}
