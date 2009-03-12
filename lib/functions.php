<?php

function extend($object, $classes) {
    if (!is_array($classes)) {
        $classes = func_get_args();
        $object = array_shift($classes);
    }
    
    foreach (array_unique($classes) as $class) {
        $class_name = (is_object($class)) ? get_class($class) : $class;
        if (!class_exists($class_name)) trigger_error('Undefined class '.$class_name, E_USER_ERROR);
        
        if (is_object($object)) {
            if (!in_array($class_name, $object->instance_extended_parents)) {
                // Mixin methods
                $methods = get_class_methods($class_name);
                foreach ($methods as $method) {
                    if (!isset($object->instance_extended_methods[$method])) $object->instance_extended_methods[$method] = array();
                    $object->instance_extended_methods[$method][] = $class_name;
                }
                
                // Mixin properties
                $properties = (is_object($class)) ? get_object_vars($class) : get_class_vars($class);
                foreach ($properties as $key => $value) {
                    $object->instance_extended_properties[$key] = $value;
                }
                
                $object->instance_extended_parents[] = $class_name;
            }
        } else {
            if (!in_array($class_name, get_static_property($object, 'extended_parents'))) {
                // Mixin methods
                foreach (get_class_methods($class_name) as $method) {
                    $extended_methods = get_static_property($object, 'extended_methods');
                    if (!isset($extended_methods[$method])) $extended_methods[$method] = array();
                    $extended_methods[$method][] = $class_name;
                    set_static_property($object, 'extended_methods', $extended_methods);
                }
                
                // Mixin properties
                $properties = (is_object($class)) ? get_object_vars($class) : get_class_vars($class);
                $extended_properties = get_static_property($object, 'extended_properties');
                foreach ($properties as $key => $value) {
                    $extended_properties[$key] = $value;
                }
                set_static_property($object, 'extended_properties', $extended_properties);
                
                set_static_property($object, 'extended_parents', array_merge(get_static_property($object, 'extended_parents'), array($class_name)));
            }
        }
    }
}

function build_static_method_call($method, $class, $arguments = array()) {
    if (is_object($class)) $class = get_class($class);
    $method_call = $class.'::'.$method.'(';
    if (!empty($arguments)) {
        $method_call .= '$arguments[0]';
        for ($i = 1; $i < count($arguments); $i++) {
            $method_call .= ', $arguments['.$i.']';
        }
    }
    return $method_call .= ')';
}

function get_static_property($class, $property) {
    return eval('return '.$class.'::$'.$property.';');
}

function set_static_property($class, $property, $value) {
    eval($class.'::$'.$property.' = $value;');
}

?>