<?php

namespace BCLib\Alma;

/**
 * Class SoapBibRecord
 * @package BCLib\Alma
 *
 * @property Holding[] holdings
 * @property string    mms
 */
class SoapBibRecord implements \JsonSerializable
{
    /**
     * @var \File_MARC_Record
     */
    protected $_marc;

    protected $_holdings = array();

    /**
     * @var Holding
     */
    protected $_holding_template;

    protected $_marc_text;

    public function __construct(\File_MARC_Record $marc, Holding $holding_template)
    {
        $this->_marc = $marc;
        $this->_holding_template = $holding_template;
    }

    public function __get($name)
    {
        switch ($name) {
            case 'holdings':
                return $this->_getHoldings();
            case 'mms':
                return $this->_marc->getField('001')->getData();
            default:
                throw new \Exception("$name is not a valid bib record field");
        }

    }

    protected function _getHoldings()
    {
        if (count($this->_holdings)) {
            return $this->_holdings;
        }

        foreach ($this->_marc->getFields('AVA') as $ava_field) {
            $holding = clone $this->_holding_template;
            $holding->load($ava_field);
            $this->_holdings[] = $holding;
        }

        return $this->_holdings;
    }

    /**
     * @param $field_num
     *
     * @return array|\File_MARC_List
     */
    function getMARCField($field_num)
    {
        return $this->_marc->getFields($field_num);
    }

    function jsonSerialize()
    {
        $bib_record = new \stdClass();
        $bib_record->mms = $this->mms;
        $bib_record->holdings = $this->holdings;
        return $bib_record;
    }

    // File_MARC_Record keeps a copy of the MARCXML as a SimpleXML object. SimpleXML
    // can't be serialized, so we have to convert the MARCXML to raw text.
    function __sleep()
    {
        $this->_marc_text = gzcompress($this->_marc->toXML());
        return array("_marc_text", "_holding_template");
    }

    function __wakeup()
    {
        $this->_marc_text = gzuncompress($this->_marc_text);
        $marc_file = new \File_MARCXML((string) $this->_marc_text, \File_MARCXML::SOURCE_STRING);
        $this->_marc = $marc_file->next();
    }

}