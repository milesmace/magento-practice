<?php

namespace KW\CustomerWallet\Ui\Component\Listing\Filters;

use Magento\Framework\Data\OptionSourceInterface;

class WalletActiveStateOptions implements OptionSourceInterface
{

    const ENABLED = 1;
    const DISABLED = 0;

    public function toOptionArray()
    {
        return [
            ['value' => self::ENABLED, 'label' => __('Enabled')],
            ['value' => self::DISABLED, 'label' => __('Disabled')],
        ];
    }
}
