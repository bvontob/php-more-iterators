<?php
/**
 * Class CallbackTransformIterator implements an Iterator that transforms
 * the values of another Iterator through a callback provided from the
 * outside.
 */

class CallbackTransformIterator extends IteratorIterator
implements Iterator, Traversable, OuterIterator {

  private $valueCallback;
  private $keyCallback;


  /**
   * In addition to a plain IteratorIterator we take a second argument with
   * a callback function to transform the values returned by the inner Iterator.
   * The callback will be called with three arguments: The current value, the
   * current key and the inner Iterator. Most of the time it will probably
   * only use the first of the three arguments (the value). It must return
   * the transformed value.
   *
   * The optional third argument is a second transformation callback working
   * on the key.
   *
   * XXX: Replace internal type check with a typehint for $callback as of
   *      PHP 5.4, when this should be added as a new feature...
   */
  public function __construct(Traversable $iterator,
                              $callback = NULL,
                              $keyCallback = NULL) {
    if((isset($callback) && !is_callable($callback))
       || (isset($keyCallback) && !is_callable($keyCallback)))
      throw new Exception(__CLASS__.": Second and third arguments must be ".
                          "callbacks (or NULL)");
    $this->valueCallback = $callback;
    $this->keyCallback   = $keyCallback;
    parent::__construct($iterator);
  }


  /**
   * current() will return the current value of the inner Iterator transformed
   * by the callback provided to the constructor.
   */
  public function current() {
    if(isset($this->valueCallback))
      return call_user_func($this->valueCallback,
                            parent::current(),
                            parent::key(),
                            $this->getInnerIterator());
    return parent::current();
  }


  /**
   * key() will return the current key of the inner Iterator transformed
   * by the callback provided to the constructor.
   */
  public function key() {
    if(isset($this->keyCallback))
      return call_user_func($this->keyCallback,
                            parent::current(),
                            parent::key(),
                            $this->getInnerIterator());
    return parent::key();
  }
}

?>