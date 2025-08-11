<?php

namespace KW\CustomerWallet\Model\ResourceModel\WalletTransaction;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use KW\CustomerWallet\Model\WalletTransaction as Model;
use KW\CustomerWallet\Model\ResourceModel\WalletTransaction as ResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
