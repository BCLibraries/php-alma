<?php
/**
 * Created by PhpStorm.
 * User: florinb
 * Date: 3/21/14
 * Time: 3:07 PM
 */

namespace BCLib\Alma\REST;


class Item
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

    public $holding_data;

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
    }
}