<?php

namespace BCLib\Alma\REST;

class Bib
{
    public $title;
    public $author;
    public $issn;
    public $isbn;
    public $publisher;
    public $link;
    public $mms_id;
    public $complete_edition;
    public $place_of_publication;
    public $network_numbers = array();

    public function loadJSON($bib_data_json)
    {
        $this->mms_id = $bib_data_json->mms_id;
        $this->author = $bib_data_json->author;
        $this->issn = $bib_data_json->issn;
        $this->isbn = $bib_data_json->isbn;
        $this->link = $bib_data_json->link;
        $this->complete_edition = $bib_data_json->complete_edition;
        $this->place_of_publication = $bib_data_json->place_of_publication;
        $this->publisher = $bib_data_json->publisher;
        $this->title = $bib_data_json->title;
        $this->network_numbers = $bib_data_json->network_numbers->network_number;
    }
} 