<?php
/**
 * Created by PhpStorm.
 * User: florinb
 * Date: 11/20/13
 * Time: 11:36 AM
 */

namespace BCLib\Alma;

class UserInfoServices
{
    protected $_soap_client;
    protected $_user_prototype;

    public function __construct(AlmaSoapClient $client, User $user_prototype)
    {
        $this->_soap_client = $client;
        $this->_user_prototype = $user_prototype;
    }

    public function getUser($identifier)
    {
        $user = false;
        $params = array('arg0' => $identifier);
        $base = $this->_soap_client->execute('getUserDetails', $params);
        if ($this->_soap_client->lastError() === false) {
            $children = $base->result->children('http://com/exlibris/urm/user_record/xmlbeans');
            $user = clone $this->_user_prototype;
            $user->load($children[0]);
        }
        return $user;
    }

    public function lastError()
    {
        return $this->_soap_client->lastError();
    }
} 