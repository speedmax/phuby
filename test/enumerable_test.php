<?php

require_once '../phuby.php';

$e = new A;

$e[] = 'ing';
$e[] = 'cool';
$e[] = 'wow';

echo $e[99];
$e->default = 'TEST DEFAULT';
echo $e[99]."\n";

foreach ($e as $k => $v) {
    echo $k.' => '.$v."\n";
}

echo "***COLLECT***\n";
print_r($e->collect('return $key;')->array);

echo "***ALIAS METHOD MAP***\n";
print_r($e->map('return $key;')->array);

if ($e->any('return $key == 1;')) echo "true\n";
if ($e->any('return $key == 4;')) echo "true\n";

echo "***INJECT***\n";
print_r($e->inject(array(), '$object["injected_$key"] = $value; return $object;'));

$e = new H;
$e['short'] = 4;
$e['this is a longer one'] = 12;
$e['this is long'] = 2;

echo "***ARRAY***\n";
print_r($e->array);

echo "***SORT***\n";
print_r($e->sort()->array);

echo "***SORT_BY***\n";
print_r($e->sort_by('return strlen($key);')->array);

echo "***FLATTEN***\n";
$e = new A(array(1, 2, 3, new A(array(4, 5, 6, new A(array(7, 8, 9))))));
print_r($e->flatten()->array);

echo "***CHUNK***\n";
$e = new A(array(1,2,3,4,5,6,7,8));
print_r($e->chunk(3)->to_native_a());

?>