--TEST--
SplitIterator: Early break out of inner "split-off" iteration
--FILE--
<?php
require_once("SplitIterator.php");
require_once("test_inc/SplitIterator_inc.php");

$splitter = TestHelpers::pnewdebug("SplitIfDivisibleIterator",
                                   new ArrayIterator(range(1, 20)),
                                   3);
foreach($splitter as $split) {
  print "NEW SPLIT-OFF ITERATOR:\n";
  foreach($split as $item) {
    print "  ITEM: $item\n";
    if($item % 4 == 0) { // Break at just some points
      print "  BREAK\n";
      break;
    }
  }
}

?>
--EXPECT--
new SplitIfDivisibleIterator(object(ArrayIterator), '3') returns object(SplitIfDivisibleIterator)
NEW SPLIT-OFF ITERATOR:
  ITEM: 1
  ITEM: 2
  ITEM: 3
NEW SPLIT-OFF ITERATOR:
  ITEM: 4
  BREAK
NEW SPLIT-OFF ITERATOR:
  ITEM: 7
  ITEM: 8
  BREAK
NEW SPLIT-OFF ITERATOR:
  ITEM: 10
  ITEM: 11
  ITEM: 12
  BREAK
NEW SPLIT-OFF ITERATOR:
  ITEM: 13
  ITEM: 14
  ITEM: 15
NEW SPLIT-OFF ITERATOR:
  ITEM: 16
  BREAK
NEW SPLIT-OFF ITERATOR:
  ITEM: 19
  ITEM: 20
  BREAK