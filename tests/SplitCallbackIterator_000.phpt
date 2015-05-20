--TEST--
SplitCallbackIterator: Automatic smoke test (syntax, warnings, silence)
--FILE--
<?php
$source      = FALSE;
$output      = FALSE;
$global_vars = array();
$cr_okay     = FALSE;

$source = file_get_contents("SplitCallbackIterator.php");
$lines  = preg_split('/(\\x0a|\\x0d)+/', $source, 0, PREG_SPLIT_NO_EMPTY);

if(preg_match('/\?>\s*$/', $source))
  echo "SplitCallbackIterator has closing PHP tag at end\n";
else
  echo "SplitCallbackIterator is missing closing PHP tag at end\n";

if(file_exists(preg_replace("|[^/]+$|", "DOS_LINE_ENDINGS", "SplitCallbackIterator"))) {
  // Check for a consistent use of only CRLF if a "DOS_LINE_ENDINGS" marker
  // file exists in the containing folder
  $cr_okay = (preg_match_all('/\x0d\x0a/', $source) == preg_match_all('/\x0d/', $source)
              && preg_match_all('/\x0d\x0a/', $source) == preg_match_all('/\x0a/', $source));
} else {
  // Otherwise (normal situation) any CR character is treated as a bug
  $cr_okay = !preg_match('/\x0d/', $source);
}

if($cr_okay)
  echo "SplitCallbackIterator does not contain CR characters (or is consistent and in a folder marked with DOS_LINE_ENDINGS)\n";
else
  echo "SplitCallbackIterator does contain CR characters (and is not consistent or not in a folder marked with DOS_LINE_ENDINGS)\n";

$global_vars = array_keys($GLOBALS);

ob_start();
require_once("SplitCallbackIterator.php");
$output = ob_get_contents();
ob_end_clean();
if($output === "")
  echo "Parsing of SplitCallbackIterator was silent\n";
else
  echo "Parsing of SplitCallbackIterator was not silent:\n$output";

// XXX: Currently, we have these exceptions of global variables being added...
$global_vars = array_merge($global_vars,
                           array('db_host', 'db_user', 'db_pass', 'db_name',
                                 'memcache_hostconfig',
                                 'global_script_resources_object'));

if(count(array_diff(array_keys($GLOBALS), $global_vars)) == 0)
  print "SplitCallbackIterator did not pollute global variable space\n";
else
  print "SplitCallbackIterator added these variables to global space: ".
        implode(", ", array_diff(array_keys($GLOBALS), $global_vars)).
        "\n";

$class_defs = array();
foreach($lines as $line) {
  if(preg_match('/^\s*((abstract)\s+)?(class|interface|trait)\s+([a-z0-9_]+)/i', $line, $matches)) {
    $new_def = "$matches[3] $matches[4]";
    if($matches[3] == 'class'
       && preg_match('/Exception$/', $matches[4])) {
      foreach($class_defs as $class_def) {
        if(preg_match("/^".preg_replace("/^(trait|interface)/", "class", $class_def)."/", $new_def))
          continue 2;
      }
    }
    $class_defs[] = $new_def;
  }
}
if(count($class_defs) <= 1) {
  print "SplitCallbackIterator contains at most one class, interface, or trait (except for Exception classes)\n";
} else {
  print "SplitCallbackIterator contains multiple classes/interfaces/traits:\n";
  print "  ".implode("\n  ", $class_defs)."\n";
}
?>
--EXPECT--
SplitCallbackIterator has closing PHP tag at end
SplitCallbackIterator does not contain CR characters (or is consistent and in a folder marked with DOS_LINE_ENDINGS)
Parsing of SplitCallbackIterator was silent
SplitCallbackIterator did not pollute global variable space
SplitCallbackIterator contains at most one class, interface, or trait (except for Exception classes)
