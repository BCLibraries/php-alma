<?php

namespace BCLib\Alma;

class AlmaServices
{
    protected static $_username;
    protected static $_password;
    protected static $_wsdl_directory;
    protected static $_institution;

    public static function initialize($username, $password, $institution)
    {
        AlmaServices::$_username = $username;
        AlmaServices::$_password = $password;
        AlmaServices::$_institution = $institution;
        AlmaServices::$_wsdl_directory = __DIR__ . '/../../../wsdl';
    }

    public static function userInfoServices($wsdl = null)
    {
        $wsdl = is_null($wsdl) ? AlmaServices::$_wsdl_directory . '/UserWebServices.xml' : $wsdl;
        $client = AlmaServices::_getSoapClient($wsdl);
        return new UserInfoServices($client, new User());
    }

    protected static function _getSoapClient($wsdl)
    {
        return new AlmaSoapClient(
            AlmaServices::$_username,
            AlmaServices::$_password,
            AlmaServices::$_institution,
            $wsdl);
    }
}