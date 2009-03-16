<?php

class Object {
    
    static $extended_methods = array();
    static $extended_parents = array();
    static $extended_properties = array();
    public $class;
    public $instance_extended_methods;
    public $instance_extended_parents;
    public $instance_extended_properties;
    
    function __construct($arguments = null) {
        $this->class = get_class($this);
        $this->instance_extended_methods = self::$extended_methods;
        $this->instance_extended_parents = self::$extended_parents;
        $this->instance_extended_properties = self::$extended_properties;
        if ($this->respond_to('initialize')) {
            $arguments = func_get_args();
            $this->call('send', 'initialize', $arguments);
        }
    }
    
    function __destruct() {
        if ($this->respond_to('finalize')) $this->send('finalize');
    }
    
    function call($method, $arguments) {
        $args = func_get_args();
        $arguments = $this->extract_call_arguments($args);
        return call_user_func_array(array($this, $method), $arguments);
    }
    
    function call_extended_method($method_name, $arguments) {
        $args = func_get_args();
        $arguments = $this->extract_call_arguments($args);
        $callee = array_pop($this->instance_extended_methods[$method_name]);
        eval('$result = '.build_function_call($callee, $arguments).';');
        $this->instance_extended_methods[$method_name][] = $callee;
        return $result;
    }
    
    function inspect() {
        ob_start();
        print_r($this);
        return ob_get_clean();
    }
    
    function is_a($class) {
        return $this instanceof $class;
    }
    
    function method_extended($method) {
        return isset($this->instance_extended_methods[$method]);
    }
    
    function new_instance($arguments = null) {                      
        $arguments = func_get_args();
        return eval('return new '.build_function_call($this->class, $arguments).';');
    }
    
    function respond_to($method) {
        return isset($this->instance_extended_methods[$method]) || in_array($method, get_class_methods(get_class($this)));
    }
    
    function send($method, $arguments = null) {
        $arguments = func_get_args();
        $method = array_shift($arguments);
        if (!$this->respond_to($method)) {
            trigger_error('Undefined method '.get_class($this).'::'.$method.'()', E_USER_ERROR);
        } else if (isset($this->instance_extended_methods[$method]) && !empty($this->instance_extended_methods[$method])) {
            return $this->call_extended_method($method, $arguments);
        } else {
            return $this->call($method, $arguments);
        }
    }
    
    function super($arguments = null) {
        $arguments = func_get_args();
        $caller = array_pop(array_slice(debug_backtrace(), 1, 1));
        if (empty($caller)) {
            trigger_error(get_class($this).'::super() must be called from inside of an instance method', E_USER_ERROR);
        } else if ($this->respond_to($caller['function']) && $caller['class'] != get_class($this)) {
            return $this->call('send', $caller['function'], $arguments);
        } else {
            return eval('return '.build_function_call(array(get_parent_class($this), $caller['function']), $arguments).';');
        }
    }
    
    protected function __call($method, $arguments = array()) {
        return $this->call('send', $method, $arguments);
    }
    
    protected function __get($key) {
        if (isset($this->instance_extended_properties[$key])) {
            return $this->instance_extended_properties[$key];
        } else {
            trigger_error('Undefined property $'.$key, E_USER_ERROR);
        }
    }
    
    protected function __isset($key) {
        return isset($this->instance_extended_properties[$key]);
    }
    
    protected function __set($key, $value) {
        if (isset($this->instance_extended_properties[$key])) $this->instance_extended_properties[$key] = $value;
    }
    
    protected function __unset($key) {
        unset($this->instance_extended_properties[$key]);
    }
    
    protected function extract_call_arguments($args) {
        array_shift($args);
        $arguments = array_pop($args);
        if (!is_array($arguments)) trigger_error('The last argument in '.get_class($this).'::extract_call_arguments() must be an array', E_USER_ERROR);
        return array_merge($args, $arguments);
    }
    
}

?>