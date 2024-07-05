<?php
namespace DeskFlow\Core;
class Runtime{
    public static $connections = [];
    public static $nodes = [];
    public static $inputs = [];
    public static $outputs = [];
    
    function __construct() {
        self::$connections = [];
        // initialize runtime components
    }

    public static function addConnection(Input &$input, Output &$output){
        $connection = new inputConnection($input, $output);
        $connection_id = $connection->getId();
        self::$connections[$connection_id] = $connection;
        return $connection_id;
    }

    public static function start(){
        // start the runtime
    }

    public function addNode(Node $node){
        self::$nodes[$node->id] = $node;
    }

    // Nearly will have a common class called somithing as DeskFlow that is recognized as a DeskFlow object
    public static function onError($guilty){
        echo nl2br("Error. Let the component speak... \r");
        var_dump($guilty);
        die;
    }
}