<?php

namespace BCLib\Alma;

class AlmaSoapClient
{
    private $_user;
    private $_password;
    private $_wsdl;

    /**
     * @var \SoapClient
     */
    private $_client;

    private $_last_error = false;

    public function __construct($user, $password, $institution, $wsdl)
    {
        $this->_user = 'AlmaSDK-' . $user . '-institutionCode-' . $institution;
        $this->_password = $password;
        $this->_wsdl = $wsdl;
    }

    public function execute($function_name, array $params)
    {
        if (is_null($this->_client)) {
            $this->_loadClient();
        }

        $result = $this->_client->$function_name($params);

        if (substr((string) $result->SearchResults, 0, 8) == '<OAI-PMH') {
            $payload = '<enclosure>' . $result->SearchResults . '</enclosure>';
        } else {
            $payload = (string) $result->SearchResults;
        }

        $base = new \SimpleXMLElement($payload);

        if ((string) $base->errorsExist === 'true') {
            $this->_last_error = new \stdClass();
            $this->_last_error->code = (string) $base->errorList->error->errorCode;
            $this->_last_error->message = (string) $base->errorList->error->errorMessage;
        }

        return $base;
    }

    public function lastError()
    {
        return $this->_last_error;
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