<?php

namespace KW\CustomerWallet\Model;

use KW\CustomerWallet\Api\Data\WalletInterface;
use KW\CustomerWallet\Api\Data\WalletTransactionInterface;
use KW\CustomerWallet\Model\ResourceModel\WalletTransaction\Collection as WalletTransactionCollection;
use KW\CustomerWallet\Model\ResourceModel\WalletTransaction\CollectionFactory as WalletTransactionCollectionFactory;
use KW\CustomerWallet\Model\ResourceModel\WalletTopup\CollectionFactory as WalletTopupCollectionFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Data\Collection\AbstractDb as AbstractDbCollection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

class Wallet extends AbstractModel implements WalletInterface
{
    protected $_idFieldName = 'wallet_id';
    protected $_eventPrefix = 'wallet';
    protected $_eventObject = 'wallet';
    protected $_cacheTag = 'wallet';

    // Local States and data
    private ?CustomerInterface $_customer = null;

    protected function _construct()
    {
        $this->_init(\KW\CustomerWallet\Model\ResourceModel\Wallet::class);
    }

    public function __construct(
        Context $context,
        Registry $registry,
        private CustomerRepositoryInterface $customerRepository,
        private WalletTransactionCollectionFactory $walletTransactionCollectionFactory,
        private WalletTopupCollectionFactory $walletTopupCollectionFactory,
        private WalletTopupFactory $walletTopupFactory,
        ?AbstractResource $resource = null,
        ?AbstractDbCollection $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    public function getCustomerId()
    {
        return (int) $this->getData(self::CUSTOMER_ID);
    }

    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    public function getBalance()
    {
        return (float) $this->getData(self::BALANCE);
    }

    public function setBalance($balance)
    {
        return $this->setData(self::BALANCE, $balance);
    }

    public function getIsActive()
    {
        return (bool) $this->getData(self::IS_ACTIVE);
    }

    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * @return CustomerInterface|null
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getCustomer(): ?CustomerInterface
    {
        if ($this->getId() && $this->getCustomerId()) {
            if ($this->_customer && $this->_customer->getId()) {
                return $this->_customer;
            }

            $this->_customer = $this->customerRepository->getbyId($this->getCustomerId());
            return $this->_customer;
        }
        return null;
    }

    public function getTransactions(): ?array
    {
        if ($this->getId() && $this->getCustomerId()) {
            $collection = $this->walletTransactionCollectionFactory->create();
            $collection->addFieldToFilter(
                ['wallet_id', WalletTransactionInterface::RELATED_WALLET_ID],
                [$this->getId(), $this->getId()]
            );

            return $collection->getItems();
        }
        return null;
    }

    public function getTopups(): ?array
    {
        if ($this->getId() && $this->getCustomerId()) {
            $collection = $this->walletTopupCollectionFactory->create();
            $collection->addFieldToFilter('wallet_id', $this->getId());

            return $collection->getItems();
        }

        return null;
    }

    public function addMoney(int $amount, string $note): bool
    {
        if ($this->getId() && $this->getCustomerId()) {
            $topup = $this->walletTopupFactory->create();
            $topup
                ->setWalletId($this->getId())
                ->setAmount($amount)
                ->setNote($note);
            $topup->save();

            // Recalculate the balance
            $this->setBalance($amount + $this->getBalance());
            $this->save();
        }
        return true;
    }
}
