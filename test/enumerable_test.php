<?php

require_once '../phuby.php';

$e = new Enumerable;

$e[] = 'ing';
$e[] = 'cool';
$e[] = 'wow';

foreach ($e as $k => $v) {
    echo $k.' => '.$v."\n";
}
// echo $e['test'];

if ($e->any('return $key == "ing";')) echo "true\n";
if ($e->any('return $key == "invalid";')) echo "true\n";

print_r($e->inject(array(), '$object["injected_$key"] = $value; return $object;'));

?>