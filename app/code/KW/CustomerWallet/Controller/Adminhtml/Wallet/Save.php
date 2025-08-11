<?php

namespace KW\CustomerWallet\Controller\Adminhtml\Wallet;

use KW\CustomerWallet\Api\WalletRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action
{

    private WalletRepositoryInterface $walletRepository;

    public function __construct(Action\Context $context, WalletRepositoryInterface $walletRepository)
    {
        parent::__construct($context);
        $this->walletRepository = $walletRepository;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        if (!$this->getRequest()->isPost()) {
            throw new LocalizedException(__('Request method is not allowed.'));
        }

        $data = $this->getRequest()->getPostValue();
        $wid = $this->getRequest()->getParam('wid');

        $wallet = $this->walletRepository->getById($wid);

        try {
            $wallet->setData($data);
            $wallet->save();
            $this->messageManager->addSuccessMessage(__('The wallet has been saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $this->_redirect('*/*/edit', ['wallet_id' => $wid]);
    }
}
