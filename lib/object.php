<?php

class Object {
    
    protected $included_methods = array();
    protected $included_parents = array();
    protected $included_properties = array();
    
    public function __construct($arguments = null) {
        if ($this->respond_to('initialize')) {
            $arguments = func_get_args();
            $this->send('initialize', $arguments);
        }
    }
    
    public function __destruct() {
        if ($this->respond_to('finalize')) $this->send('finalize');
    }
    
    public function mixin($args) {
        $classes = array();
        foreach (func_get_args() as $arg) $classes = array_merge($classes, ((is_array($arg) ? $arg : array($arg))));
        
        foreach (array_unique($classes) as $class) {
            $class_name = (is_object($class)) ? get_class($class) : $class;
            if (!class_exists($class_name)) trigger_error('Undefined class '.$class_name, E_USER_ERROR);
            
            // Mixin methods
            $methods = get_class_methods($class_name);
            foreach ($methods as $method) {
                if (!isset($this->included_methods[$method])) $this->included_methods[$method] = array();
                $this->included_methods[$method][] = $class_name;
            }
            
            // Mixin properties
            $properties = (is_object($class)) ? get_object_vars($class) : get_class_vars($class);
            foreach ($properties as $key => $value) {
                $this->included_properties[$key] = $value;
            }
            
            if (!in_array($class_name, $this->included_parents)) $this->included_parents[] = $class_name;
        }
    }
    
    public function included_methods() {
        return $this->included_methods;
    }
    
    public function included_parents() {
        return $this->included_parents;
    }
    
    public function included_properties() {
        return $this->included_properties;
    }
    
    public function respond_to($method) {
        return isset($this->included_methods[$method]) || in_array($method, get_class_methods(get_class($this)));
    }
    
    public function send($method, $arguments = array()) {
        if (!$this->respond_to($method)) {
            trigger_error('Undefined method '.get_class($this).'::'.$method, E_USER_ERROR);
        } else if (isset($this->included_methods[$method]) && !empty($this->included_methods[$method])) {
            $object = array_pop($this->included_methods[$method]);
            $result = eval($this->build_method_call($method, $object, $arguments));
            $this->included_methods[$method][] = $object;
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
        if (isset($this->included_properties[$key])) {
            return $this->included_properties[$key];
        } else {
            trigger_error('Undefined property $'.$key, E_USER_ERROR);
        }
    }
    
    protected function __isset($key) {
        return isset($this->included_properties[$key]);
    }
    
    protected function __set($key, $value) {
        $this->included_properties[$key] = $value;
    }
    
    protected function __unset($key) {
        unset($this->included_properties[$key]);
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