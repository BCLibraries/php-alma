<?php

namespace BCLib\Alma\REST;

use BCLib\Alma\AlmaCache;

class BibServices
{
    /**
     * @var Client
     */
    private $_client;

    /**
     * @var \BCLib\Alma\AlmaCache
     */
    private $_cache;

    public function __construct(Client $client, AlmaCache $cache)
    {
        $this->_client = $client;
        $this->_cache = $cache;
    }

    public function listHoldings($mms, $cache_ttl = 600)
    {
        $cache_key = $this->_cache->holdingsListKey($mms);

        if ($this->_cache->contains($cache_key)) {
            return $this->_cache->read($cache_key);
        }

        $response = $this->_client->fetch('bibs/' . $mms . '/holdings/');

        $holdings_list = new HoldingList();
        $holdings_list->total_record_count = $response->total_record_count;
        $holdings_list->bib_data = new Bib();
        $holdings_list->bib_data->loadJSON($response->bib_data);
        foreach ($response->holding as $holding_json_object) {
            $holding = new Holding();
            $holding->loadJson($holding_json_object);
            $holdings_list->holdings[] = $holding;
        }

        $this->_cache->save($cache_key, $holdings_list, $cache_ttl);

        return $holdings_list;
    }

}