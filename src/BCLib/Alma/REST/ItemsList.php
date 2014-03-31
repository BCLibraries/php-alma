<?php

namespace BCLib\Alma\REST;

class ItemsList implements \IteratorAggregate, \ArrayAccess, Loadable
{
    /**
     * @var \BCLib\Alma\Rest\Item[]
     */
    protected $_items = array();
    protected $total_record_count;

    public function loadJson($item_list_json)
    {
        foreach ($item_list_json->item as $item_json) {
            $item = new Item();
            $item->loadJSON($item_json);
            $this->_items[] = $item;
        }
        $this->total_record_count = $item_list_json->total_record_count;
    }

    public function offsetExists($offset)
    {
        return isset($this->_items[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->_items[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->_items[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->_items[$offset]);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->_items);
    }
}