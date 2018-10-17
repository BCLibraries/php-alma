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
        $this->holding_id = isset($holding_json_object->holding_id) ? $holding_json_object->holding_id : null;
        $this->call_number = isset($holding_json_object->call_number) ? $holding_json_object->call_number : null;
        $this->library_desc = isset($holding_json_object->library->desc) ? $holding_json_object->library->desc : null;
        $this->library_value = isset($holding_json_object->library->value) ? $holding_json_object->library->value : null;
        $this->location_desc = isset($holding_json_object->location->desc) ? $holding_json_object->location->desc : null;
        $this->location_value = isset($holding_json_object->location->value) ? $holding_json_object->location->value : null;
        $this->link = isset($holding_json_object->link) ? $holding_json_object->link : null;
    }
}