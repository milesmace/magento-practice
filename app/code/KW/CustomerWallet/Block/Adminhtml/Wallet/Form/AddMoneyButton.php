<?php

namespace KW\CustomerWallet\Block\Adminhtml\Wallet\Form;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class AddMoneyButton implements ButtonProviderInterface
{

    public function __construct(
        private Registry $registry,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getButtonData()
    {
        return [
            'label' => __('Add Money'),
            'class' => 'action-secondary',
        ];
    }
}
