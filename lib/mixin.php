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
                $this->mixin_methods[$method] = $class_name;
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
    
    public function super($arguments = null) {
        $caller = array_pop(array_slice(debug_backtrace(), 1, 1));
        echo $caller['function'];
    }
    
    protected function __call($method, $arguments) {
        if (isset($this->mixin_methods[$method])) {
            $arguments_count = count($arguments);
            $method_call = $this->mixin_methods[$method].'::'.$method.'(';
            if ($arguments_count) {
                $method_call .= '$arguments[0]';
                for ($i = 1; $i < $arguments_count; $i++) {
                    $method_call .= ', $arguments['.$i.']';
                }
            }
            $method_call .= ');';
            return eval($method_call);
        } else {
            trigger_error('Undefined method '.get_class($this).'::'.$method, E_USER_ERROR);
        }
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