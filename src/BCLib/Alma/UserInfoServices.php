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
    /**
     * @var AlmaSoapClient
     */
    protected $_soap_client;
    protected $_user_prototype;
    protected $_group_codes;
    protected $_id_types;

    /**
     * @var \BClib\Alma\AlmaCache
     */
    protected $_cache;

    public function __construct(
        AlmaSoapClient $client,
        User $user_prototype,
        array $group_codes,
        AlmaCache $cache
    ) {
        $this->_soap_client = $client;
        $this->_user_prototype = $user_prototype;
        $this->_group_codes = $group_codes;
        $this->_cache = $cache;
    }

    public function getUser($identifier)
    {
        if ($this->_cache->containsUser($identifier)
        ) {
            return array($this->_cache->getUser($identifier));
        }

        $user = false;
        $params = array('arg0' => $identifier);
        $base = $this->_soap_client->execute('getUserDetails', $params);
        if ($this->_soap_client->lastError() === false) {
            $children = $base->result->children('http://com/exlibris/urm/user_record/xmlbeans');
            $user = clone $this->_user_prototype;
            $user->load($children[0], $this->_group_codes);
            $cache_user = clone $user;
            $this->_cache->saveUser($cache_user);
        }
        return $user;
    }

    public function lastError()
    {
        return $this->_soap_client->lastError();
    }
} 