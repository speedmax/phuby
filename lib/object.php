<?php

class Object {
    
    public static $extended_methods = array();
    public static $extended_parents = array();
    public static $extended_properties = array();
    public $instance_extended_methods = array();
    public $instance_extended_parents = array();
    public $instance_extended_properties = array();
    
    public function __construct($arguments = null) {
        $this->instance_extended_methods = self::$extended_methods;
        $this->instance_extended_parents = self::$extended_parents;
        $this->instance_extended_properties = self::$extended_properties;
        if ($this->respond_to('initialize')) {
            $arguments = func_get_args();
            $this->send('initialize', $arguments);
        }
    }
    
    public function __destruct() {
        if ($this->respond_to('finalize')) $this->send('finalize');
    }
    
    public function extend($args) {
        $args = func_get_args();
        call_user_func_array('extend', array_merge(array($this), $args));
    }
    
    public function instance_extended_methods() {
        return $this->instance_extended_methods;
    }
    
    public function instance_extended_parents() {
        return $this->instance_extended_parents;
    }
    
    public function instance_extended_properties() {
        return $this->instance_extended_properties;
    }
    
    public function is_a($class) {
        return $this instanceof $class;
    }
    
    public function respond_to($method) {
        return isset($this->instance_extended_methods[$method]) || in_array($method, get_class_methods(get_class($this)));
    }
    
    public function send($method, $arguments = array()) {
        if (!$this->respond_to($method)) {
            trigger_error('Undefined method '.get_class($this).'::'.$method, E_USER_ERROR);
        } else if (isset($this->instance_extended_methods[$method]) && !empty($this->instance_extended_methods[$method])) {
            $object = array_pop($this->instance_extended_methods[$method]);
            $result = eval($this->build_method_call($method, $object, $arguments));
            $this->instance_extended_methods[$method][] = $object;
            return $result;
        } else {
            return call_user_func_array(array($this, $method), $arguments);
        }
    }
    
    public function super($arguments = null) {
        $caller = array_pop(array_slice(debug_backtrace(), 1, 1));
        $arguments = func_get_args();
        if ($this->respond_to($caller['function'])) {
            return $this->send($caller['function'], $arguments);
        } else {
            return eval($this->build_method_call($caller['function'], 'parent', $arguments));
        }
    }
    
    protected function __call($method, $arguments = array()) {
        $this->send($method, $arguments);
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
        $this->instance_extended_properties[$key] = $value;
    }
    
    protected function __unset($key) {
        unset($this->instance_extended_properties[$key]);
    }
    
    protected function build_method_call($method, $class = null, $arguments = array()) {
        if (is_null($class)) $class = get_class($this);
        if (is_object($class)) $class = get_class($class);
        
        $method_call = $class.'::'.$method.'(';
        if (!empty($arguments)) {
            $method_call .= '$arguments[0]';
            for ($i = 1; $i < count($arguments); $i++) {
                $method_call .= ', $arguments['.$i.']';
            }
        }
        return $method_call .= ');';
    }
    
}

extend('Object', 'Callback');

?>