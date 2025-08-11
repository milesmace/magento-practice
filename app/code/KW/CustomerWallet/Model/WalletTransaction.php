<?php
namespace KW\CustomerWallet\Model;

use Magento\Framework\Model\AbstractModel;
use KW\CustomerWallet\Api\Data\WalletTransactionInterface;

class WalletTransaction extends AbstractModel implements WalletTransactionInterface
{
    protected function _construct()
    {
        $this->_init(\KW\CustomerWallet\Model\ResourceModel\WalletTransaction::class);
    }

    public function getTransactionId()
    {
        return (int) $this->getData(self::TRANSACTION_ID);
    }

    public function setTransactionId($transactionId)
    {
        return $this->setData(self::TRANSACTION_ID, $transactionId);
    }

    public function getWalletId()
    {
        return (int) $this->getData(self::WALLET_ID);
    }

    public function setWalletId($walletId)
    {
        return $this->setData(self::WALLET_ID, $walletId);
    }

    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    public function getAmount()
    {
        return (float) $this->getData(self::AMOUNT);
    }

    public function setAmount($amount)
    {
        return $this->setData(self::AMOUNT, $amount);
    }

    public function getRelatedWalletId()
    {
        return $this->getData(self::RELATED_WALLET_ID);
    }

    public function setRelatedWalletId($relatedWalletId)
    {
        return $this->setData(self::RELATED_WALLET_ID, $relatedWalletId);
    }

    public function getOrderId()
    {
        return $this->getData(self::ORDER_ID);
    }

    public function setOrderId($orderId)
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }
}
