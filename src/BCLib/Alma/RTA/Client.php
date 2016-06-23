<?php

namespace BCLib\Alma\RTA;

class Client
{
    /**
     * @var \Guzzle\Http\Client
     */
    private $client;

    /**
     * @var Parser
     */
    private $rta_parser;

    /**
     * @var Request
     */
    private $request;

    private $alma_host;
    private $library;

    public function __construct($client, Parser $rta_parser, $alma_host, $library)
    {
        $this->client = $client;
        $this->alma_host = $alma_host;
        $this->library = $library;
        $this->rta_parser = $rta_parser;
    }

    /**
     * @param array $ids
     * @return Holding[]
     * @throws \Exception
     */
    public function fetch(array $ids)
    {
        $response = $this->client->get($this->buildUrl($ids))->send();
        return $this->rta_parser->read($response->getBody(true));
    }

    public function buildUrl($ids)
    {
        $query = http_build_query(
            array(
                'doc_num' => join(',', $ids),
                'library' => $this->library
            )
        );
        return "http://" . $this->alma_host . "/view/publish_avail?$query";
    }
}