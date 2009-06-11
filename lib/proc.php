<?php

class Proc extends Object { }

abstract class ProcMethods {
    
    public $binding = array();
    public $block;
    public $parameters;
    
    function initialize($block) {
        $parameters = func_get_args();
        $this->block = array_pop($parameters);
        $this->parameters = $parameters;
    }
    
    function call($values = null) {
        $values = func_get_args();
        return $this->call_array($values);
    }
    
    function call_array($values = array()) {
        $arguments = array();
        foreach ($this->parameters as $index => $parameter) {
            $parameter_name = (is_array($parameter)) ? $parameter[0] : $parameter;
            if (isset($values[$index])) {
                $arguments[$parameter_name] = $values[$index];
            } elseif (is_array($parameter)) {
                $arguments[$parameter_name] = $parameter[1];
            } else {
                trigger_error('Missing argument '.($index + 1).' in Proc::call()', E_USER_WARNING);
            }
        }
        unset($index);
        unset($parameter);
        unset($parameter_name);
        unset($values);
        extract($this->binding, EXTR_REFS);
        extract($arguments);
        return eval($this->block);
    }
    
}

Proc::extend('ProcMethods');