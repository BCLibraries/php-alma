<?php

namespace BCLib\Alma\REST;

class Holding
{
    public $holding_id;

    public $library_value;
    public $library_desc;

    public $location_value;
    public $location_desc;

    public $link;

    public $call_number;

    public function loadJson($holding_json_object)
    {
        $this->holding_id = $holding_json_object->holding_id;
        $this->call_number = $holding_json_object->call_number;
        $this->library_desc = $holding_json_object->library->desc;
        $this->library_value = $holding_json_object->library->value;
        $this->location_desc = $holding_json_object->location->desc;
        $this->location_value = $holding_json_object->location->value;
        $this->link = $holding_json_object->link;
    }
}