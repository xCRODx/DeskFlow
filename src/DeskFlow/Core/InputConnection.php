<?php 
namespace DeskFlow\Core;
class InputConnection {
    public $id;
    public $input;
    public $output;

    function __construct(Input &$input, Output &$output) {
        $this->setId();
        $this->input =& $input;
        $this->output =& $output;
    }

    public function setId(){
        $this->id = uniqid();
    }

    public function getId() {
        return $this->id;
    }

    public function isActive(){
        
    }
}