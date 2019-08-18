<?php

class LoginManager extends BaseManager
{

    public function remember_me($username, $password)
    {
        $hour = time() + 3600 * 24 * 30;
        setcookie('username', $username, $hour);
        setcookie('password', $password, $hour);
    }

    public function get_user($username_or_email)
    {
        $query = "SELECT * FROM users WHERE email = ? OR username = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute([$username_or_email, $username_or_email]);
        return $statement->fetch();
    }

    public function set_remember($email)
    {
        $query = "UPDATE users SET remember_me WHERE email = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute([$username_or_email]);
    }

}
