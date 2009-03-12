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
            $this->call('send', 'initialize', $arguments);
        }
    }
    
    public function __destruct() {
        if ($this->respond_to('finalize')) $this->send('finalize');
    }
    
    public function call($method, $arguments) {
        $args = func_get_args();
        $method = array_shift($args);
        $arguments = array_pop($args);
        if (!is_array($arguments)) trigger_error('The last argument passed to '.get_class($this).'::call() must be an array', E_USER_ERROR);
        call_user_func_array(array($this, $method), array_merge($args, $arguments));
    }
    
    public function extend($arguments) {
        $arguments = func_get_args();
        call_user_func_array('extend', array_merge(array($this), $arguments));
    }
    
    public function is_a($class) {
        return $this instanceof $class;
    }
    
    public function respond_to($method) {
        return isset($this->instance_extended_methods[$method]) || in_array($method, get_class_methods(get_class($this)));
    }
    
    public function send($method, $arguments = null) {
        $arguments = func_get_args();
        $method = array_shift($arguments);
        if (!$this->respond_to($method)) {
            trigger_error('Undefined method '.get_class($this).'::'.$method.'()', E_USER_ERROR);
        } else if (isset($this->instance_extended_methods[$method]) && !empty($this->instance_extended_methods[$method])) {
            $object = array_pop($this->instance_extended_methods[$method]);
            $result = eval(build_static_method_call($method, $object, $arguments).';');
            $this->instance_extended_methods[$method][] = $object;
            return $result;
        } else {
            return $this->call($method, $arguments);
        }
    }
    
    public function super($arguments = null) {
        $arguments = func_get_args();
        $caller = array_pop(array_slice(debug_backtrace(), 1, 1));
        if (empty($caller)) {
            trigger_error(get_class($this).'::super() must be called from inside of an instance method', E_USER_ERROR);
        } else if ($this->respond_to($caller['function'])) {
            return $this->call('send', $caller['function'], $arguments);
        } else {
            return eval(build_static_method_call($caller['function'], 'parent', $arguments).';');
        }
    }
    
    protected function __call($method, $arguments = array()) {
        $this->call('send', $method, $arguments);
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
    
}

?>