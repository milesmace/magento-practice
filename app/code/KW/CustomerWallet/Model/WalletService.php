<?php

namespace KW\CustomerWallet\Model;

use KW\CustomerWallet\Api\Data\WalletInterface;
use KW\CustomerWallet\Api\Data\WalletTransactionInterface;
use KW\CustomerWallet\Api\WalletRepositoryInterface;
use KW\CustomerWallet\Model\ResourceModel\WalletTopup\Collection as WalletTopupCollection;
use KW\CustomerWallet\Model\ResourceModel\WalletTopup\CollectionFactory as WalletTopupCollectionFactory;
use KW\CustomerWallet\Model\ResourceModel\WalletTransaction\Collection as WalletTransactionCollection;
use KW\CustomerWallet\Model\ResourceModel\WalletTransaction\CollectionFactory as WalletTransactionCollectionFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class WalletService
{
    public const XML_PATH_WELCOME_CREDIT_ENABLED = 'wallet/wallet/welcome_credit_enabled';
    public const XML_PATH_WELCOME_CREDIT_AMOUNT = 'wallet/wallet/welcome_credit_amount';

    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
        private ScopeConfigInterface $scopeConfig,
        private StoreManagerInterface $storeManager,
        private TransportBuilder $transportBuilder,
        private WalletFactory $walletFactory,
        private WalletRepositoryInterface $walletRepository,
        private WalletTransactionCollectionFactory $walletTransactionCollectionFactory,
        private WalletTopupCollectionFactory $walletTopupCollectionFactory,
        private WalletTransactionFactory $walletTransactionFactory,
        private WalletTopupFactory $walletTopupFactory,
    ) {
    }

    /**
     * @param $customerId
     * @return void
     * @throws LocalizedException
     * @throws MailException
     * @throws NoSuchEntityException
     */
    private function sendWalletSetupCompleteEmail($customerId): void
    {
        $storeId = $this->storeManager->getStore()->getStoreId();

        $customer = $this->customerRepository->getById($customerId);
        $customerName = $customer->getFirstname() . ' ' . $customer->getLastname();

        $this->transportBuilder
            ->setTemplateIdentifier('wallet_setup_complete')
            ->setTemplateOptions(['area' => 'frontend', 'store' => $storeId])
            ->setTemplateVars([ 'customer_name' => $customerName ])
            ->setFromByScope('general', $storeId)
            ->addTo($customer->getEmail(), $customerName)
            ->getTransport()
            ->sendMessage();
    }

    /**
     * @param $customerId
     * @return void
     * @throws LocalizedException
     * @throws MailException
     * @throws NoSuchEntityException
     */
    private function sendWalletWelcomeCreditEmail($customerId): void
    {
        $storeId = $this->storeManager->getStore()->getStoreId();
        $welcomeCredit = $this->scopeConfig->getValue(
            self::XML_PATH_WELCOME_CREDIT_AMOUNT,
            ScopeInterface::SCOPE_WEBSITE
        );

        $customer = $this->customerRepository->getById($customerId);
        $customerName = $customer->getFirstname() . ' ' . $customer->getLastname();

        $this->transportBuilder
            ->setTemplateIdentifier('wallet_welcome_credit')
            ->setTemplateOptions(['area' => 'frontend', 'store' => $storeId])
            ->setTemplateVars(
                [
                    'customer_name' => $customerName,
                    'credit_amount' => $welcomeCredit,
                ]
            )
            ->setFromByScope('general', $storeId)
            ->addTo($customer->getEmail(), $customerName)
            ->getTransport()
            ->sendMessage();
    }

    /**
     * @param int $walletId
     * @return WalletTransactionCollection
     * @throws LocalizedException
     */
    public function getWalletTransactions(int $walletId): WalletTransactionCollection
    {
        $wallet = $this->walletRepository->getById($walletId);
        if (!$wallet) {
            throw new LocalizedException(__('Wallet not found.'));
        }

        $collection = $this->walletTransactionCollectionFactory->create();

        $collection->addFieldToFilter(
            ['wallet_id', WalletTransactionInterface::RELATED_WALLET_ID],
            [$walletId, $walletId]
        );

        return $collection;
    }

    /**
     * @param int $walletId
     * @return WalletTopupCollection
     * @throws LocalizedException
     */
    public function getWalletTopups(int $walletId): WalletTopupCollection
    {
        $wallet = $this->walletRepository->getById($walletId);
        if (!$wallet) {
            throw new LocalizedException(__('Wallet not found.'));
        }

        $collection = $this->walletTopupCollectionFactory->create();

        $collection->addFieldToFilter('wallet_id', $walletId);

        return $collection;
    }

    /**
     * @param int $customerId
     * @return WalletInterface
     * @throws LocalizedException
     * @throws MailException
     * @throws NoSuchEntityException
     */
    public function setupWalletForCustomer(int $customerId): WalletInterface
    {
        $welcomeCreditEnabled = $this->scopeConfig->isSetFlag(
            self::XML_PATH_WELCOME_CREDIT_ENABLED,
            ScopeInterface::SCOPE_WEBSITE
        );
        $welcomeCredit = $this->scopeConfig->getValue(
            self::XML_PATH_WELCOME_CREDIT_AMOUNT,
            ScopeInterface::SCOPE_WEBSITE
        );

        // Create a new wallet
        /** @var WalletInterface $wallet */
        $wallet = $this->walletFactory->create();
        $wallet
            ->setCustomerId($customerId)
            ->setBalance(0.00)
            ->setIsActive(1);

        if ($welcomeCreditEnabled && $welcomeCredit != 0) {
            $wallet->setBalance($welcomeCredit);
        }

        // Save the wallet
        $this->walletRepository->save($wallet);

        // Send necessary emails
        $this->sendWalletSetupCompleteEmail($customerId);
        if ($welcomeCreditEnabled && $welcomeCredit != 0) {
            $this->sendWalletWelcomeCreditEmail($customerId);
        }

        return $wallet;
    }

    /**
     * @param int $walletId
     * @param float $amount
     * @param string $msg
     * @return bool
     * @throws \Exception
     */
    public function addFundsToWallet(
        int $walletId,
        float $amount,
        string $msg = ''
    ): bool {
        $wallet = $this->walletRepository->getById($walletId);

        if ($wallet->getId() && $wallet->getCustomerId()) {
            $topup = $this->walletTopupFactory->create();
            $topup
                ->setWalletId($wallet->getId())
                ->setAmount($amount)
                ->setNote($msg);

            $topup->save();

            // Recalculate the balance
            $wallet->setBalance($amount + $wallet->getBalance());
            $this->walletRepository->save($wallet);

            return true;
        }

        return false;
    }

    /**
     * @param int $senderWalletId
     * @param int $receiverWalletId
     * @param float $amount
     * @param string $msg
     * @return bool
     * @throws LocalizedException
     * @throws NoSuchEntityException
     * @throws \Exception
     */
    public function transfer(
        int $senderWalletId,
        int $receiverWalletId,
        float $amount,
        string $msg = ''
    ): bool {
        $senderWallet = $this->walletRepository->getById($senderWalletId);
        $receiverWallet = $this->walletRepository->getById($receiverWalletId);

        // Check if the sender has enough funds
        if ($senderWallet->getBalance() < $amount) {
            throw new LocalizedException(__('Insufficient balance.'));
        }

        // Update the balances of sender & receiver
        $senderWallet->setBalance($senderWallet->getBalance() - $amount);
        $receiverWallet->setBalance($receiverWallet->getBalance() + $amount);

        // Create the transaction
        $transaction = $this->walletTransactionFactory->create();
        $transaction
            ->setWalletId($senderWalletId)
            ->setAmount($amount)
            ->setRelatedWalletId($receiverWalletId)
            ->setDescription($msg);

        $transaction->save();
        $this->walletRepository->save($senderWallet);
        $this->walletRepository->save($receiverWallet);

        return true;
    }
}
