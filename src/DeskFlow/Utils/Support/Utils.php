<?php
namespace DeskFlow\Utils\Support;
class Utils {
    public static $bench;
    /**
     * Return the name of the class withou the namespace path
     * @return string
     */
    public static function getClassName($className) : String {
        if(is_object($className))
            $className = get_class($className);
        $className = explode('\\', $className);
        return end($className);
    }

    public static function benchStart(){
        self::$bench = microtime(true);
    }

    public static function benchElapsed(){
        return microtime(true) - self::$bench;
    }
}