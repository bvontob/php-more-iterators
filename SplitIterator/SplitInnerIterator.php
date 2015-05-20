<?php
/**
 * This is the type of iterator that {@see SplitIterator} returns for
 * its split-off parts.
 *
 * This iterator can only be traversed once (it can't be rewound, as
 * it is actually just a "view" on parts of the outer, unsplit
 * iterator, and rewinding it would also mess with the state of the
 * outer iterator).
 *
 * @see SplitIterator
 */
class SplitInnerIterator extends NoRewindIterator {
  private $outerIterator;

  private $valid = TRUE;

  final public function __construct(Traversable $iterator, SplitIterator $outerIterator) {
    $this->outerIterator = $outerIterator;
    parent::__construct($iterator);
  }

  final public function key() {
    return $this->valid() ? parent::key() : NULL;
  }

  final public function current() {
    return $this->valid() ? parent::current() : NULL;
  }

  final public function next() {
    $this->valid = !$this->outerIterator->needsSplit($this->key(), $this->current());
    if($this->valid) {
      parent::next();
      $this->valid = $this->outerIterator->getInnerIterator()->valid();
    }
  }

  final public function valid() {
    return $this->valid;
  }

  final public function invalidate() {
    $this->valid = FALSE;
  }
}
?>