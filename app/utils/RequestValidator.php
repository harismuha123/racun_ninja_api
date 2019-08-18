<?php

class RequestValidator {

    public function checkPasswordStrength($password) {
        $hashed_password = strtoupper(sha1($password));
        $hash_prefix = substr($hashed_password, 0, 5);
        $hash_suffix = substr($hashed_password, 5);
        $endpoint = 'https://api.pwnedpasswords.com/range/' . $hash_prefix;
        $user_agent = 'Pwnage-Checker-for-SSSD';
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $endpoint);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_HEADER, true);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl_handle, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($curl_handle);

        if (isset($response) && $response !== false) {
            return strpos($response, $hash_suffix) === false;
        }
        return true;
    }

    public function validatePassword ($password) {
        switch(true) {
            case strlen($password) < 6:
                return array("message" => "Password must be at least 6 characters long", "valid" => false);
            case !preg_match('/(?=.*[A-Z])[a-zA-Z0-9\S]/', $password):
                return array("message" => "Password must contain at least one uppercase character!", "valid" => false);
            case !preg_match('/(?=.*\W.*)[a-zA-Z0-9\S]/', $password):
                return array("message" => "Password must contain at least one special character!", "valid" => false);
            case !preg_match('/(?=.*\d.*)[a-zA-Z0-9\S]/', $password):
                return array("message" => "Password must contain at least one digit!", "valid" => false);
            case !$this->checkPasswordStrength($password):
                return array("message" => "Password is too weak and has been breached in the past. Please choose a stronger password!", "valid" => false);
            default:
                return array("message" => "Password is valid!", "valid" => true);
        }
    }

    public function validateUsername ($username) {
        if(!preg_match('/^[a-zA-Z0-9-_]+$/', $username)) {
            return array("message" => "Username can only contain alphanumeric, dash, and underscore characters!", "valid" => false);
        }

        return array("message" => "Username is valid!", "valid" => true);
    }

    public function validateEmail ($email) {

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return array("message" => "Email is not valid!", "valid" => false);
        }

        return array("message" => "Email is valid!", "valid" => true);
    }

}