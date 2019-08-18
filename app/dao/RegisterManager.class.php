<?php

class RegisterManager extends BaseManager{

    public function add_user($input) {
        try {
            $this->pdo->query("ALTER TABLE users AUTO_INCREMENT = 1");

            $query = "INSERT INTO users (name, email, mobile_number, username, password)
                      VALUES(:name, :email_address, :mobile_number, :username, :password)";
            $statement = $this->pdo->prepare($query);
            $data = array(
                name => $input["name"],
                email_address => $input["email_address"],
                mobile_number => $input["mobile_number"],
                username => $input["username"],
                password => password_hash($input["password"], PASSWORD_DEFAULT)
            );
            $statement-> execute($data);
            return array("message" => "User added successfully!", 'success' => true);
        } catch (PDOException $e) {
            if($e->errorInfo[1] == 1062) {
                return array("message" => "User already exists!", 'success' => false);
            } else {
                return array("message" => "Error while adding user!", 'success' => false);
            }
        }
    }

    public function get_user($username) {
        $query = "SELECT * FROM users WHERE username = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute([$username]);
        return $statement->fetch();
    }

    public function get_user_by_email($email) {
        $query = "SELECT * FROM users WHERE email = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute([$email]);
        return $statement->fetch();
    }
}