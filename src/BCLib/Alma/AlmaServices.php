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

    public static function courseServices($wsdl = null)
    {
        $wsdl = is_null($wsdl) ? AlmaServices::$_wsdl_directory . '/UserWebServices.xml' : $wsdl;
        $client = AlmaServices::_getSoapClient($wsdl);

        $citation_factory = new CitationFactory(new Book(), new Article());
        $list_prototype = new ReadingList($citation_factory);
        $section = new Section($list_prototype);

        return new CourseServices($client, $section);
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