<?php

if (!function_exists('generateOTP')) {
    function generateOTP($n)
    {
        $generator = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789123abcdefghijklmnopqrstuvwxyz";
        $result = '';
        for ($i = 1; $i < $n; $i++) {
            $result .= substr($generator, (rand() % (strlen($generator)) - 1), 1);
        }
        return $result;
    }
}
