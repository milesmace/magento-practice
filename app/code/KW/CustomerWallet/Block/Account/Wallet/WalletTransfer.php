<?php

namespace KW\CustomerWallet\Block\Account\Wallet;

use Magento\Framework\View\Element\Template;

class WalletTransfer extends Template
{

    public function getPostActionUrl(): string
    {
        return $this->getUrl('customer/wallet/transferpost');
    }
}
