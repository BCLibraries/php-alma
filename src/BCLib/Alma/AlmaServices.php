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
        self::$_username = $username;
        self::$_password = $password;
        self::$_institution = $institution;
        self::$_wsdl_directory = __DIR__ . '/../../../wsdl-https';

        if ($cache instanceof Cache) {
            self::$_cache = $cache;
        }
    }

    public static function userInfoServices($wsdl = null, $group_names = [], $id_types = [])
    {
        $client = self::_getSoapClient('UserWebServices.xml', $wsdl);
        $user = new User(new Identifier($id_types), new Block());
        $cache = new AlmaCache(self::$_cache);
        return new UserInfoServices($client, $user, $group_names, $cache);
    }

    public static function courseServices($wsdl = null)
    {
        $client = self::_getSoapClient('CourseWebServices.xml', $wsdl);
        $citation_factory = new CitationFactory(new Book(), new Article());
        $list_prototype = new ReadingList($citation_factory);
        $section = new Section($list_prototype);
        $cache = new AlmaCache(self::$_cache);
        return new CourseServices($client, $section, $cache);
    }

    public static function holdingsServices($wsdl = null)
    {
        $client = self::_getSoapClient('ResourceManagementWebServices.xml', $wsdl);
        $holding_prototype = new Holding();
        $cache = new AlmaCache(self::$_cache);
        return new HoldingsService($client, $holding_prototype, $cache);
    }

    public static function bibServices(
        $alma_api_key,
        $base_url,
        Cache $cache = null,
        $api_version = 'v1'

    ) {
        $alma_cache = new AlmaCache($cache);
        $guzzle = new \Guzzle\Http\Client();
        $client = new REST\Client($guzzle, $alma_api_key, $base_url, $api_version);
        return new REST\BibServices($client, $alma_cache);
    }

    protected static function _getSoapClient($default_wsdl, $user_wsdl = null)
    {
        $user_wsdl = $user_wsdl === null ? self::$_wsdl_directory . "/$default_wsdl" : $user_wsdl;
        return new AlmaSoapClient(
            self::$_username,
            self::$_password,
            self::$_institution,
            $user_wsdl);
    }
}