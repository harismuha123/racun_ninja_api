<?php

class ResidentialUnitManager extends BaseManager
{
    public function add_residential_unit($unit)
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO residential_units(user_id, name, address)
             VALUES(:user_id, :name, :address)"
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
            "residential_unit_id" => $id,
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

        try {
            $residential_unit_id = $request["residential_unit_id"];
            $providers = $request["providers"];
            $credentials = $request["credentials"];
            $index = 0;

            foreach ($providers as $provider_id) {
                $data = array(
                    "residential_unit_id" => $residential_unit_id,
                    "provider_id" => $provider_id,
                    "credentials" => json_decode($credentials[$index], true),
                );

                $temp_statement = $this->pdo->prepare(
                    "INSERT INTO residential_unit_providers(residential_unit_id, provider_id, credentials)
                 VALUES(:residential_unit_id, :provider_id, :credentials)"
                );

                $temp_statement->execute($data);

                $residential_unit_provider_id = $this->pdo->lastInsertId();

                switch($provider_id){
                    case 1:
                    case "1":
                        $bills = Flight::ta()->fetch_data($data["credentials"]);
                        break;                
                    default:
                        break;
                }

                foreach ($bills as $bill) {
                    $bill_statement = $this->pdo->prepare(
                        "INSERT INTO bills(date, debt, paid)
                     VALUES(:date, :debt, :paid)"
                    );

                    $bill_statement->execute($bill);

                    $bill_id = $this->pdo->lastInsertId();

                    $unit_bill_statement = $this->pdo->prepare(
                        "INSERT INTO residential_unit_bills(residential_unit_provider_id, bill_id)
                     VALUES(:unit_id, :bill_id)"
                    );

                    $unit_bill_statement->execute(array(
                        "unit_id" => $residential_unit_provider_id,
                        "bill_id" => $bill_id,
                    ));
                }

                $index++;
            }

            return "Providers added successfully.";
        } catch (PDOException $e) {
            return $e;
        }
    }

    public function get_residential_units($user_id)
    {
        $statement = $this->pdo->prepare(
            "SELECT * FROM residential_units WHERE user_id = :user_id"
        );

        $statement->execute(array(
            "user_id" => $user_id,
        ));

        $residential_units = $statement->fetchAll();

        $final_units = [];

        foreach ($residential_units as $unit) {
            $temp_statement = $this->pdo->prepare(
                "SELECT p.provider_id as provider_id, p.name as name
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
