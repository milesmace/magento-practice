<?php

namespace KW\CustomerWallet\Ui\DataProvider;

use KW\CustomerWallet\Api\Data\WalletInterface;
use KW\CustomerWallet\Model\ResourceModel\Wallet\CollectionFactory as WalletCollectionFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Ui\DataProvider\AbstractDataProvider;

class WalletDataProvider extends AbstractDataProvider
{
    private CustomerRepositoryInterface $customerRepository;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CustomerRepositoryInterface $customerRepository,
        WalletCollectionFactory $walletCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->customerRepository = $customerRepository;
        $this->collection = $walletCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function getData()
    {
        if (!$this->getCollection()->isLoaded()) {
            $this->getCollection()->load();
        }

        $result = [];

        /** @var WalletInterface $wallet */
        foreach ($this->getCollection()->getItems() as $wallet) {
            $customer = $this->customerRepository->getById($wallet->getCustomerId());
            $customerName = $customer->getfirstname() . ' ' . $customer->getlastname();

            $result[] = [
                'wallet_id' => $wallet->getWalletId(),
                'customer_name' => $customerName,
                'balance' => $wallet->getBalance(),
            ];
        }

        return [
            'totalRecords' => count($result),
            'items' => $result,
        ];
    }
}
