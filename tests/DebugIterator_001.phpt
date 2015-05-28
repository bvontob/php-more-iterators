--TEST--
DebugIterator: Basic test
--FILE--
<?php
require_once("DebugIterator.php");
$iterator = new DebugIterator(new ArrayIterator(array("one",
                                                      new stdClass(),
                                                      3,
                                                      array("1 o'clock", "2 o'clock"),
                                                      TRUE,
                                                      NULL
                                                      )));
foreach($iterator as $key => $item) {
  // NOP, the DebugIterator does everything
}
?>
--EXPECT--
DebugIterator(ArrayIterator)::__construct(object(ArrayIterator)) => object(DebugIterator)
DebugIterator(ArrayIterator)::rewind()
DebugIterator(ArrayIterator)::valid() => TRUE
DebugIterator(ArrayIterator)::current() => 'one'
DebugIterator(ArrayIterator)::key() => 0
DebugIterator(ArrayIterator)::next()
DebugIterator(ArrayIterator)::valid() => TRUE
DebugIterator(ArrayIterator)::current() => object(stdClass)
DebugIterator(ArrayIterator)::key() => 1
DebugIterator(ArrayIterator)::next()
DebugIterator(ArrayIterator)::valid() => TRUE
DebugIterator(ArrayIterator)::current() => 3
DebugIterator(ArrayIterator)::key() => 2
DebugIterator(ArrayIterator)::next()
DebugIterator(ArrayIterator)::valid() => TRUE
DebugIterator(ArrayIterator)::current() => array('1 o\'clock', '2 o\'clock')
DebugIterator(ArrayIterator)::key() => 3
DebugIterator(ArrayIterator)::next()
DebugIterator(ArrayIterator)::valid() => TRUE
DebugIterator(ArrayIterator)::current() => TRUE
DebugIterator(ArrayIterator)::key() => 4
DebugIterator(ArrayIterator)::next()
DebugIterator(ArrayIterator)::valid() => TRUE
DebugIterator(ArrayIterator)::current() => NULL
DebugIterator(ArrayIterator)::key() => 5
DebugIterator(ArrayIterator)::next()
DebugIterator(ArrayIterator)::valid() => FALSE
