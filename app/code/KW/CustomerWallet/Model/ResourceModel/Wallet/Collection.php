<?php

namespace KW\CustomerWallet\Model\ResourceModel\Wallet;

use KW\CustomerWallet\Model\Wallet as Model;
use KW\CustomerWallet\Model\ResourceModel\Wallet as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
