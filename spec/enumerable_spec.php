<?php
require 'spec_helper.php';

class Describe_Enumerable extends SimpleSpec {
    
    function should_provide_native_array_interface() {
        $e = new Enumerable;
        
        #push
        $e[] = 'ing';
        $e[] = 'cool';
        $e[] = 'wow';

        expects( $e->to_a() )->should_be(array('ing', 'cool', 'wow'));
        
        $e['name'] = 'taylor luk';
        
        expects($e['name'])->should_be('taylor luk');
    }
    
    function should_be_iteratable_with_foreach() {
        $e = new Enumerable;
        $e[] = 'ing';
        $e[] = 'cool';
        $e[] = 'wow';
        $results = array();
        
        foreach ($e as $k => $v) {
            $results[] = $v;
        }
        
        expects($results)->should_be(array('ing', 'cool', 'wow'));
    }
    
    function should_provide_functional_iterators() {
        $e = new Enumerable;
        $e[] = 'ing';
        $e[] = 'cool';
        $e[] = 'wow';
        
        expects($e->any('$key == "ing"'))->should_be(true);
        expects($e->any('$key == "invalid"'))->should_be(false);
    }
}

