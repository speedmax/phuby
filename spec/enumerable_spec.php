<?php
require 'spec_helper.php';

class Describe_Enumerable extends SimpleSpec {
    
    function should_provide_native_array_interface() {
        $e = new A;
        
        #push
        $e[] = 'ing';
        $e[] = 'cool';
        $e[] = 'wow';

        expects( $e->array )->should_be(array('ing', 'cool', 'wow'));
    }
    
    function should_be_iteratable_with_foreach() {
        $e = new A;
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
        $e = new A;
        $e[] = 'ing';
        $e[] = 'cool';
        $e[] = 'wow';
        
        expects($e->any('return $value == "ing";'))->should_be(true);
        expects($e->any('return $value == "invalid";'))->should_be(false);
    }
}

