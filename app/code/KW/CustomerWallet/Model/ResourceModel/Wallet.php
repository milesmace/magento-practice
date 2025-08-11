<?php

namespace KW\CustomerWallet\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Wallet extends AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('customer_wallet', 'entity_id');
    }
}
