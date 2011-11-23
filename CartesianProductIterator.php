<?php
/**
 * Class CartesianProductIterator implements an Iterator that iterates over two
 * Iterators, returning all the values of the second Iterator for every value
 * of the first Iterator, aka performing a cartesian product or a CROSS JOIN as
 * it would be called in SQL. Each joined value of the product is returned as
 * an array of the two values like this:
 *
 *   array(valueFrom1stIterator, valueFrom2ndIterator)
 *
 * The keys of the Iterators are discarded, this class will instead return a
 * sequential numeric key.
 */

class CartesianProductIterator extends IteratorIterator
implements Iterator, Traversable, OuterIterator {

  private $innerIterator;
  private $key = 0;

  public function __construct(Traversable $iterator, Traversable $innerIterator) {
    $this->innerIterator = $innerIterator;
    parent::__construct($iterator);
  }

  public function current() {
    return array(parent::current(), $this->innerIterator->current());
  }

  public function key() {
    return $this->key;
  }

  public function next() {
    $this->key++;
    $this->innerIterator->next();
    if($this->innerIterator->valid())
      return;
    $this->innerIterator->rewind();
    parent::next();
  }

  public function rewind() {
    $this->key = 0;
    $this->innerIterator->rewind();
    parent::rewind();
  }
}

?>