<?php
require_once("SplitIterator/SplitInnerIterator.php");

/**
 *
 * @author     Beat Vontobel
 * @since      2015-05-20
 * @package    MeteoNews\phplib
 * @subpackage Iterators
 */
abstract class SplitIterator extends IteratorIterator {
  abstract public function needsSplit($key, $value);

  private $splitIterator;

  private $key;

  public function __construct(Traversable $innerIterator) {
    parent::__construct($innerIterator);
    $this->splitOff();
  }

  public function key() {
    return $this->key;
  }

  public function current() {
    return $this->splitIterator;
  }

  public function next() {
    parent::next();
    $this->key++;
    $this->splitOff();
  }

  public function rewind() {
    parent::rewind();
    $this->key = 0;
    $this->splitOff();
  }

  private function splitOff() {
    if(isset($this->splitIterator)) {
      $this->splitIterator->invalidate();
      $this->splitIterator = NULL;
    }

    if(parent::valid())
      $this->splitIterator = new SplitInnerIterator($this->getInnerIterator(), $this);
  }

  public function valid() {
    return isset($this->splitIterator);
  }
}
?>