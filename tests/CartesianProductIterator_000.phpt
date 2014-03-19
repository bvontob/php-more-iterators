--TEST--
CartesianProductIterator: Automatic smoke test (syntax, warnings, silence)
--FILE--
<?php
$source      = FALSE;
$output      = FALSE;
$global_vars = array();
$cr_okay     = FALSE;

$source = file_get_contents("CartesianProductIterator.php");
if(preg_match('/\?>\s*$/', $source))
  echo "CartesianProductIterator has closing PHP tag at end\n";
else
  echo "CartesianProductIterator is missing closing PHP tag at end\n";

if(file_exists(preg_replace("|[^/]+$|", "DOS_LINE_ENDINGS", "CartesianProductIterator"))) {
  // Check for a consistent use of only CRLF if a "DOS_LINE_ENDINGS" marker
  // file exists in the containing folder
  $cr_okay = (preg_match_all('/\x0d\x0a/', $source) == preg_match_all('/\x0d/', $source)
              && preg_match_all('/\x0d\x0a/', $source) == preg_match_all('/\x0a/', $source));
} else {
  // Otherwise (normal situation) any CR character is treated as a bug
  $cr_okay = !preg_match('/\x0d/', $source);
}

if($cr_okay)
  echo "CartesianProductIterator does not contain CR characters (or is consistent and in a folder marked with DOS_LINE_ENDINGS)\n";
else
  echo "CartesianProductIterator does contain CR characters (and is not consistent or not in a folder marked with DOS_LINE_ENDINGS)\n";

$global_vars = array_keys($GLOBALS);

ob_start();
require_once("CartesianProductIterator.php");
$output = ob_get_contents();
ob_end_clean();
if($output === "")
  echo "Parsing of CartesianProductIterator was silent\n";
else
  echo "Parsing of CartesianProductIterator was not silent:\n$output";

// XXX: Currently, we have these exceptions of global variables being added...
$global_vars = array_merge($global_vars,
                           array('db_host', 'db_user', 'db_pass', 'db_name',
                                 'memcache_hostconfig',
                                 'global_script_resources_object'));

if(count(array_diff(array_keys($GLOBALS), $global_vars)) == 0)
  print "CartesianProductIterator did not pollute global variable space\n";
else
  print "CartesianProductIterator added these variables to global space: ".
        implode(", ", array_diff(array_keys($GLOBALS), $global_vars)).
        "\n";
?>
--EXPECT--
CartesianProductIterator has closing PHP tag at end
CartesianProductIterator does not contain CR characters (or is consistent and in a folder marked with DOS_LINE_ENDINGS)
Parsing of CartesianProductIterator was silent
CartesianProductIterator did not pollute global variable space
