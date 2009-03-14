<?php

require_once '../phuby.php';

$e = new Enumerable;

$e[] = 'ing';
$e[] = 'cool';
$e[] = 'wow';

foreach ($e as $k => $v) {
    echo $k.' => '.$v."\n";
}

echo "***COLLECT***\n";
print_r($e->collect('return $key;')->to_a());

if ($e->any('return $key == 1;')) echo "true\n";
if ($e->any('return $key == 4;')) echo "true\n";

echo "***INJECT***\n";
print_r($e->inject(array(), '$object["injected_$key"] = $value; return $object;'));

$e = new Enumerable;
$e['short'] = 4;
$e['this is a longer one'] = 12;
$e['this is long'] = 2;

echo "***ARRAY***\n";
print_r($e->to_a());

echo "***SORT***\n";
print_r($e->sort()->to_a());

echo "***SORT_BY***\n";
print_r($e->sort_by('return strlen($key);')->to_a());

?>