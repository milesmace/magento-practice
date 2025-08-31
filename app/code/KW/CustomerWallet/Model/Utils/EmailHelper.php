<?php

namespace KW\CustomerWallet\Model\Utils;

use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;

class EmailHelper
{

    public function __construct(
        private StoreManagerInterface $storeManager,
        private TransportBuilder $transportBuilder,
    ) {
    }
}
