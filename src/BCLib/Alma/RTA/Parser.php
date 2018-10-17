<?php

namespace BCLib\Alma\RTA;

class Parser
{
    /**
     * @var \DomXPath
     */
    private $xpath;

    public function __construct()
    {
        $this->ava_map = [
            'a' => 'institution',
            'b' => 'library',
            'c' => 'location',
            'd' => 'call_number',
            'e' => 'availability',
            'f' => 'number',
            'g' => 'number_unavailable',
            'j' => 'j',
            'k' => 'multi_volume',
            'p' => 'number_loans'
        ];
    }

    /**
     * @param $rta_xml
     * @return array
     * @throws \Exception
     */
    public function read($rta_xml)
    {
        $rta_dom = new \DOMDocument();
        if (!$rta_dom->loadXML($rta_xml)) {
            throw new \Exception('Invalid RTA response');
        }
        $this->xpath = new \DOMXPath($rta_dom);
        $this->xpath->registerNamespace('oai', 'http://www.openarchives.org/OAI/2.0/');
        $this->xpath->registerNamespace('slim', 'http://www.loc.gov/MARC21/slim');

        foreach ($this->xpath->evaluate('/publish-avail/oai:OAI-PMH') as $node) {
            $id = explode(':', $node->getElementsByTagName('identifier')->item(0)->nodeValue)[1];
            $response[$id] = $this->readRecord($node);
        }
        return $response;
    }

    private function readRecord(\DOMElement $record_xml)
    {
        $holding = new Holding();
        $ava_fields = $this->xpath->evaluate(
            "oai:ListRecords/oai:record/oai:metadata/slim:record/slim:datafield[@tag='AVA']",
            $record_xml
        );
        foreach ($ava_fields as $ava) {
            $holding->items[] = $this->readItemRecord($ava);
        }
        return $holding;
    }

    private function readItemRecord(\DOMElement $ava_field)
    {
        $item = new Item();
        foreach ($ava_field->getElementsByTagName('subfield') as $sub) {
            $property = $this->ava_map[$sub->getAttribute('code')];
            $item->$property = $sub->nodeValue;
        }
        return $item;
    }
}