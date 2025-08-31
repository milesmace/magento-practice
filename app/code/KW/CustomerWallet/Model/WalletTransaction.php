<?php

namespace KW\CustomerWallet\Model;

use Magento\Framework\Model\AbstractModel;
use KW\CustomerWallet\Api\Data\WalletTransactionInterface;

class WalletTransaction extends AbstractModel implements WalletTransactionInterface
{
    protected $_idFieldName = 'transaction_id';
    protected $_eventPrefix = 'wallet_transaction';
    protected $_eventObject = 'wallet_transaction';
    protected $_cacheTag = 'wallet_transaction';

    protected function _construct()
    {
        $this->_init(\KW\CustomerWallet\Model\ResourceModel\WalletTransaction::class);
    }

    public function getTransactionId()
    {
        return (int) $this->getData(self::TRANSACTION_ID);
    }

    public function setTransactionId($transactionId): WalletTransactionInterface
    {
        $this->setData(self::TRANSACTION_ID, $transactionId);
        return $this;
    }

    public function getWalletId()
    {
        return (int) $this->getData(self::WALLET_ID);
    }

    public function setWalletId($walletId): WalletTransactionInterface
    {
        $this->setData(self::WALLET_ID, $walletId);
        return $this;
    }

    public function getTid()
    {
        return $this->getData(self::TID);
    }

    public function setTid($tid): WalletTransactionInterface
    {
        $this->setData(self::TID, $tid);
        return $this;
    }

    public function getAmount()
    {
        return (float) $this->getData(self::AMOUNT);
    }

    public function setAmount($amount): WalletTransactionInterface
    {
        $this->setData(self::AMOUNT, $amount);
        return $this;
    }

    public function getRelatedWalletId()
    {
        return $this->getData(self::RELATED_WALLET_ID);
    }

    public function setRelatedWalletId($relatedWalletId): WalletTransactionInterface
    {
        $this->setData(self::RELATED_WALLET_ID, $relatedWalletId);
        return $this;
    }

    public function getOrderId()
    {
        return $this->getData(self::ORDER_ID);
    }

    public function setOrderId($orderId): WalletTransactionInterface
    {
        $this->setData(self::ORDER_ID, $orderId);
        return $this;
    }

    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    public function setDescription($description): WalletTransactionInterface
    {
        $this->setData(self::DESCRIPTION, $description);
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    public function setCreatedAt($createdAt): WalletTransactionInterface
    {
        $this->setData(self::CREATED_AT, $createdAt);
        return $this;
    }
}
