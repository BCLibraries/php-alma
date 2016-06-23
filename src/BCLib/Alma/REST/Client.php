<?php

namespace BCLib\Alma\REST;

use BCLib\Alma\REST\Exceptions\BadRequestException;
use Guzzle\Http\Exception\BadResponseException;

class Client
{

    /**
     * @var \Guzzle\Http\Client
     */
    private $_client;

    private $_key;

    public function __construct(
        \Guzzle\Http\Client $client,
        $alma_api_key,
        $base_url,
        $api_version = 'v1'
    ) {
        $this->_client = $client;
        $this->_client->setBaseUrl("$base_url/$api_version");
        $this->_key = $alma_api_key;
    }

    public function _fetch($url)
    {
        $query_string = 'apikey=' . $this->_key;

        try {
            $response = $this->_client->get(
                "$url.json?$query_string",
                array(
                    'headers' => array(
                        'Accept' => 'application/json'
                    )
                )
            )->send()->getBody(true);
        } catch (BadResponseException $e) {
            throw new BadRequestException($e->getResponse());
        };

        return json_decode($response);
    }

    public function load(Loadable $container, $url)
    {
        $response = $this->_fetch($url);
        $container->loadJson($response);
        return $container;
    }

} 