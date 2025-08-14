<?php

namespace KW\CustomerWallet\Block\Adminhtml\Wallet\Form;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton implements ButtonProviderInterface
{

    private $urlBuilder;

    public function __construct(
        private Context $context,
    ) {
        $this->urlBuilder = $this->context->getUrlBuilder();
    }

    /**
     * @inheritDoc
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save Wallet'),
            'class' => 'save primary',
            'id' => 'customer-wallet-save-button',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [[
                            'targetName' => 'customer_wallet_form.customer_wallet_form_data_source',
                            'actionName' => 'save',
                            'params' => [true]
                        ]]
                    ]
                ]
            ],
        ];
    }

    public function getSaveUrl()
    {
        return $this->urlBuilder->getUrl('/*/*/save');
    }
}
