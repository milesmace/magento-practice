<?php

namespace KW\CustomerWallet\Block\Adminhtml\Wallet\Form;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class AddMoneyButton implements ButtonProviderInterface
{

    public function __construct(
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
            'data_attribute' => [
                'mage-init' => [
                    'Magento_Ui/js/form/button-adapter' => [
                        'actions' => [
                            [
                                'targetName' => 'customer_wallet_form.customer_wallet_form.wallet_add_money_modal',
                                'actionName' => 'toggleModal'
                            ]
                        ]
                    ]
                ]
            ],
            'on_click' => '',
            'sort_order' => 10
        ];
        return [
            'label' => __('Add Money'),
            'class' => 'add action-secondary',
            'data_attribute' => [
                'mage-init' => [
                    'Magento_Ui/js/form/button-adapter' => [
                        'actions' => [[
                            'targetName' => 'customer_wallet_form.customer_wallet_form.wallet_add_money_modal',
                            'actionName' => 'openModal'
                        ]]
                    ]
                ]
            ],
            'on_click' => '',
        ];
    }
}
