<?php

require_once "Mailer.class.php";

class TokenManager extends BaseManager
{

    public function generate_email_token($email_or_username)
    {
        $newStatement = $this->pdo->prepare(
            "SELECT * FROM users WHERE username = ? OR email = ?"
        );
        $newStatement->execute([$email_or_username, $email_or_username]);
        $user = $newStatement->fetch();

        if ($user) {
            $bytes = random_bytes(16);
            $email_token = bin2hex($bytes);

            $query = "INSERT INTO email_tokens(email_token, email_or_username, created_at, valid_until)
                    VALUES (:email_token, :email_or_username, :created_at, :valid_until)";

            $data = array(
                "email_token" => $email_token,
                "email_or_username" => $email_or_username,
                "created_at" => date('Y-m-d H:i:s'),
                "valid_until" => date('Y-m-d H:i:s', strtotime('+5 minutes')),
            );

            $statement = $this->pdo->prepare($query);
            $statement->execute($data);
            $activation_mail = "
            <h3>You have initiated the password reset process.</h3>
            <hr>
            If you have not initiated this process, contact your administrator immediately! This link will expire after 5 minutes.
            <hr>
            Please click on the bottom link to reset your password:<br>
            https://sssd.github.io/reset/?token=" . $email_token . "";
            /* send activation mail */
            $message = Mailer::mail($user["email"], "SSSD Password Reset Request", $activation_mail);
            return array("message" => $message, 'success' => true);
        } else {
            return array("message" => "User does not exist", 'success' => false);
        }
    }

    public function get_email_token($token)
    {
        $query = "SELECT * FROM email_tokens WHERE email_token = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute([$token]);
        return $statement->fetch();
    }

    public function update_password($token, $newPassword)
    {
        $query = "UPDATE users
                  SET password = ?
                  WHERE email = ? OR username = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute([password_hash($newPassword, PASSWORD_DEFAULT), $token["email_or_username"], $token["email_or_username"]]);
        return array("message" => "Password has been updated!", 'success' => true);
    }
}
