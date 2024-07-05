<?php
namespace DeskFlow\Core;
class Node {
    public $id;
    public String $name = 'BaseNode';
    public $seed;
    public $service;
    public $inputs = [];
    public $outputs = [];
    public $isActive = false;
    public $lastFather = null;

    public function __construct(Array $params = [], Node &$father = null) {
        $this->setId((isset($params['id']) ? $params['id'] : null)); // Set the id for the new node
        $seed = $this->getId(); // Set the seed as the id by default
        if ($father){
            $seed = $father->getSeed();
            //$this->parents[$father->id] = $father;
        }
        $this->setSeed($seed); // Set the seed for the new node
    }

    public function setSeed($seed){
        $this->seed = $seed;
    }

    public function setId($id = null){
        $this->id = $id ?: uniqid();
    }

    public function getId() :String {
        return $this->id;
    }

    public function getSeed(){
        return $this->seed;
    }

    public function setService(Service $service) {
        $this->service =& $service;
    }

    public function addInput(BaseInput|Array &$input) {
        if(is_array($input))
            $input = $this->createBaseInput($input);
        $this->inputs[$input->index] =& $input;
    }

    public function addOutput(BaseInput|Array &$output) {
        if(is_array($output))
            $output = $this->createBaseInput($output);
        $this->outputs[$output->index] =& $output;
    }

    public function createBaseInput(Array $params) : BaseInput {
        $index = $params['index'] ?? count($this->inputs);
        return new BaseInput($index, $this, $params);
    }

    public function &getInputByIndex(int $index) : BaseInput {
        return $this->inputs[$index];
    }

    public function &getInputByName(string $name) : BaseInput|null {
        foreach ($this->inputs as $input) {
            if ($input->name == $name)
                return $input;
        }
        return null;
    }

    public function isActive() {
        return $this->isActive;
    }

    public function setActive(){
        $this->isActive = true;
    }

    public function onError($error){}

    public function afterError(){}

    protected function error($message){
        $this->onError($message);
        // throw an error by the DeskFlow owns catching error
        //throw new NodeException($this, $message);
        $this->afterError();
        die($message);
    }

    public function NextNode(){
        
    }

    // public function propagate() {
    //     foreach ($this->outputs as $output) {
    //         $output->activate();
    //     }
    // }

}

