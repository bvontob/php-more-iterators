<?php
/**
 * Implementation of a simple {@see SplitIterator} for test cases.
 *
 * This class for tests exists, as the {@see SplitIterator} itself is
 * an abstract class to which concrete implementations need to add
 * their own {@see SplitIterator::needsSplit()} method.
 */
class SplitIfDivisibleIterator extends SplitIterator {
  private $divisor;

  /**
   * Creates a new iterator that splits another iterator into smaller
   * iterators at each value that passes the divisibility test.
   *
   * @param Traversable An iterator over integers, to be split into
   *     smaller iterators.
   *
   * @param int The divisor by which each value returned from the
   *     original iterator will be divided by: If the value is
   *     divisible (modulus operation equals zero), a new iterator
   *     will be split off.
   */
  public function __construct(Traversable $iterator, $divisor) {
    $this->divisor = (int)$divisor;
    parent::__construct($iterator);
  }

  public function needsSplit($key, $value) {
    return $value % $this->divisor == 0;
  }
}
?>