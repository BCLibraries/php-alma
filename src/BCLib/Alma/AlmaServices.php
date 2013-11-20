<?php

namespace BCLib\Alma;

class AlmaServices
{
    protected static $_username;
    protected static $_password;
    protected static $_wsdl_directory;
    protected static $_institution;

    public static function initialize($username, $password, $institution, $wsdl_directory = null)
    {
        AlmaServices::$_username = $username;
        AlmaServices::$_password = $password;
        AlmaServices::$_institution = $institution;
        if (is_null($wsdl_directory)) {
            AlmaServices::$_wsdl_directory = __DIR__ . '/../../../wsdl';
        } else {
            AlmaServices::$_wsdl_directory = $wsdl_directory;
        }
    }

    public static function userInfoServices($wsdl = null)
    {
        if (is_null($wsdl)) {
            $wsdl = AlmaServices::$_wsdl_directory . "/UserWebServices.xml";
        }

        $client = new AlmaSoapClient(
            AlmaServices::$_username,
            AlmaServices::$_password,
            AlmaServices::$_institution,
            $wsdl);
        return new UserInfoServices($client, new User());
    }
}