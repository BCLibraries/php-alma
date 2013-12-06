<?php

namespace BCLib\Alma;

use Doctrine\Common\Cache\Cache;

class AlmaServices
{
    protected static $_username;
    protected static $_password;
    protected static $_wsdl_directory;
    protected static $_institution;

    /**
     * @var
     */
    protected static $_cache;


    public static function initialize($username, $password, $institution, Cache $cache = null)
    {
        AlmaServices::$_username = $username;
        AlmaServices::$_password = $password;
        AlmaServices::$_institution = $institution;
        AlmaServices::$_wsdl_directory = __DIR__ . '/../../../wsdl';

        if ($cache instanceof Cache) {
            AlmaServices::$_cache = $cache;
        }
    }

    public static function userInfoServices($wsdl = null, $group_names = array(), $id_types = array())
    {
        $wsdl = is_null($wsdl) ? AlmaServices::$_wsdl_directory . '/UserWebServices.xml' : $wsdl;
        $client = AlmaServices::_getSoapClient($wsdl);
        $user = new User(new Identifier($id_types), new Block());
        return new UserInfoServices($client, $user, $group_names);
    }

    public static function courseServices($wsdl = null)
    {
        $wsdl = is_null($wsdl) ? AlmaServices::$_wsdl_directory . '/CourseWebServices.xml' : $wsdl;
        $client = AlmaServices::_getSoapClient($wsdl);

        $citation_factory = new CitationFactory(new Book(), new Article());
        $list_prototype = new ReadingList($citation_factory);
        $section = new Section($list_prototype);
        $cache = new AlmaCache(AlmaServices::$_cache);

        return new CourseServices($client, $section, $cache);
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