<?php

namespace BCLib\Alma\REST\Exceptions;

use Guzzle\Http\Message\Response;

class BadRequestException extends \Exception
{
    /**
     * @var \Guzzle\Http\Message\Response
     */
    protected $_response;

    protected $_error_list;

    protected $_result;

    public function __construct(Response $response, \Exception $previous = null)
    {
        parent::__construct('Bad response', 0, $previous);
        $this->_response = $response;
    }

    public function getErrors()
    {
        $this->_loadJson();
        return $this->_error_list;
    }

    public function getResult()
    {
        $this->_loadJson();
        return $this->_result;
    }

    public function getStatusCode()
    {
        return $this->_response->getStatusCode();
    }

    public function getURL()
    {
        return $this->_response->getEffectiveUrl();
    }

    protected function _loadJson()
    {
        if ($this->_result !== null) {

            $body = json_decode($this->_response->getBody());

            $this->_error_list = [];

            foreach ($body->errorList as $error_json) {
                $error = new \stdClass();
                $error->code = $error_json->errorCode;
                $error->message = $error_json->errorMessage;
                $this->_error_list[] = $error;
            }
            $this->_result = $body->result;
        }
    }

}