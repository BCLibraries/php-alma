<?php

namespace BCLib\Alma\REST;

use BCLib\Alma\AlmaCache;

class Client
{

    /**
     * @var \Guzzle\Http\Client
     */
    private $_client;

    private $_key;

    /**
     * @var \BCLib\Alma\AlmaCache
     */
    private $_cache;

    public function __construct(
        \Guzzle\Http\Client $client,
        $alma_api_key,
        $base_url,
        AlmaCache $cache,
        $api_version = 'v1'
    ) {
        $this->_client = $client;
        $this->_client->setBaseUrl("$base_url/$api_version");
        $this->_key = $alma_api_key;
        $this->_cache = $cache;
    }

    public function _fetch($url)
    {
        $query_string = 'apikey=' . $this->_key;
        $response = $this->_client->get(
            "$url.json?$query_string",
            array(
                'headers' => array(
                    'Accept' => 'application/json'
                )
            )
        )->send()->getBody(true);
        return json_decode($response);
    }

    public function load(Loadable $container, $url, $id, $cache_ttl)
    {
        $cache_key = $this->_cache->key(get_class($container), $id);

        if ($this->_cache->contains($cache_key)) {
            return $this->_cache->read($cache_key);
        }
        $response = $this->_fetch($url);
        $container->loadJson($response);
        $this->_cache->save($cache_key, $container, $cache_ttl);
        return $container;
    }

} 