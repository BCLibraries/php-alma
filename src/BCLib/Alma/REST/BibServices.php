<?php

namespace BCLib\Alma\REST;

use BCLib\Alma\AlmaCache;
use Doctrine\Common\Cache\Cache;

class BibServices
{
    /**
     * @var Client
     */
    private $_client;

    /**
     * @var AlmaCache
     */
    private $_cache;

    public function __construct($client, AlmaCache $cache)
    {
        $this->_client = $client;
        $this->_cache = $cache;
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
        return $this->_load(new HoldingList(), $url, $mms, $cache_ttl);
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
        $item_list = $this->_load(new ItemsList(), $url, $holdings_id, $cache_ttl);
        foreach ($item_list as $item) {
            $this->_cache->save($item->pid, $item, $cache_ttl);
        }
        return $item_list;
    }

    protected function _load(Loadable $container, $url, $id, $cache_ttl)
    {
        $cache_key = $this->_cache->key(get_class($container), $id);
        if ($this->_cache->contains($cache_key)) {
            return $this->_cache->read($cache_key);
        }
        $this->_client->load($container, $url);
        $this->_cache->save($id, $container, $cache_ttl);
        return $container;
    }

}