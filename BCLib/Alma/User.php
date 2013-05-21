<?php

namespace BCLib\Alma;

require_once __DIR__ . '/Block.php';
require_once __DIR__ . '/Identifier.php';

class User
{
    /** @var AlmaSoapClient */
    private $_soap_client;

    /** @var \SimpleXMLElement */
    private $_xml;


    public function __construct(AlmaSoapClient $soap_client)
    {
        $this->_soap_client = $soap_client;
    }

    public function load($identifier)
    {
        $params = array('arg0' => $identifier);
        $result = $this->_soap_client->execute('getUserDetails', $params);
        $base = new \SimpleXMLElement($result->SearchResults);
        $children = $base->result->children('http://com/exlibris/urm/user_record/xmlbeans');
        $this->_xml = $children[0];
    }

    public function firstName()
    {
        return (string) $this->_xml->userDetails->firstName;
    }

    public function middleName()
    {
        return (string) $this->_xml->userDetails->middleName;
    }

    public function lastName()
    {
        return (string) $this->_xml->userDetails->lastName;
    }

    public function email()
    {
        foreach ($this->_xml->userAddressList->userEmail as $email_xml)
        {
            if ($email_xml->attributes()['preferred'] == 'true')
            {
                return (string) $email_xml->email[0];
            }
        }
        return '';
    }

    public function isActive()
    {
        return ((string) $this->_xml->userDetails->status === 'Active');
    }

    public function groupName(array $group_map = array())
    {
        $group_id = $this->groupCode();
        return isset($group_map[$group_id]) ? $group_map[$group_id] : $group_id;
    }

    public function groupCode()
    {
        return (string) $this->_xml->userDetails->userGroup;
    }

    /**
     * @param array $id_type_map
     *
     * @return Identifier[]
     */
    public function identifiers(array $id_type_map = array())
    {
        $return_ids = array();

        $identifiers_xml = $this->_xml->userIdentifiers->userIdentifier;
        foreach ($identifiers_xml as $identifier_xml)
        {
            $id = new Identifier();
            $id->value = (string) $identifier_xml->value;
            $id->code = (string) $identifier_xml->type;
            $id->name = '';
            if (isset($id_type_map[$id->code]))
            {
                $id->name = $id_type_map[$id->code];
            }
            $return_ids[] = $id;
        }

        return $return_ids;
    }

    /**
     * @return Block[]
     */
    public function blocks()
    {
        $return_blocks = array();

        $blocks_xml = $this->_xml->userBlockList->userBlock;
        foreach ($blocks_xml as $block_xml)
        {
            $block = new Block();
            $block->code = (string) $block_xml->blockDefinitionId;
            $block->type = (string) $block_xml->type;
            $block->status = (string) $block_xml->status;
            $block->note = (string) $block_xml->note;
            $block->creation_date = (string) $block_xml->owneredEntity->creationDate;
            $block->modification_date = (string) $block_xml->owneredEntity->modificationDate;
            $return_blocks[] = $block;
        }

        return $return_blocks;
    }
}