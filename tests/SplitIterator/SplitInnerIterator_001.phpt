--TEST--
SplitIterator/SplitInnerIterator: Stop before vs. after condition TRUE
--FILE--
<?php
require_once("SplitIterator/SplitInnerIterator.php");

foreach(array(SplitInnerIterator::STOP_AFTER,
              SplitInnerIterator::STOP_BEFORE)
        as $when) {
  $inner   = new ArrayIterator(range(1, 10));
  $stopper = TestHelpers::pnewdebug("SplitInnerIterator",
                                    $inner,
                                    function ($k, $v) { return $v == 5; },
                                    $when);

  foreach($stopper as $value)
    print "  $value\n";
  print "Inner at (must be one more than last): ".$inner->current()."\n\n";
}
?>
--EXPECT--
new SplitInnerIterator(object(ArrayIterator), object(Closure), '0') returns object(SplitInnerIterator)
  1
  2
  3
  4
  5
Inner at (must be one more than last): 6

new SplitInnerIterator(object(ArrayIterator), object(Closure), '1') returns object(SplitInnerIterator)
  1
  2
  3
  4
Inner at (must be one more than last): 5
