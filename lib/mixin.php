<?php

class Mixin {
    
    protected $mixin_methods = array();
    protected $mixin_parents = array();
    protected $mixin_properties = array();
    
    public function mixin($args) {
        $classes = array();
        foreach (func_get_args() as $arg) $classes = array_merge($classes, ((is_array($arg) ? $arg : array($arg))));
        
        foreach (array_unique($classes) as $class) {
            $class_name = (is_object($class)) ? get_class($class) : $class;
            if (!class_exists($class_name)) trigger_error('Undefined class '.$class_name, E_USER_ERROR);
            
            // Mixin methods
            $methods = get_class_methods($class_name);
            foreach ($methods as $method) {
                if (!isset($this->mixin_methods[$method])) $this->mixin_methods[$method] = array();
                $this->mixin_methods[$method][] = $class_name;
            }
            
            // Mixin properties
            $properties = (is_object($class)) ? get_object_vars($class) : get_class_vars($class);
            foreach ($properties as $key => $value) {
                $this->mixin_properties[$key] = $value;
            }
            
            if (!in_array($class_name, $this->mixin_parents)) $this->mixin_parents[] = $class_name;
        }
    }
    
    public function mixin_methods() {
        return $this->mixin_methods;
    }
    
    public function mixin_parents() {
        return $this->mixin_parents;
    }
    
    public function mixin_properties() {
        return $this->mixin_properties;
    }
    
    public function respond_to($method) {
        return isset($this->mixin_methods[$method]) || in_array($method, get_class_methods(get_class($this)));
    }
    
    public function send($method, $arguments = array()) {
        if (!$this->respond_to($method)) {
            trigger_error('Undefined method '.get_class($this).'::'.$method, E_USER_ERROR);
        } else if (isset($this->mixin_methods[$method])) {
            $method_call = $this->mixin_methods[$method][count($this->mixin_methods[$method]) - 1].'::'.$method.'(';
            if (count($arguments)) {
                $method_call .= '$arguments[0]';
                for ($i = 1; $i < count($arguments); $i++) {
                    $method_call .= ', $arguments['.$i.']';
                }
            }
            $method_call .= ');';
            return eval($method_call);
        } else {
            return call_user_func_array(array($this, $method), $arguments);
        }
    }
    
    public function super($arguments = null) {
        $caller = array_pop(array_slice(debug_backtrace(), 1, 1));
        echo $caller['function'];
    }
    
    protected function __call($method, $arguments = array()) {
        $this->send($method, $arguments);
    }
    
    protected function __get($key) {
        if (isset($this->mixin_properties[$key])) {
            return $this->mixin_properties[$key];
        } else {
            trigger_error('Undefined property $'.$key, E_USER_ERROR);
        }
    }
    
    protected function __isset($key) {
        return isset($this->mixin_properties[$key]);
    }
    
    protected function __set($key, $value) {
        $this->mixin_properties[$key] = $value;
    }
    
    protected function __unset($key) {
        unset($this->mixin_properties[$key]);
    }
    
}

?>