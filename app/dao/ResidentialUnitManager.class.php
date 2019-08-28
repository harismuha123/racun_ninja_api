<?php

class ResidentialUnitManager extends BaseManager
{
    public function add_residential_unit($unit)
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO residential_units(name, address)
             VALUES(:name, :address)"
        );

        $statement->execute($unit);

        return $this->pdo->lastInsertId();
    }

    public function update_residential_unit($unit, $id)
    {
        $statement = $this->pdo->prepare(
            "UPDATE residential_units
             SET name=:name, address=:address
             WHERE residential_unit_id = :residential_unit_id"
        );

        $statement->execute(array(
            "name" => $unit["name"],
            "address" => $unit["address"],
            "residential_unit_id" => $id
        ));
    }

    public function delete_residential_unit($id)
    {
        $statement = $this->pdo->prepare(
            "DELETE FROM residential_units
             WHERE residential_unit_id = :unit_id"
        );

        $statement->execute(array(
            "unit_id" => $id,
        ));

        $provider_statement = $this->pdo->prepare(
            "DELETE FROM residential_unit_providers
             WHERE residential_unit_id = :unit_id"
        );

        $provider_statement->execute(array(
            "unit_id" => $id,
        ));
    }

    public function add_providers_to_residential_unit($request)
    {

        $user_id = $request["user_id"];
        $residential_unit_id = $request["residential_unit_id"];
        $providers = $request["providers"];
        $credentials = $request["credentials"];
        $index = 0;

        foreach ($providers as $provider_id) {
            $data = array(
                "user_id" => $user_id,
                "residential_unit_id" => $residential_unit_id,
                "provider_id" => $provider_id,
                "credentials" => $credentials[$index]
            );
            $temp_statement = $this->pdo->prepare(
                "INSERT INTO residential_unit_providers(user_id, residential_unit_id, provider_id, credentials)
                 VALUES(:user_id, :residential_unit_id, :provider_id, :credentials)"
            );
            $temp_statement->execute($data);
            $index++;
        }
    }

    public function get_residential_units($user_id)
    {
        $statement = $this->pdo->prepare(
            "SELECT r.residential_unit_id as residential_unit_id, r.name as name, r.address as address FROM residential_units AS r
             JOIN residential_unit_providers AS rp
             ON r.residential_unit_id = rp.residential_unit_id
             JOIN users AS u
             ON rp.user_id = u.user_id
             WHERE u.user_id = :user_id"
        );

        $statement->execute(array(
            "user_id" => $user_id,
        ));

        $residential_units = $statement->fetchAll();

        $final_units = [];

        foreach ($residential_units as $unit) {
            $temp_statement = $this->pdo->prepare(
                "SELECT p.provider_id as provider_id, p.name as name, p.logo as logo, p.uri as uri
                 FROM providers as p
                 JOIN residential_unit_providers as rp
                 ON p.provider_id = rp.provider_id
                 WHERE rp.residential_unit_id = :unit_id"
            );
            $temp_statement->execute(array(
                "unit_id" => $unit["residential_unit_id"],
            ));
            $unit["providers"] = $temp_statement->fetchAll();
            array_push($final_units, $unit);
        }

        return $final_units;
    }

    public function delete_provider_for_residential_unit($request)
    {
        $statement = $this->pdo->prepare(
            "DELETE FROM residential_unit_providers 
             WHERE residential_unit_id = :residential_unit_id
             AND provider_id = :provider_id"
        );

        $statement->execute($request);
    }

}
