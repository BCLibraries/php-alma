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
     * @var Doctrine\Common\Cache
     */
    protected static $_cache;


    public static function initialize($username, $password, $institution, Cache $cache = null)
    {
        AlmaServices::$_username = $username;
        AlmaServices::$_password = $password;
        AlmaServices::$_institution = $institution;
        AlmaServices::$_wsdl_directory = __DIR__ . '/../../../wsdl-https';

        if ($cache instanceof Cache) {
            AlmaServices::$_cache = $cache;
        }
    }

    public static function userInfoServices($wsdl = null, $group_names = array(), $id_types = array())
    {
        $client = AlmaServices::_getSoapClient('UserWebServices.xml', $wsdl);
        $user = new User(new Identifier($id_types), new Block());
        $cache = new AlmaCache(AlmaServices::$_cache);
        return new UserInfoServices($client, $user, $group_names, $cache);
    }

    public static function courseServices($wsdl = null)
    {
        $client = AlmaServices::_getSoapClient('CourseWebServices.xml', $wsdl);
        $citation_factory = new CitationFactory(new Book(), new Article());
        $list_prototype = new ReadingList($citation_factory);
        $section = new Section($list_prototype);
        $cache = new AlmaCache(AlmaServices::$_cache);
        return new CourseServices($client, $section, $cache);
    }

    public static function holdingsServices($wsdl = null)
    {
        $client = AlmaServices::_getSoapClient('ResourceManagementWebServices.xml', $wsdl);
        $holding_prototype = new Holding();
        $cache = new AlmaCache(AlmaServices::$_cache);
        return new HoldingsService($client, $holding_prototype, $cache);
    }

    protected static function _getSoapClient($default_wsdl, $user_wsdl = null)
    {
        $user_wsdl = is_null($user_wsdl) ? AlmaServices::$_wsdl_directory . "/$default_wsdl" : $user_wsdl;
        return new AlmaSoapClient(
            AlmaServices::$_username,
            AlmaServices::$_password,
            AlmaServices::$_institution,
            $user_wsdl);
    }
}