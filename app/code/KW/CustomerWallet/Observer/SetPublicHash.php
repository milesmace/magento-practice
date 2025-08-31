<?php

namespace KW\CustomerWallet\Observer;

use KW\CustomerWallet\Api\Data\WalletInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Math\Random;

class SetPublicHash implements ObserverInterface
{

    public function __construct(
        private Random $random,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        /** @var WalletInterface $wallet */
        $wallet = $observer->getEvent()->getDataObject();
        $wallet->setPublicHash($this->random->getRandomString(32));
    }
}
