<?php

namespace BCLib\Alma\REST;

use BCLib\Alma\AlmaCache;

class BibServices
{
    /**
     * @var Client
     */
    private $_client;

    public function __construct(Client $client)
    {
        $this->_client = $client;
    }

    /**
     * @param     $mms
     * @param int $cache_ttl
     *
     * @return HoldingList
     */
    public function listHoldings($mms, $cache_ttl = 600)
    {
        $url = 'bibs/' . $mms . '/holdings/';
        return $this->_client->load(new HoldingList(), $url, $mms, $cache_ttl);
    }

    /**
     * @param     $holdings_id
     * @param     $mms
     * @param int $cache_ttl
     *
     * @return ItemsList
     */
    public function listItems($holdings_id, $mms, $cache_ttl = 60)
    {
        $url = 'bibs/' . $mms . '/holdings/' . $holdings_id . '/items';
        return $this->_client->load(new ItemsList(), $url, $holdings_id, $cache_ttl);
    }

}