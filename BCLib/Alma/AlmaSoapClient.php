<?php

namespace BCLib\Alma;

class AlmaSoapClient
{
    private $_user;
    private $_password;
    private $_wsdl;
    private $_client;

    public function __construct($user, $instution, $password, $wsdl)
    {
        $this->_user = 'AlmaSDK-' . $user . '-institutionCode-' . $instution;
        $this->_password = $password;
        $this->_wsdl = $wsdl;
    }

    public function execute($function_name, array $params)
    {
        if (is_null($this->_client))
        {
            $this->_loadClient();
        }

        return $this->_client->$function_name($params);
    }

    private function _loadClient()
    {
        $soap_params = Array(
            'login'     => $this->_user,
            'password'  => $this->_password,
            'trace'     => true,
            'exception' => true
        );

        $this->_client = new \SoapClient($this->_wsdl, $soap_params);
    }
}