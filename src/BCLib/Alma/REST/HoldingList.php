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
    public $holdings = array();

    public $total_record_count;

    public function loadJson($json)
    {
        $this->total_record_count = $json->total_record_count;
        foreach ($json->holding as $holding_json)
        {
            $holding = new Holding();
            $holding->loadJson($holding_json);
            $this->holdings[] = $holding;
        }
        $this->bib_data = new Bib();
        $this->bib_data->loadJSON($json->bib_data);
    }
}