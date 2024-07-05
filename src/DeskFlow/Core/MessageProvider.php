<?php
namespace DeskFlow\Core;
//Manage variables values
class MessageProvider {
    public $value;

    public function __construct($value = null){
        $this->value = $value;
    }

    public function setValue($val){
        $this->onSetValue($val);
        $this->value = $val;
        $this->afterSetValue();
    }

    public function onSetValue($val){}

    public function afterSetValue(){}

    public function getValue(){
        return $this;
    }

    public function getValueContent(){
        return $this->value;
    }

    public function resetValue(){
        $this->setValue(null);
    }

    public function isNull(){
        return is_null($this->value);
    }

    public function isNotnull(){
        return !$this->isNull();
    }

    public function isEqual($val){
        return $this->value === $val;
    }
    
    public function isNotEqual($val){
        return!$this->isEqual($val);
    }

    public function isTrue(){
        return $this->value === true;
    }

    public function isFalse(){
        return $this->value === false;
    }

    public function isZero(){
        return $this->value === 0;
    }

    public function isNotZero(){
        return!$this->isZero();
    }
}