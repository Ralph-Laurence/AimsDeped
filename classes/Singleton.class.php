<?php 
use IO\DbHelper;
use Views\Boilerplate;
 
require_once("DbHelper.class.php");
require_once("Boilerplate.class.php");

class Singleton
{ 
    private static $m_Instance = null;
    private static $m_DbInstance;
    private static $m_BoilerPlateInstance;

    private function __construct()
    {
        self::$m_DbInstance = self::InitDbHelper();
        self::$m_BoilerPlateInstance = self::InitBoilerPlate();
    }

    //
    // Instance of this class
    //
    public static function GetInstance()
    {
        if (self::$m_Instance == null)
        {
            self::$m_Instance = new Singleton();
        }

        return self::$m_Instance;
    }

    //
    // Database Helper Instance
    //
    private static function InitDbHelper() : DbHelper
    { 
        return new DbHelper
        (
            Config::GetHost(),
            Config::GetUsername(),
            Config::GetPassword(),
            Config::GetDbName() 
        );
    }

    public static function GetDbHelperInstance() : DbHelper
    {
        if (self::$m_DbInstance == null)    
        {
            self::$m_DbInstance = self::InitDbHelper();
        }

        return self::$m_DbInstance;
    }  

    //
    // Document Boilerplate Instance
    //
    private static function InitBoilerPlate() : Boilerplate
    {
        return new Boilerplate();
    }

    public static function GetBoilerPlateInstance() : Boilerplate
    {
        if (self::$m_BoilerPlateInstance == null)
        {
            self::$m_BoilerPlateInstance = self::InitBoilerPlate();
        }

        return self::$m_BoilerPlateInstance;
    }
} 

?>