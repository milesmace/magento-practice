<?php

namespace KW\CustomerWallet\Api\Data;

interface WalletInterface
{
    /**#@+
     * Constants for keys
     */
    const CUSTOMER_ID = 'customer_id';
    const BALANCE = 'balance';
    const PUBLIC_HASH = 'public_hash';
    const IS_ACTIVE = 'is_active';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /**#@-*/

    /**
     * @return int
     */
    public function getCustomerId();

    /**
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId($customerId);

    /**
     * @return float
     */
    public function getBalance();

    /**
     * @param float $balance
     * @return $this
     */
    public function setBalance($balance);

    /**
     * @return string
     */
    public function getPublicHash(): string;

    /**
     * @param string $publicHash
     * @return $this
     */
    public function setPublicHash(string $publicHash): WalletInterface;

    /**
     * @return bool
     */
    public function getIsActive();

    /**
     * @param bool $isActive
     * @return $this
     */
    public function setIsActive(bool $isActive);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * @return string
     */
    public function getUpdatedAt();

    /**
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt);
}
