<?php

namespace Webkul\MiniCart\Plugin\Checkout\CustomerData;

class Cart2
{
    public function afterGetSectionData(\Magento\Checkout\CustomerData\Cart $subject, array $result)
    {
        $result['extra_dataa'] = $result['subtotalAmount'];
        return $result;
    }
}
