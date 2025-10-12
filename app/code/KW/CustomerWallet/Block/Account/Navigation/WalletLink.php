<?php

namespace KW\CustomerWallet\Block\Account\Navigation;

use Magento\Customer\Block\Account\SortLink;

class WalletLink extends SortLink
{

    protected function _toHtml()
    {
        if ($this->getTemplate()) {
            return parent::_toHtml();
        }

        // Determine CSS classes
        $classes = ['nav', 'item'];
        if ($this->getIsHighlighted() || $this->isCurrent()) {
            $classes[] = 'current';
        }
        $classHtml = implode(' ', $classes);

        // Start list item
        $html = '<li class="' . $classHtml . '">';

        // Always render <a> tag so the link is clickable
        $html .= '<a href="' . $this->escapeHtml($this->getHref()) . '"';

        // Optional title attribute
        if ($this->getTitle()) {
            $html .= ' title="' . $this->escapeHtml(__($this->getTitle())) . '"';
        }

        $html .= $this->getAttributesHtml() . '>';

        // Highlight label if needed
        if ($this->getIsHighlighted()) {
            $html .= '<strong>';
        }

        $html .= $this->escapeHtml(__($this->getLabel()));

        if ($this->getIsHighlighted()) {
            $html .= '</strong>';
        }

        $html .= '</a></li>';

        return $html;
    }

    public function isCurrent()
    {
        $currentFullActionName = $this->getRequest()->getFullActionName();
        return in_array(
            $currentFullActionName,
            [
                'customer_wallet_index',
                'customer_wallet_transfer',
                'customer_wallet_topup',
            ],
        );
    }
}
