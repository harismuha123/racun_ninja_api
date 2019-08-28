<?php

class TelemachAdapter extends BaseAdapter
{
    public function __construct()
    {
        parent::__construct("https://mojtelemach.ba/racuni/pregled-racuna");
    }

    public function fetch_data($credentials)
    {
        $form = $this->result["forms"][0];

        // Set some or all of the variables in the form.
        $form->SetFormValue("username", $credentials["username"]);
        $form->SetFormValue("password", $credentials["password"]);

        // Submit the form.
        $result2 = $form->GenerateFormRequest();
        $this->result = $this->web->Process($result2["url"], $result2["options"]);

        if (!$this->result["success"]) {
            echo "Error retrieving URL.  " . $this->result["error"] . "\n";
            exit();
        }

        if ($this->result["response"]["code"] != 200) {
            echo "Error retrieving URL.  Server returned:  " . $this->result["response"]["code"] . " " . $this->result["response"]["meaning"] . "\n";
            exit();
        }

        $html = TagFilter::Explode($this->result["body"], $this->htmloptions);
        $root = $html->Get();

        $bills = array();

        $dates = $root->Find("#page-wrap > div > section.row.service-row.devices.box-shadow > div.table-wrap > div.r-table > div.r-table-row > div.r-table-cell.bills-date-cell");

        foreach ($dates as $item) {
            $sentence = $item->GetInnerHTML();
            array_push($bills, array(
                "date" => trim(str_replace('<span class="responsive-table-title">Datum izdavanja računa:</span>', '', $sentence)),
            ));
        }

        $rows = $root->Find("#page-wrap > div > section.row.service-row.devices.box-shadow > div.table-wrap > div.r-table > div.r-table-row > div.r-table-cell.bills-price-cell");

        $debt_data = array();

        foreach ($rows as $item) {
            $sentence = $item->GetInnerHTML();
            array_push($debt_data, trim(str_replace('<span class="responsive-table-title">Iznos:</span>', '', str_replace("KM", "", $sentence))));
        }

        $debt = array();

        for ($i = 0; $i < count($debt_data); $i += 2) {
            if (($i + 1) <= count($debt_data)) {
                array_push($debt, array(
                    "debt" => $debt_data[$i],
                    "paid" => $debt_data[$i + 1],
                ));
            }
        }

        $final_data = array();

        for ($i = 0; $i < count($debt); $i++) {
            array_push($final_data, array_merge($bills[$i], $debt[$i]));
        }

        return $final_data;
    }
}
