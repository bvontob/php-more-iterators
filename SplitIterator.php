<?php
abstract class SplitIterator extends IteratorIterator {
  abstract public function needsSplit($key, $value);

  private $splitIterator;

  private $key;

  final public function key() {
    return $this->key;
  }

  final public function current() {
    return $this->splitIterator;
  }

  final public function next() {
    parent::next();
    $this->key++;
    $this->splitIterator = new SplitInnerIterator($this->getInnerIterator(), $this);
  }

  final public function rewind() {
    parent::rewind();
    $this->key = 0;
    $this->splitIterator = new SplitInnerIterator($this->getInnerIterator(), $this);
  }
}

class SplitInnerIterator extends NoRewindIterator {
  private $outerIterator;

  private $valid = TRUE;

  final public function __construct(Traversable $iterator, SplitIterator $outerIterator) {
    $this->outerIterator = $outerIterator;
    parent::__construct($iterator);
  }

  final public function next() {
    $this->valid = !$this->outerIterator->needsSplit($this->key(), $this->current());
    if($this->valid)
      parent::next();
  }

  final public function valid() {
    return $this->valid;
  }
}
?>