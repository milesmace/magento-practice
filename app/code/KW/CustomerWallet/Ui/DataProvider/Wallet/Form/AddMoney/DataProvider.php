<?php

namespace KW\CustomerWallet\Ui\DataProvider\Wallet\Form\AddMoney;

use KW\CustomerWallet\Model\ResourceModel\Wallet\CollectionFactory as WalletCollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\RequestInterface;

class DataProvider extends AbstractDataProvider
{
    protected $request;
    protected $loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        WalletCollectionFactory $collectionFactory,
        RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->request = $request;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (!empty($this->loadedData)) {
            return $this->loadedData;
        }

        $walletId = (int) $this->request->getParam('wallet_id');
        if ($walletId) {
            $wallet = $this->collection->getItemById($walletId);
            if ($wallet) {
                $this->loadedData[$walletId] = $wallet->getData();
                $this->loadedData[$walletId]['amount'] = 0;
            }
        }

        return $this->loadedData;
    }
}
