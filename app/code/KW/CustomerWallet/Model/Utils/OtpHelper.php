<?php

namespace KW\CustomerWallet\Model\Utils;

class OtpHelper
{

    public function generate(int $length = 6): string
    {
        $nums = '0123456789';
        $otp = '';

        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, 9);
            $otp .= $nums[$rand];
        }

        return $otp;
    }
}
