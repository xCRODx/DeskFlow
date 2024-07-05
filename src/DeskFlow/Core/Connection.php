<?php
namespace DeskFlow\Core;

/**
 * Gerencia as conexões dos inputs com os outputs
 */
class Connection {
    public $id;
    public $connections = [];
    public $parents = [];
    public $childrens = [];
    public $actualConnectionIndex = null;
    public BaseInput $owner; // The Input owner of the connectors - The Node is inside owner BaseInput object

    public function __construct(BaseInput &$BaseInput = null) {
        $this->setId();
        $this->actualConnectionIndex = 0;
        $this->owner =& $BaseInput;
    }

    public function setId(){
        $this->id = uniqid();
    }

    public function getId() {
        return $this->id;
    }

    public function addConnection(Input &$input) {
        $this->checkIsValidConnection($input);
        $connection_id = Runtime::addConnection($input, $this->owner);
        $this->childrens[$connection_id] =& Runtime::$connections[$connection_id];
        $input->addParent(Runtime::$connections[$connection_id]);
    }

    public function addParentConnection(InputConnection &$connection) {
        $this->parents[$connection->getId()] =& $connection;
    }

    public function removeConnection(InputConnection &$connection){
        if(in_array($this->owner->input, ['input', 'inputService']))
            unset($this->parents[$connection->getId()]);
        if(in_array($this->owner->input, ['output', 'outputService']))
            unset($this->childrens[$connection->getId()]);
    }

    public function checkIsValidConnection(Input &$input) {
        if($input->subState == BaseInput::SUBSTATE_BLOCKED || $this->owner->subState == BaseInput::SUBSTATE_BLOCKED)
            $this->error('Cannot connect to blocked input/output');
        if($this->owner->node->getId() == $input->node->getId())
            $this->error('Cannot connect to a input from same node');
        if($input->node->getId() == $this->owner->node->getId())
            $this->error('Cannot connect to itself');
        if(($this->owner->input === 'input' && $input->input === 'input') || ($this->owner->input === 'input' && $input->input === 'inputService') || ($this->owner->input === 'inputService' && $input->input === 'input'))
            $this->error('Cannot connect input to input');
        if(($this->owner->input === 'output' && $input->input === 'output') || ($this->owner->input === 'output' && $input->input === 'outputService') || ($this->owner->input === 'outputService' && $input->input === 'output'))
            $this->error('Cannot connect output to output');
        if($this->owner->input === 'outputService' && $input->input === 'outputService')
            $this->error('Cannot connect outputService to outputService');
        if($this->owner->input !== 'inputService' && $input->input === 'outputService')
            $this->error('Cannot connect outputService to input');
        if($this->owner->input === 'input' && $input->input === 'output')
            $this->error('Cannot connect output to input');

        if(!$this->checkValidConnectionType($input))
            $this->error('Invalid connection type');
    }

    public function checkValidConnectionType(BaseInput &$input){
        foreach($this->owner->types as $type)
        if($input->canAcceptType($type))
            return true;
    }

    public function activateInputs(){
        foreach($this->childrens as $k => $v){
            $this->childrens[$k]->setActive();
        }
    }

    public function error($message, $params = null){
        // throw an error by the DeskFlow owns catching error
        //throw new ConnectionException($this, $message, $params);
        die($message);
    }
}