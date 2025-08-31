<?php

namespace KW\CustomerWallet\Observer;

use KW\CustomerWallet\Model\WalletTransaction;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Math\Random;
use Magento\Framework\Stdlib\DateTime;

class SetUniqueTransactionId implements ObserverInterface
{

    public function __construct(
        private DateTime $dateTime,
        private Random $random
    ) {
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        /** @var WalletTransaction $transaction */
        $transaction = $observer->getEvent()->getDataObject();

        if ($transaction->isObjectNew()) {
            $transaction->setTid('T' . $this->dateTime->gmDate('YmdHis', time()) . $this->random->getRandomString(8));
        }
    }
}
