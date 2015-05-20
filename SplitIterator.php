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

  final public function key() {
    return $this->key;
  }

  final public function current() {
    return $this->splitIterator;
  }

  final public function next() {
    parent::next();
    $this->key++;
    $this->splitOff();
  }

  final public function rewind() {
    parent::rewind();
    $this->key = 0;
    $this->splitOff();
  }

  protected function splitOff() {
    if(isset($this->splitIterator))
      $this->splitIterator->invalidate();
    $this->splitIterator = new SplitInnerIterator($this->getInnerIterator(), $this);
  }

  /**
   * @todo We need to anticipate here, if we really can return a
   *     SplitInnerIterator, and not just stupidly forward.
   */
  final public function valid() {
    return parent::valid();
  }
}
?>