<?php

class ProviderManager extends BaseManager
{
    public function add_provider($provider)
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO providers(name, logo, uri)
             VALUES(:name, :logo, :uri)"
        );
        $statement->execute($provider);
    }

    public function get_providers()
    {
        $statement = $this->pdo->prepare(
            "SELECT * FROM providers"
        );
        $statement->execute();
        return $statement->fetchAll();
    }

    public function get_provider_by_id($id)
    {
        $statement = $this->pdo->prepare(
            "SELECT * FROM providers WHERE provider_id = :provider_id"
        );
        $statement->execute([
            "provider_id" => $id
        ]);
        return $statement->fetch();
    }
}
