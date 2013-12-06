<?php

namespace BCLib\Alma;

/**
 * Class User
 * @package BCLib\Alma
 *
 * @property string       first_name
 * @property string       middle_name
 * @property string       last_name
 * @property string       username
 * @property string       email
 * @property boolean      is_active
 * @property string       group_code
 * @property string       group_name
 * @property Identifier[] identifiers
 * @property Block[]      blocks
 */
class User
{
    /** @var \SimpleXMLElement */
    protected $_xml;

    protected $_group_names;
    protected $_id_types;
    protected $_last_error;

    protected $_identifiers;
    protected $_blocks;

    /**
     * @var Identifier
     */
    protected $_id_prototype;

    /**
     * @var Block
     */
    protected $_block_prototype;

    public function __construct(Identifier $id_prototype, Block $block_prototype)
    {
        $this->_id_prototype = $id_prototype;
        $this->_block_prototype = $block_prototype;
    }


    public function load(\SimpleXMLElement $xml, array $group_names = array())
    {
        $this->_xml = $xml;
        $this->_group_names = $group_names;
    }

    public function __get($property)
    {
        switch ($property) {
            case 'first_name':
                return (string) $this->_xml->userDetails->firstName;
            case 'middle_name':
                return (string) $this->_xml->userDetails->middleName;
            case 'last_name':
                return (string) $this->_xml->userDetails->lastName;
            case 'user_name':
                return (string) $this->_xml->userDetails->userName;
            case 'email':
                return $this->_email();
            case 'is_active':
                return ((string) $this->_xml->userDetails->status === 'Active');
            case 'group_code':
                return (string) $this->_xml->userDetails->userGroup;
            case 'group_name':
                return $this->_groupName();
            case 'identifiers':
                return $this->_identifiers();
            case 'blocks':
                return $this->_blocks();
            case 'identifiers':
                return $this->_identifiers();
        }
    }

    protected function _email()
    {
        foreach ($this->_xml->userAddressList->userEmail as $email_xml) {
            $attributes_array = $email_xml->attributes();
            if ($attributes_array['preferred'] == 'true') {
                return (string) $email_xml->email[0];
            }
        }
        return '';
    }

    protected function _groupName()
    {
        if (isset ($this->_group_names[$this->group_code])) {
            return $this->_group_names[$this->group_code];
        } else {
            return false;
        }
    }

    /**
     * @return Identifier[]
     */
    protected function _identifiers()
    {
        if (!is_array($this->_identifiers)) {
            $this->_identifiers = array();
            $identifiers_xml = $this->_xml->userIdentifiers->userIdentifier;
            foreach ($identifiers_xml as $identifier_xml) {
                $id = clone $this->_id_prototype;
                $id->load($identifier_xml);
                $this->_identifiers[] = $id;
            }
        }
        return $this->_identifiers;
    }

    /**
     * @return Block[]
     */
    protected function _blocks()
    {
        if (!is_array($this->_blocks)) {
            $this->_blocks = array();
            $blocks_xml = $this->_xml->userBlockList->userBlock;
            foreach ($blocks_xml as $block_xml) {
                $block = clone $this->_block_prototype;
                $block->load($block_xml);
                $this->_blocks[] = $block;
            }
        }
        return $this->_blocks;
    }

    public function lastError()
    {
        return $this->_last_error;
    }

    public function __sleep()
    {
        // SimpleXMLElements can't be serialized. Convert to XML text.
        $this->_xml->addAttribute('xmlns:xmlns:xsi', "http://www.w3.org/2001/XMLSchema-instance");
        $this->_xml = $this->_xml->asXML();
        return array('_xml', '_id_prototype', '_block_prototype');
    }

    public function __wakeup()
    {
        $this->_xml = new \SimpleXMLElement($this->_xml);
    }
}