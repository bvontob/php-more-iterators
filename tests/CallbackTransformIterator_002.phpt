--TEST--
CallbackTransformIterator: Transforming both keys and values
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

function ordOfKey($value, $key, $iterator) {
  return ord($key);
}

foreach(new CallbackTransformIterator($innerIterator,
                                      'strtoupperIfKeyIsUpper',
                                      'ordOfKey')
        as $newKey => $newValue)
  print "$newKey => $newValue\n";

?>
--EXPECT--
97 => one
66 => TWO
99 => three
