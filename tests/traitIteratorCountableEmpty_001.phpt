--TEST--
traitIteratorCountableEmpty: Basic usage
--FILE--
<?php
require_once("traitIteratorCountableEmpty.php");
require_once("test_inc/TestHelpers.php");

class TestIteratorCountableEmpty implements Iterator, Countable {
  use traitIteratorCountableEmpty;
}

$empty = TestHelpers::pnewdebug("TestIteratorCountableEmpty");
TestHelpers::pcatchdebug($empty, "count");
$count = 0;
foreach($empty as $entry)
  $count++;
printf("foreach loop count: %d\n", $count);
?>
--EXPECT--
new TestIteratorCountableEmpty() returns object(TestIteratorCountableEmpty)
TestIteratorCountableEmpty->count() returns '0'
foreach loop count: 0
