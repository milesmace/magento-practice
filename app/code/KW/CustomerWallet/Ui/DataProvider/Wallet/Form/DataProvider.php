<?php
namespace KW\CustomerWallet\Ui\DataProvider\Wallet\Form;

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
        if ($this->loadedData !== null) {
            return $this->loadedData;
        }

        $walletId = $this->request->getParam('wallet_id');
        if (!$walletId) {
            return [];
        }

        $wallet = $this->collection->addFieldToFilter('wallet_id', $walletId)->getFirstItem();
        if (!$wallet) {
            return [];
        }

        $this->loadedData[$wallet->getWalletId()] = $wallet->getData();


        return $this->loadedData;
    }
}
