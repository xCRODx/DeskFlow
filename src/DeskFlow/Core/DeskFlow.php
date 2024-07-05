<?php
namespace DeskFlow\Core;
use DeskFlow\Utils\Support\Utils;
use DeskFlow\Core\Input;
use DeskFlow\Core\Output;
use DeskFlow\Core\Node;
use DeskFlow\Core\BaseInput;

class DeskFlow {
    public static $events = [];
    public function __construct(){
        Utils::benchStart();
        $node1 = new Node();
        $input1Node1 = new Input(0, $node1);
        $input2Node1 = new Input(1, $node1);
        $input3Node1 = new Input(2, $node1);
        $output1Node1 = new Output(0, $node1);
        $output2Node1 = new Output(1, $node1);
        
        var_dump(Utils::benchElapsed());
        $node2 = new Node();
        $input1Node2 = new Input(0, $node2);
        $output1Node2 = new Output(0, $node2);
        
        var_dump(Utils::benchElapsed());
        $output1Node1->connect($input1Node2);
        var_dump(Utils::benchElapsed());
        $output1Node2->connect($input3Node1);
        var_dump(Utils::benchElapsed());
        $output2Node1->connect($input1Node2);
        var_dump(Utils::benchElapsed());
        $output2Node1->connect($input1Node2);
        //var_dump($output2Node1);
        $input1Node1->setState(BaseInput::STATE_FINISHED);
        var_dump($output2Node1->connections);

        var_dump(Utils::benchElapsed());
        
        echo "<br>-----------------------------------------<br>";
        $this->dispatch('deskflow', [$this]);
    }

    // register a event with a callback function
    public static function event(String $eventName, callable $callback){
        self::$events[$eventName][] = $callback;
    }

    /**
     * Runs the event
     */
    public static function dispatch(String $eventName, $data = null){
        if(isset(self::$events[$eventName])){
            foreach(self::$events[$eventName] as $callback){
                call_user_func_array($callback, $data);
            }
        }
    }

    public function update(){
        $this->dispatch('update', array($this));
    }

    public function mount(){
        $this->dispatch('mount', array($this));
    }

    public function unmount(){
        $this->dispatch('unmount', array($this));
    }

    public function run(){
        $this->dispatch('run', array($this));
        $this->finish();
    }

    public function finish(){
        $this->dispatch('finish', array($this));
    }
}