<?php

namespace KW\CustomerWallet\Model;

use Magento\Framework\Model\AbstractModel;
use KW\CustomerWallet\Api\Data\WalletTopupInterface;

class WalletTopup extends AbstractModel implements WalletTopupInterface
{
    protected function _construct()
    {
        $this->_init(\KW\CustomerWallet\Model\ResourceModel\WalletTopup::class);
    }

    public function getTopupId()
    {
        return (int) $this->getData(self::TOPUP_ID);
    }

    public function setTopupId($topupId)
    {
        return $this->setData(self::TOPUP_ID, $topupId);
    }

    public function getWalletId()
    {
        return (int) $this->getData(self::WALLET_ID);
    }

    public function setWalletId($walletId)
    {
        return $this->setData(self::WALLET_ID, $walletId);
    }

    public function getAmount()
    {
        return (float) $this->getData(self::AMOUNT);
    }

    public function setAmount($amount)
    {
        return $this->setData(self::AMOUNT, $amount);
    }

    public function getNote()
    {
        return $this->getData(self::NOTE);
    }

    public function setNote($note)
    {
        return $this->setData(self::NOTE, $note);
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
