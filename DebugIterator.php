<?php
/**
 * The <b>DebugIterator</b> wraps as an {@see IteratorIterator} around
 * any other {@see Iterator} and logs all calls to it.
 *
 *   <code>
 *     // Take any iterator...
 *     $originalIterator = new ArrayIterator(range(1, 5));
 *
 *     // ..and wrap it in a DebugIterator, to log calls to it.
 *     $iterator = new DebugIterator($originalIterator);
 *
 *     // This will now print something like
 *     //   DebugIterator(ArrayIterator)::rewind()
 *     //   DebugIterator(ArrayIterator)::valid() => TRUE
 *     //   DebugIterator(ArrayIterator)::current() => 1
 *     //   DebugIterator(ArrayIterator)::next()
 *     //   ...
 *     foreach($iterator as $item) {
 *       // ...
 *     }
 *   </code>
 *
 * @author     Beat Vontobel
 * @since      2015-05-2i
 * @package    MeteoNews\phplib
 * @subpackage Iterators
 */
class DebugIterator extends IteratorIterator {
  /**
   * Logs a message.
   *
   * Override this, if you want to send your log output to different
   * destinations or with different formatting.  Per default each
   * message will be just printed to STDOUT with a newline appended.
   *
   * @param string The message to be logged.
   *
   * @return void
   */
  protected function writeLogMessage($message) {
    printf("%s\n", $message);
  }

  /**
   * Logs one method call.
   *
   * Override this, if you're unhappy with the default formatting.
   *
   * The default internally uses {@see formatValue()} to format
   * individual arguments and return values to/from method calls and
   * {@see writeLogMessage()} to send the completed message to an
   * output channel.  If you just want to change one of those, it's
   * better to override those methods and leave this one as it is.
   *
   * @param string The name of the called method.
   *
   * @param boolean If the given method has a return value (TRUE) or
   *     is void (FALSE).
   *
   * @param mixed The return value from the called method (ignored if
   *     the previous argument is FALSE).
   *
   * @param array The arguments to the method call.
   *
   * @return void
   *
   * @see writeLogMessage(), formatValue()
   */
  protected function logCall($method,
                             $hasReturnValue = FALSE,
                             $returnValue = NULL,
                             array $args = array()) {
    $message = sprintf("%s(%s)::%s(%s)",
                       get_called_class(),
                       get_class(parent::getInnerIterator()),
                       $method,
                       implode(", ", array_map(array($this, 'formatValue'), $args)));
    if($hasReturnValue)
      $message .= sprintf(" => %s", $this->formatValue($returnValue));
    $this->writeLogMessage($message);
  }

  /**
   * Formats a PHP value as a string.
   *
   * Used for arguments and return values to/from method calls in log
   * messages.  Override this, if you're unhappy with the default
   * format.
   *
   * @param mixed The value to be formatted as a string.
   *
   * @return string
   */
  protected function formatValue($value) {
    if(is_object($value))
      if(is_a($value, "ifMNDebugName"))
        return $value->getDebugName();
      else
        return sprintf("object(%s)",
                       get_class($value));
    elseif(is_array($value))
      return sprintf("array(%s)",
                     implode(", ",
                             array_map(array($this, 'formatValue'),
                                       $value)));
    elseif(is_bool($value))
      return $value ? "TRUE" : "FALSE";
    elseif(is_null($value))
      return "NULL";
    elseif(is_float($value) || is_int($value))
      return (string)$value;
    else
      return sprintf("'%s'",
                     str_replace("'", "\\'", $value));
  }


  /*
   * Just override all the IteratorIterator methods here to hook in
   * the logging calls.
   */

  public function __construct(Traversable $iterator) {
    parent::__construct($iterator);
    $this->logCall(__FUNCTION__, TRUE, $this, array($iterator));
  }

  public function current() {
    $current = parent::current();
    $this->logCall(__FUNCTION__, TRUE, $current);
    return $current;
  }

  public function getInnerIterator() {
    $innerIterator = parent::getInnerIterator();
    $this->logCall(__FUNCTION__, TRUE, $innerIterator);
    return $innerIterator;
  }

  public function key() {
    $key = parent::key();
    $this->logCall(__FUNCTION__, TRUE, $key);
    return $key;
  }

  public function next() {
    parent::next();
    $this->logCall(__FUNCTION__);
  }

  public function rewind() {
    parent::rewind();
    $this->logCall(__FUNCTION__);
  }

  public function valid() {
    $valid = parent::valid();
    $this->logCall(__FUNCTION__, TRUE, $valid);
    return $valid;
  }
}
?>