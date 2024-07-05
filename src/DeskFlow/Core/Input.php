<?php
namespace DeskFlow\Core;
class Input extends BaseInput {
    const TYPE = 'input';
    function __construct(Int $index, Node &$node, array $params = []) {
        if(!isset($params['input_type']))
            $this->input = 'input';
        parent::__construct($index, $node, $params);
    }
}