<?php
/**
 * This trait implements the methods for an empty, countable Iterator.
 * 
 * Use this for a class that needs to fulfill both the Countable and
 * the Iterator interfaces, but will always be empty (aka contain no
 * objects in the Iterator and count() returning 0):
 *
 * <code>
 *   class MyEmptyIterator implements Iterator, Countable {
 *     use traitIteratorCountableEmpty;
 *   }
 * </code>
 *
 * Just saves you some typing for Mock-Ups, test cases, or other
 * classes that will never contain anything.
 *
 * @author Beat Vontobel
 * @since  2015-04-01
 */
trait traitIteratorCountableEmpty {
  public function count() {
    return 0;
  }

  public function valid() {
    return FALSE;
  }

  public function current() {
    return NULL;
  }

  public function key() {
    return NULL;
  }

  public function next() { }
  public function rewind() { }
}
?>