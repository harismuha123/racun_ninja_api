<?php

class BillManager extends BaseManager
{
    public function get_bills($provider_id, $residential_unit_id)
    {
        $statement = $this->pdo->prepare(
            "SELECT b.bill_id as bill_id, b.date as date, b.debt as debt, b.paid as paid
             FROM bills AS b
             JOIN residential_unit_bills AS rb
             ON b.bill_id = rb.bill_id
             JOIN residential_unit_providers AS rp
             ON rb.residential_unit_provider_id = rp.id
             WHERE rp.provider_id = :provider_id AND rp.residential_unit_id = :residential_unit_id"
        );

        $statement->execute(array(
            "provider_id" => $provider_id,
            "residential_unit_id" => $residential_unit_id,
        ));

        return $statement->fetchAll();
    }

    public function get_average_debt($provider_id, $residential_unit_id)
    {
        $statement = $this->pdo->prepare(
            "SELECT ROUND(AVG(b.debt), 2) as average_debt
             FROM bills AS b
             JOIN residential_unit_bills AS rb
             ON b.bill_id = rb.bill_id
             JOIN residential_unit_providers AS rp
             ON rb.residential_unit_provider_id = rp.id
             WHERE rp.provider_id = :provider_id AND rp.residential_unit_id = :residential_unit_id"
        );

        $statement->execute(array(
            "provider_id" => $provider_id,
            "residential_unit_id" => $residential_unit_id,
        ));

        return $statement->fetch();
    }

    public function get_total_debt($provider_id, $residential_unit_id)
    {
        $statement = $this->pdo->prepare(
            "SELECT ROUND((SUM(b.debt) - SUM(b.paid)), 2) as total_debt
             FROM bills AS b
             JOIN residential_unit_bills AS rb
             ON b.bill_id = rb.bill_id
             JOIN residential_unit_providers AS rp
             ON rb.residential_unit_provider_id = rp.id
             WHERE rp.provider_id = :provider_id AND rp.residential_unit_id = :residential_unit_id"
        );

        $statement->execute(array(
            "provider_id" => $provider_id,
            "residential_unit_id" => $residential_unit_id,
        ));

        return $statement->fetch();
    }
}
