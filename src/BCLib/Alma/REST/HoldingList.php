<?php

namespace BCLib\Alma\REST;

class HoldingList
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
} 