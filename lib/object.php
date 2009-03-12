<?php

class Object {
    
    protected $extended_methods = array();
    protected $extended_parents = array();
    protected $extended_properties = array();
    
    public function __construct($arguments = null) {
        $this->extend('Callback');
        if ($this->respond_to('initialize')) {
            $arguments = func_get_args();
            $this->send('initialize', $arguments);
        }
    }
    
    public function __destruct() {
        if ($this->respond_to('finalize')) $this->send('finalize');
    }
    
    public function extend($args) {
        $classes = array();
        foreach (func_get_args() as $arg) $classes = array_merge($classes, ((is_array($arg) ? $arg : array($arg))));
        
        foreach (array_unique($classes) as $class) {
            $class_name = (is_object($class)) ? get_class($class) : $class;
            if (!class_exists($class_name)) trigger_error('Undefined class '.$class_name, E_USER_ERROR);
                
            if (!in_array($class_name, $this->extended_parents)) {
                // Mixin methods
                $methods = get_class_methods($class_name);
                foreach ($methods as $method) {                
                    if (!isset($this->extended_methods[$method])) $this->extended_methods[$method] = array();
                    $this->extended_methods[$method][] = $class_name;
                }
            
                // Mixin properties
                $properties = (is_object($class)) ? get_object_vars($class) : get_class_vars($class);
                foreach ($properties as $key => $value) {
                    $this->extended_properties[$key] = $value;
                }
                
                $this->extended_parents[] = $class_name;
                if (in_array('extended', $methods)) eval($this->build_method_call('extended', $class_name));
            }
        }
    }
    
    public function extended_methods() {
        return $this->extended_methods;
    }
    
    public function extended_parents() {
        return $this->extended_parents;
    }
    
    public function extended_properties() {
        return $this->extended_properties;
    }
    
    public function is_a($class) {
        return $this instanceof $class;
    }
    
    public function respond_to($method) {
        return isset($this->extended_methods[$method]) || in_array($method, get_class_methods(get_class($this)));
    }
    
    public function send($method, $arguments = array()) {
        if (!$this->respond_to($method)) {
            trigger_error('Undefined method '.get_class($this).'::'.$method, E_USER_ERROR);
        } else if (isset($this->extended_methods[$method]) && !empty($this->extended_methods[$method])) {
            $object = array_pop($this->extended_methods[$method]);
            $result = eval($this->build_method_call($method, $object, $arguments));
            $this->extended_methods[$method][] = $object;
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
        if (isset($this->extended_properties[$key])) {
            return $this->extended_properties[$key];
        } else {
            trigger_error('Undefined property $'.$key, E_USER_ERROR);
        }
    }
    
    protected function __isset($key) {
        return isset($this->extended_properties[$key]);
    }
    
    protected function __set($key, $value) {
        $this->extended_properties[$key] = $value;
    }
    
    protected function __unset($key) {
        unset($this->extended_properties[$key]);
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

?>