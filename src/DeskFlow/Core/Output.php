<?php 
namespace DeskFlow\Core;
class Output extends BaseInput{
    const TYPE = 'output';
    function __construct(Int $index, Node &$node, array $params = []) {
        if(!isset($params['input_type']))
            $this->input = 'output';
        parent::__construct($index, $node, $params);
    }

    // public Array $connections = [];
    // // The input has only 2 states, active and not active
    // const STATE_ACTIVE = 'active';
    // const STATE_NOT_ACTIVE = 'not active';

    // public $value;

    // public function __construct(Node $node) {
    //     $this->state = self::STATE_NOT_ACTIVE;
    // }

    // public function setValue(Service &$value){
    //     $this->value = $value;
    // }
    
    // public function activate() {
    //     if ($this->state !== self::STATE_ACTIVE) {
    //         $this->state = self::STATE_ACTIVE;
    //     }
    // }
    
}