<?php

namespace KW\CustomerWallet\Model;

use KW\CustomerWallet\Api\Data\WalletInterface;
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

    public function getPublicHash(): string
    {
        return $this->getData(self::PUBLIC_HASH);
    }

    public function setPublicHash(string $publicHash): WalletInterface
    {
        return $this->setData(self::PUBLIC_HASH, $publicHash);
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
}
