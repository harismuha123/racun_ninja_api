<?php

use OTPHP\TOTP;

class LoginValidator
{

    public function rememberUser($user)
    {
        $hour = time() + 3600 * 24 + 30;
        setcookie('username', $user["username"], $hour);
        setcookie('password', $user["password"], $hour);
    }

    public function generateTOTP($email)
    {
        $mySecret = trim(AUTH_SECRET);
        $otp = TOTP::create($mySecret);
        $otp->setLabel($email);
        $googleChartUri = $otp->getProvisioningUri();
        return $otp->getQrCodeUri(
            'https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=' . $googleChartUri,
            $googleChartUri);
    }

    public function verifyTOTP($verification_code)
    {
        $mySecret = trim(AUTH_SECRET);
        $otp = TOTP::create($mySecret);
        return $otp->verify($verification_code);
    }

}
