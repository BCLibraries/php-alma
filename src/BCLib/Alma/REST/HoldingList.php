<?php

namespace BCLib\Alma\REST;

class HoldingList implements Loadable
{
    /**
     * @var \BClib\Alma\REST\Bib
     */
    public $bib_data;

    /**
     * @var \BClib\Alma\REST\Holding[]
     */
    public $holdings = [];

    public $total_record_count;

    public function loadJson($json)
    {
        $this->total_record_count = $json->total_record_count;
        if (isset ($json->holding)) {
            foreach ($json->holding as $holding_json) {
                $holding = new Holding();
                $holding->loadJson($holding_json);
                $this->holdings[] = $holding;
            }
        }
        if (isset($json->bib_data)) {
            $this->bib_data = new Bib();
            $this->bib_data->loadJSON($json->bib_data);
        }
    }
}