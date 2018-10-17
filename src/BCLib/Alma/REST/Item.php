<?php

namespace BCLib\Alma\REST;

class Item implements Loadable
{
    public $link;
    public $pid;
    public $barcode;
    public $description;
    public $library_value;
    public $library_desc;
    public $location_value;
    public $location_desc;
    public $enumeration;

    public $holding_link;
    public $holding_id;
    public $temp_library_value;
    public $temp_library_desc;
    public $temp_location_value;
    public $temp_location_desc;

    /**
     * @var \BCLib\Alma\REST\Bib
     */
    public $bib_data;

    public function loadJSON($item_json)
    {
        $this->bib_data = new Bib();
        $this->bib_data->loadJSON($item_json->bib_data);
        $this->link = isset($item_json->link) ? $item_json->link : null;

        $item_data = isset($item_json->item_data) ? $item_json->item_data : null;

        $this->pid = isset($item_data->pid) ? $item_data->pid : null;
        $this->description = isset($item_data->description) ? $item_data->description : null;
        $this->barcode = isset($item_data->barcode) ? $item_data->barcode : null;
        $this->library_desc = isset($item_data->library->desc) ? $item_data->library->desc : null;
        $this->library_value = isset($item_data->library->value) ? $item_data->library->value : null;
        $this->location_desc = isset($item_data->location->desc) ? $item_data->location->desc : null;
        $this->location_value = isset($item_data->location->value) ? $item_data->location->value : null;
        $this->enumeration = isset($item_data->enumeration_a) ? $item_data->enumeration_a : null;

        $holding_data = isset($item_json->holding_data) ? $item_json->holding_data : null;

        $this->holding_id = isset($holding_data->holding_id) ? $holding_data->holding_id : null;
        $this->holding_link = isset($holding_data->link) ? $holding_data->link : null;
        $this->temp_library_desc = isset($holding_data->temp_library->desc) ? $holding_data->temp_library->desc : null;
        $this->temp_library_value = isset($holding_data->temp_library->value) ? $holding_data->temp_library->value : null;
        $this->temp_location_desc = isset($holding_data->temp_library->desc) ? $holding_data->temp_library->desc : null;
        $this->temp_location_value = isset($holding_data->temp_library->value) ? $holding_data->temp_library->value : null;
    }
}