<?php

namespace KW\CustomerWallet\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class WalletTransaction extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('wallet_transactions', 'transaction_id');
    }
}
