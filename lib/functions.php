<?php

function alias_method($object, $new_name, $old_name) {
    extend_method($object, $object, $old_name, $new_name);
}

function alias_method_chain($object, $method, $with) {
    $without = $method.'_without_'.$with;
    if (is_object($object)) {
        $object->instance_extended_methods[$without] = $object->instance_extended_methods[$method];
        unset($object->instance_extended_methods[$method]);
    } else {
        $extended_methods = get_static_property($object, 'extended_methods');
        $extended_methods[$without] = $extended_methods[$method];
        unset($extended_methods[$method]);
        set_static_property($object, 'extended_methods', $extended_methods);
    }
    extend_method($object, $object, $method.'_with_'.$with, $method);
}

function build_function_call($function, $arguments = array(), $variable_name = 'arguments') {
    if (!is_array($function)) $function = array($function);
    if (is_object($function[0])) $function[0] = get_class($function[0]);
    $function_call = join('::', $function).'(';
    if (!empty($arguments)) {
        $function_call .= '$'.$variable_name.'[0]';
        for ($i = 1; $i < count($arguments); $i++) {
            $function_call .= ', $'.$variable_name.'['.$i.']';
        }
    }
    return $function_call .= ')';
}

function evaluate_block($block, $arguments = array()) {
    if (isset($arguments['this'])) {
        $arguments['self'] = $arguments['this'];
        unset($arguments['this']);
    }
    unset($arguments['block']);
    extract($arguments);
    return eval($block);
}

function extend($object, $classes) {
    if (!is_array($classes)) {
        $classes = func_get_args();
        $object = array_shift($classes);
    }
    
    foreach (array_unique($classes) as $class) {
        $class_name = (is_object($class)) ? get_class($class) : $class;
        if (!class_exists($class_name)) trigger_error('Undefined class '.$class_name, E_USER_ERROR);
        
        $methods = get_class_methods($class_name);
        foreach ($methods as $method) extend_method($object, $class, $method);
        
        $properties = (is_object($class)) ? get_object_vars($class) : get_class_vars($class);
        foreach ($properties as $property => $value) extend_property($object, $property, $value);
        
        if (is_object($object)) {
            $object->instance_extended_parents[] = $class_name;
        } else {
            set_static_property($object, 'extended_parents', array_merge(get_static_property($object, 'extended_parents'), array($class_name)));
        }
        
        $arguments = array($object);
        if (method_exists($class_name, 'extended')) eval(build_function_call(array($class_name, 'extended'), $arguments).';');
    }
}

function extend_method($object, $class, $method, $method_name = null) {
    $class_name = (is_object($class)) ? get_class($class) : $class;
    if (is_null($method_name)) $method_name = $method;
    if (is_object($object)) {
        if (!isset($object->instance_extended_methods[$method_name])) $object->instance_extended_methods[$method_name] = array();
        $object->instance_extended_methods[$method_name][] = array($class_name, $method);
    } else {
        $extended_methods = get_static_property($object, 'extended_methods');
        if (!isset($extended_methods[$method_name])) $extended_methods[$method_name] = array();
        $extended_methods[$method_name][] = array($class_name, $method);
        set_static_property($object, 'extended_methods', $extended_methods);
    }
}

function extend_property($object, $property, $value) {
    if (is_object($object)) {
        $object->instance_extended_properties[$property] = $value;
    } else {
        $class_name = (is_object($object)) ? get_class($object) : $object;
        set_static_property($class_name, 'extended_properties', array_merge(get_static_property($class_name, 'extended_properties'), array($property => $value)));
    }
}

function get_static_property($class, $property) {
    return eval('return '.$class.'::$'.$property.';');
}

function set_static_property($class, $property, $value) {
    eval($class.'::$'.$property.' = $value;');
}

?>