--TEST--
SplitIterator: Invalidation of inner "split-off" iterators
--FILE--
<?php
require_once("SplitIterator.php");
require_once("test_inc/SplitIterator_inc.php");

$splitter = TestHelpers::pnewdebug("SplitIfDivisibleIterator",
                                   new ArrayIterator(range(1, 10)),
                                   3);
$splits = array();

foreach($splitter as $key =>$split) {
  printf("RETRIEVED %d => %s\n", $key, get_class($split));
  $splits[$key] = $split;
  checkall($splits);
}

print "END OF LOOP, ALL SHOULD BE INVALID NOW:\n";
checkall($splits);

function checkall($splits) {
  print "  CHECKING ALL INNER ITERATORS RETRIEVED SO FAR:\n";
  foreach($splits as $key => $split) {
    printf("    %d => %s:\n".
           "      %s\n".
           "      %s\n".
           "      %s\n",
           $key, get_class($split),
           TestHelpers::catchdebug($split, "valid"),
           TestHelpers::catchdebug($split, "key"),
           TestHelpers::catchdebug($split, "current"));
  }
}
?>
--EXPECT--
new SplitIfDivisibleIterator(object(ArrayIterator), '3') returns object(SplitIfDivisibleIterator)
RETRIEVED 0 => SplitInnerIterator
  CHECKING ALL INNER ITERATORS RETRIEVED SO FAR:
    0 => SplitInnerIterator:
      SplitInnerIterator->valid() returns TRUE
      SplitInnerIterator->key() returns '0'
      SplitInnerIterator->current() returns '1'
RETRIEVED 1 => SplitInnerIterator
  CHECKING ALL INNER ITERATORS RETRIEVED SO FAR:
    0 => SplitInnerIterator:
      SplitInnerIterator->valid() returns FALSE
      SplitInnerIterator->key() returns NULL
      SplitInnerIterator->current() returns NULL
    1 => SplitInnerIterator:
      SplitInnerIterator->valid() returns TRUE
      SplitInnerIterator->key() returns '3'
      SplitInnerIterator->current() returns '4'
RETRIEVED 2 => SplitInnerIterator
  CHECKING ALL INNER ITERATORS RETRIEVED SO FAR:
    0 => SplitInnerIterator:
      SplitInnerIterator->valid() returns FALSE
      SplitInnerIterator->key() returns NULL
      SplitInnerIterator->current() returns NULL
    1 => SplitInnerIterator:
      SplitInnerIterator->valid() returns FALSE
      SplitInnerIterator->key() returns NULL
      SplitInnerIterator->current() returns NULL
    2 => SplitInnerIterator:
      SplitInnerIterator->valid() returns TRUE
      SplitInnerIterator->key() returns '6'
      SplitInnerIterator->current() returns '7'
RETRIEVED 3 => SplitInnerIterator
  CHECKING ALL INNER ITERATORS RETRIEVED SO FAR:
    0 => SplitInnerIterator:
      SplitInnerIterator->valid() returns FALSE
      SplitInnerIterator->key() returns NULL
      SplitInnerIterator->current() returns NULL
    1 => SplitInnerIterator:
      SplitInnerIterator->valid() returns FALSE
      SplitInnerIterator->key() returns NULL
      SplitInnerIterator->current() returns NULL
    2 => SplitInnerIterator:
      SplitInnerIterator->valid() returns FALSE
      SplitInnerIterator->key() returns NULL
      SplitInnerIterator->current() returns NULL
    3 => SplitInnerIterator:
      SplitInnerIterator->valid() returns TRUE
      SplitInnerIterator->key() returns '9'
      SplitInnerIterator->current() returns '10'
END OF LOOP, ALL SHOULD BE INVALID NOW:
  CHECKING ALL INNER ITERATORS RETRIEVED SO FAR:
    0 => SplitInnerIterator:
      SplitInnerIterator->valid() returns FALSE
      SplitInnerIterator->key() returns NULL
      SplitInnerIterator->current() returns NULL
    1 => SplitInnerIterator:
      SplitInnerIterator->valid() returns FALSE
      SplitInnerIterator->key() returns NULL
      SplitInnerIterator->current() returns NULL
    2 => SplitInnerIterator:
      SplitInnerIterator->valid() returns FALSE
      SplitInnerIterator->key() returns NULL
      SplitInnerIterator->current() returns NULL
    3 => SplitInnerIterator:
      SplitInnerIterator->valid() returns FALSE
      SplitInnerIterator->key() returns NULL
      SplitInnerIterator->current() returns NULL