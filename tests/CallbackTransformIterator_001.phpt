--TEST--
CallbackTransformIterator: Basic use case
--FILE--
<?php
require_once("CallbackTransformIterator.php");

$innerIterator = new ArrayIterator(array('a' => "one",
                                         'B' => "two",
                                         'c' => "three"));

function strtoupperIfKeyIsUpper($value, $key, $iterator) {
  if(preg_match('/^[A-Z]/', $key))
    return strtoupper($value);
  return $value;
}

foreach(new CallbackTransformIterator($innerIterator, 'strtoupperIfKeyIsUpper')
        as $newKey => $newValue)
  print "$newKey => $newValue\n";

?>
--EXPECT--
a => one
B => TWO
c => three
