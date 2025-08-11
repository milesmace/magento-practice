<?php

namespace KW\CustomerWallet\Block\Adminhtml\Wallet\Form;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton implements ButtonProviderInterface
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
            'label' => __('Save'),
            'class' => 'save primary',
        ];
    }
}
