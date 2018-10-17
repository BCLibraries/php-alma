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
    public $network_numbers = [];

    public function loadJSON($bib_data_json)
    {
        $this->mms_id = isset($bib_data_json->mms_id) ? $bib_data_json->mms_id : null;
        $this->author = isset($bib_data_json->author) ? $bib_data_json->author : null;
        $this->issn = isset($bib_data_json->issn) ? $bib_data_json->issn : null;
        $this->isbn = isset($bib_data_json->isbn) ? $bib_data_json->isbn : null;
        $this->link = isset($bib_data_json->link) ? $bib_data_json->link : null;
        $this->complete_edition = isset($bib_data_json->complete_edition) ? $bib_data_json->complete_edition : null;
        $this->place_of_publication = isset($bib_data_json->place_of_publication) ? $bib_data_json->place_of_publication : null;
        $this->publisher = isset($bib_data_json->publisher) ? $bib_data_json->publisher : null;
        $this->title = isset($bib_data_json->title) ? $bib_data_json->title : null;
        $this->network_numbers = isset($bib_data_json->network_numbers->network_number) ? $bib_data_json->network_numbers->network_number : null;
    }
} 