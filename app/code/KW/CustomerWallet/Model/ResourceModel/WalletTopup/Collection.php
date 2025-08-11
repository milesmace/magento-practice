<?php

namespace KW\CustomerWallet\Model\ResourceModel\WalletTopup;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use KW\CustomerWallet\Model\WalletTopup as Model;
use KW\CustomerWallet\Model\ResourceModel\WalletTopup as ResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
