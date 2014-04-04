<?php

namespace BCLib\Alma\REST;

class ItemsList implements Loadable
{
    /**
     * @var \BCLib\Alma\Rest\Item[]
     */
    public $items = array();
    public $total_record_count;

    public function loadJson($item_list_json)
    {
        foreach ($item_list_json->item as $item_json) {
            $item = new Item();
            $item->loadJSON($item_json);
            $this->items[] = $item;
        }
        $this->total_record_count = $item_list_json->total_record_count;
    }
}