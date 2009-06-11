<?php

class Object extends Module {
    
    public $class;
    public $instance_variables;
    
    function __construct($arguments = null) {
        $this->class = get_class($this);
        $this->instance_variables = call_class_method($this->class, 'properties');
        if ($this->respond_to('initialize')) {
            $arguments = func_get_args();
            $this->send_array('initialize', $arguments);
        }
    }
    
    function __destruct() {
        if ($this->respond_to('finalize')) $this->send('finalize');
    }
    
    function respond_to($method) {
        $methods = call_class_method($this->class, 'methods');
        return in_array($method, get_class_methods($this->class)) || (in_array($method, array_keys($methods)) && !empty($methods[$method]));
    }
    
    function &send($method, $arguments = null) {
        $arguments = func_get_args();
        $method = array_shift($arguments);
        $result = &$this->send_array($method, $arguments);
        return $result;
    }
    
    function &send_array($method, $arguments = array()) {
        $methods = &call_class_method($this->class, 'methods');
        if (!$this->respond_to($method)) {
            trigger_error('Undefined method '.$this->class.'::'.$method.'()', E_USER_ERROR);
        } else if (!isset($methods[$method]) || empty($methods[$method])) {
            $result = call_user_func_array(array($this, $method), $arguments);
            return $result;
        } else {
            eval('$result = &'.build_function_call($methods[$method][0], $arguments).';');
            return $result;
        }
    }
    
    function &super($arguments = null) {
        $arguments = func_get_args();
        $caller = array_pop(array_slice(debug_backtrace(), 1, 1));
        if (empty($caller)) {
            trigger_error($this->class.'::super() must be called from inside of an instance method', E_USER_ERROR);
        } else {
            $methods = &call_class_method($this->class, 'methods');
            $aliases = call_class_method($this->class, 'aliases');
            $method = $caller['function'];
            foreach (array_reverse($aliases) as $alias) {
                if ($alias[1] == $method) {
                    $method = $alias[0];
                    break;
                }
            }
            if (isset($methods[$method]) && !empty($methods[$method])) {
                $callee = array_shift($methods[$method]);
                $result = &$this->send_array($method, $arguments);
                array_unshift($methods[$method], $callee);
            } else {
                eval('$result = &'.build_function_call(array(get_parent_class($this), $method), $arguments).';');
            }
            return $result;
        }
    }
    
    protected function &__call($method, $arguments = array()) {
        $result = &$this->send_array('method_missing', array($method, $arguments));
        return $result;
    }
    
    protected function __clone() {
        $this->send_array('cloned');
    }
    
    protected function &__get($property) {
        if (isset($this->$property)) {
            return $this->instance_variables[$property];
        } else {
            $this->instance_variables = array_merge(call_class_method($this->class, 'properties'), $this->instance_variables);
            if (isset($this->instance_variables[$property])) {
                return $this->instance_variables[$property];
            } else {
                trigger_error('Undefined property $'.$property, E_USER_ERROR);
            }
        }
    }
    
    protected function __isset($property) {
        return isset($this->instance_variables[$property]);
    }
    
    protected function __set($property, $value) {
        $this->instance_variables[$property] = $value;
    }
    
    protected function __unset($property) {
        unset($this->instance_variables[$property]);
    }
    
}

abstract class ObjectMethods {
    
    function cloned() { }
    
    function dup() {
        return clone $this;
    }
    
    function inspect() {
        ob_start();
        print_r($this);
        return ob_get_clean();
    }
    
    function instance_variables() {
        return $this->instance_variables;
    }
    
    function is_a($class) {
        return $this instanceof $class;
    }
    
    function &method_missing($method, $arguments = array()) {
        $result = &$this->send_array($method, $arguments);
        return $result;
    }
    
}

Object::extend('ObjectMethods');

Object::alias_method('is_an', 'is_a');