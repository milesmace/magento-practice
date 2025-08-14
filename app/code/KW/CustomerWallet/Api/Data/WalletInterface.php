<?php

namespace KW\CustomerWallet\Api\Data;

interface WalletInterface
{
    /**#@+
     * Constants for keys
     */
    const CUSTOMER_ID = 'customer_id';
    const BALANCE = 'balance';
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
