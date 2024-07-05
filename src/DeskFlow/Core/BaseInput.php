<?php
namespace DeskFlow\Core;
use DeskFlow\Utils\Support\Utils;
use DeskFlow\Core\MessageProvider;

class BaseInput {
    public String $name;
    public $id;
    public $index; // the index of input/output on the node
    public $input; //can be, input, inputService(that can initiate a Service, can receive any data type), outputService(that can only connect to a inputService and only accept boolean)
    public $state;
    public $subState;
    public $node; // the owner node
    public MessageProvider $value; // valor que sera propagado para os outputs
    public $types = []; // tipos aceitos pelo input/output
    public Connection $connections;

    public $parents = [];
    public $childrens = [];
    public $max_parents = 0;
    public $max_children = 0;

    const STATE_INITIAL = 'initial'; //any - first state and stay hire one time ion runtime
    const STATE_READY = 'ready'; //any - is ready to active - usually the second state
    const STATE_ACTIVE = 'active'; //any - already executed
    const STATE_ERROR = 'error'; //any - If in this state, can only change to the finished state(Maybe)
    const STATE_RESETED = 'reseted'; //inputs - its like initial, but it means that it has been reseted
    const STATE_FINISHED = 'finished'; //any
    
    const SUBSTATE_FREE = 'FREE'; //if in this state can conncet to the other inputs
    const SUBSTATE_BLOCKED = 'blocked'; //if in this state can't conncet to the other inputs

    public function __construct(Int $index, Node &$node, Array $params = []) {
        $this->setIndex($index);
        $this->node =& $node;
        $this->connections = new Connection($this);
        $this->setId();
        $this->setState();
        $this->defineTypes();
        if(is_array($params)) {
            $this->setParams($params);
        }else{
            // throw an error by the DeskFlow owns catching error
            //throw new InputException($this, 'error setting parameters');
        }

        if($this->input == Input::TYPE)
            $node->addInput($this);
        elseif($this->input == Output::TYPE)
            $node->addOutput($this);
    }

    public function setId(){
        $this->id = uniqid();
    }

    public function getId(): string{
        return $this->id;
    }

    public function defineTypes(Array $types = []) {
        if(empty($types))
            return $this->defineTypes([MessageProvider::class]);

        foreach($types as $type){
            if(is_object($type))
                $type = get_class($type);
            if(!is_a((new $type), MessageProvider::class))
                $this->error("Invalid type: {$type}");

            $this->types[] = $type;
        }
        
        return $this->types;
    }

    public function setState($state = self::STATE_INITIAL) {
        $this->state = $state;
    }

    public function setParams(Array $params) {
        foreach ($params as $key => $value) {
            switch($key) {
                case 'value':
                    // if(!is_a($value, MessageProvider::class))
                    //     throw new InputException($this, 'error setting value parameter');
                    $this->setValue($value);
                    break;
                    case 'types':
                        $this->defineTypes($value);
                        break;
                    case 'index':
                        $this->setIndex($value);
                    case 'input_type':
                        $this->input = $value;
                        break;
                    case 'name':
                        $this->name = $value;
                        break;
                default:
                    // throw an error by the DeskFlow owns catching error
                    //throw new InputException('error setting parameters');
            }
        }
    }

    public function connect(Input &$input) {
        $this->connections->addConnection($input);
    }

    public function getNodeId() {
        return $this->node->getId();
    }

    public function disconnect(Output &$output) {
        
    }

    // passa o valor afrente (para o output)
    public function propagate(Output &$output = null) {
        // passa o valor a frente
       // $this->connections->setValue($this->getValue());
        // Se o input estiver ativo, ativa o output
        if ($this->isActive()) {
            if($output){
                $output->setActive();
            }else{
                //foreach ($this->connections->getConnections() as $connection) {
                //    $connection->output->activate();
                //}
            }
        }
    }

    public function receiveValue(MessageProvider &$var){

    }

    public function getValue() {
        return $this->value->getValue();
    }

    // valor caso pre definido
    public function setValue(MessageProvider &$value) {
        $this->value = $value;
    }

    public function checkActualState(){
        if($this->state == self::STATE_ERROR)
            $this->node->error($this);
    }

    // check if its can pass to the next state
    public function canGoToState(String $state){
        if($this->state == self::STATE_ERROR)
            return false;
    }

    public function setIndex($index){
        $this->index = $index;
    }

    public function getindex() {
        return $this->index;
    }

    public function setActive() {
        // regras para ativar um output
        if ($this->state !== self::STATE_ACTIVE) {
            $this->state = self::STATE_ACTIVE;
        }
    }

    public function isActive() {
        return $this->state === self::STATE_ACTIVE;
    }

    public function getState() {
        return $this->state;
    }

    // public function clone() {
    //     return new self($this->node);
    // }
    
    public function getType() {
        return $this->types;
    }

    public function addParent(InputConnection &$connection) {
        $this->connections->addParentConnection($connection);
    }

    public function addChildren(InputConnection &$connection) {
        $this->connections->addConnection($connection);
    }

    public function canAcceptType(MessageProvider|String $type) {
        if(is_string($type))
            return in_array($type, $this->types);
        
        return in_array(get_class($type), $this->types);
    }

    public function error($message) {
        // throw an error by the DeskFlow owns catching error
        //throw new InputException($this, $message);
        die($message);
    }
}