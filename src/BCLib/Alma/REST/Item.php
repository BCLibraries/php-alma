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
        $this->link = $item_json->link;

        $item_data = $item_json->item_data;

        $this->pid = $item_data->pid;
        $this->description = $item_data->description;
        $this->barcode = $item_data->barcode;
        $this->library_desc = $item_data->library->desc;
        $this->library_value = $item_data->library->value;
        $this->location_desc = $item_data->location->desc;
        $this->location_value = $item_data->location->value;
        $this->enumeration = $item_data->enumeration_a;

        $holding_data = $item_json->holding_data;

        $this->holding_id = $holding_data->holding_id;
        $this->holding_link = $holding_data->link;
        $this->temp_library_desc = $holding_data->temp_library->desc;
        $this->temp_library_value = $holding_data->temp_library->value;
        $this->temp_location_desc = $holding_data->temp_library->desc;
        $this->temp_location_value = $holding_data->temp_library->value;
    }
}