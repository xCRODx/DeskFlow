<?php

namespace DeskFlow\Core;
use DeskFlow\Core\Node;
use DeskFlow\Interfaces\ServiceInterface;
use DeskFlow\Utils\Support\ServiceMetadata;

class Service implements ServiceInterface {
    public $type;
    public $id;
    public $originalParams;
    public $node;
    public ServiceMetadata $metadata;
    public $params = [];
    public $requiredInputs;
    public $activationCondition;
    const STATE_INITIAL = 'initial';
    const STATE_ACTIVE = 'active';
    const STATE_INACTIVE = 'inactive';
    const STATE_ERROR = 'error';
    const STATE_RESETED = 'reseted';
    const STATE_FINISHED = 'finished';

    /**
     * When implement a Service object, use 
     * $metadata = new ServiceMetadata($params);
     * parent::__construct($metadata);
     */
    public function __construct(ServiceMetadata $metadata) {
        $this->metadata = $metadata;
    }
    
    public function init(Node &$node = null, Array $params = []) {
        $this->originalParams = $params;
        $this->setId();
        $this->node =& $node;
        $this->setParams($params);
        $this->node->name = $this->metadata->getName();
        //parent::__construct($father);
        // $this->type = $type;
        // $this->params = $params;
        // $this->requiredInputs = $requiredInputs;
        // $this->activationCondition = $activationCondition;
    }

    public function setId(){
        $this->id = uniqid();
    }
    
    public function getId() :String {
        return $this->id;
    }

    public function setParams(Array $params){
        $this->onSetParams($params);
        $this->afterSetParams();
    }

    public function onSetParams(Array $params) {
        $this->params = $params;
    }

    public function isNodeActive(){
        return $this->node->isActive();
    }

    public function resetInput(BaseInput &$input) {
        $input->setState(BaseInput::STATE_RESETED);
    }
    
    public function afterSetParams() {}

    protected function execute() {
        $this->onExecute();
        // Lógica de execução do serviço
        $this->log("Executing {$this->type} service with params: " . json_encode($this->params) . "\n");
        $this->afterExecute();
    }

    public function onExecute() {}

    public function afterExecute() {}

    public function canActivate() {
        return true;
    }

    public function getRequiredInputs() {
        return $this->requiredInputs;
    }

    public function resetParams($params = null) {
        if(!is_array($params))
            $params = $this->originalParams;
        $this->setParams($params);
    }

    private function log($message) {
        echo $message; // Implementar método para gravar log
    }
}