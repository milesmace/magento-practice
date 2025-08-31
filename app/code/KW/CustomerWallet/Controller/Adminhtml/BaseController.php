<?php

namespace KW\CustomerWallet\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

abstract class BaseController extends Action
{
    public const ADMIN_RESOURCE = 'KW_CustomerWallet::wallet';

    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE);
    }

}
