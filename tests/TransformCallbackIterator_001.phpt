--TEST--
TransformCallbackIterator: Basic use case
--FILE--
<?php
require_once("TransformCallbackIterator.php");

$innerIterator = new ArrayIterator(array('a' => "one",
                                         'B' => "two",
                                         'c' => "three"));

function strtoupperIfKeyIsUpper($value, $key, $iterator) {
  if(preg_match('/^[A-Z]/', $key))
    return strtoupper($value);
  return $value;
}

foreach(new TransformCallbackIterator($innerIterator, 'strtoupperIfKeyIsUpper')
        as $newValue)
  print "$newValue\n";

?>
--EXPECT--
one
TWO
three
