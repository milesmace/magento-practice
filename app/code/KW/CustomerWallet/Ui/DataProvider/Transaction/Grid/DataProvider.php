<?php

namespace KW\CustomerWallet\Ui\DataProvider\Transaction\Grid;

use KW\CustomerWallet\Model\ResourceModel\WalletTransaction\CollectionFactory as TransactionCollectionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    private int $walletId;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        private TransactionCollectionFactory $transactionCollectionFactory,
        private RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $this->transactionCollectionFactory->create();
        $this->collection->addFilterToMap('id', 'main_table.transaction_id');

        $this->walletId = (int)$this->request->getParam('wallet_id');
        if ($this->walletId) {
            $this->collection->addFieldToFilter('wallet_id', $this->walletId);
        }

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        $data = parent::getData();

        foreach ($data['items'] as &$item) {
            $item['id'] = $item['transaction_id'];
            $item['reference_order'] = $item['order_id'];
            $item['reference_wallet'] = $item['related_wallet_id'];
        }

        return $data;
    }
}
