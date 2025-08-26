<?php

namespace KW\CustomerWallet\Ui\DataProvider\Topup\Grid;

use KW\CustomerWallet\Api\Data\WalletTopupInterface;
use KW\CustomerWallet\Model\ResourceModel\WalletTopup\CollectionFactory as TopupCollectionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    private int $walletId;
    private array $loadedData = [];

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        private TopupCollectionFactory $topupCollectionFactory,
        private RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $this->topupCollectionFactory->create();
        $this->collection->addFilterToMap('id', 'main_table.topup_id');

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
            $item['id'] = $item['topup_id'];
        }

        return $data;
    }
}
