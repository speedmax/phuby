<?php

class Delegator extends Object { }

abstract class DelegatorMethods {
    
    function delegate($delegated_methods) {
        $delegated_methods = func_get_args();
        $receiver = array_pop($delegated_methods);
        if (empty($delegated_methods)) {
            trigger_error('The last argument to delegate() must be an object or a string representing a class or method', E_USER_ERROR);
        } else {
            $class = get_called_class();
            $methods = &call_class_method($class, 'methods');
            foreach ($delegated_methods as $delegated_method) {
                if (!isset($methods[$delegated_method])) $methods[$delegated_method] = array();
                array_unshift($methods[$delegated_method], array($receiver, $delegated_method));
            }
        }
    }
    
}

Delegator::extend('DelegatorMethods');