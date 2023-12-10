<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Student Information</title>
    <link rel="stylesheet" href="css/style.css">
  </head>
<body>
  <header>
    <h1>Student Information</h1>
  </header>
  <form id="clear-results" method="post"
      action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input id="clear-result__submit-button" type="submit" value="Clear Results">
  </form>

<?php
require_once "includes/config.php";
require_once "includes/helpers.php";

define("NOTHING_FOUND",  "NOTHING_FOUND");
define("SEARCH",         "SEARCH");
define("UPDATE",         "UPDATE");
define("INSERT",         "INSERT");
define("DELETE",         "DELETE");

$option = (isset($_GET["submitted"]) ? $_GET["submitted"] : null);
echo $option. "<br>";

if ($option != null) {
    switch ($option) {

        case SEARCH:
            if ("" == $_GET['search'] || "" == $_GET['current_attribute']) {
                echo '<div id="error">Search query empty. Please try again.</div>' .
                    "\n";
            } else {
                if (NOTHING_FOUND === (search($_GET['search'],$_GET['current_attribute']))) {
                    echo '<div id="error">Nothing found.</div>' . "\n";
                }
            }

            break;

        case INSERT:
            if (("" == $_GET['Site']) || ("" == $_GET['URL']) || ("" == $_GET['Email_Address']) || ("" == $_GET['UserName']) || ("" == $_GET['Password'])) {
                echo '<div id="error"> You mush fill out all the required form ' .
                    'is empty. Please try again.</div>' . "\n";
            } else {
                insert($_GET['Site'],$_GET['URL'],$_GET['Email_Address'],$_GET['UserName'],$_GET['Password'],$_GET['comment']);
            }

            break;

        case DELETE:
            if (("" == $_GET['current-attribute']) || ("" == $_GET['pattern'])) {
                echo '<div id="error">At least one field in your delete procedure ' .
                    'is empty. Please try again.</div>' . "\n";
            } else {
                delete($_GET['current-attribute'], $_GET['pattern']);
            }

            break;

        case UPDATE:
            if ((0 == $_GET['new-attribute']) && ("" == $_GET['pattern'])) {
                echo '<div id="error">One or both fields were empty, ' .
                    'but both must be filled out. Please try again.</div>' . "\n";
            } else {
                update($_GET['current-attribute'], $_GET['new-attribute'],
                    $_GET['query-attribute'], $_GET['pattern'], $_GET['ID']);
            }

            break;
    }
}
else{

    echo "Welcome";
}
require_once "includes/Search-form.html";
require_once "includes/Update-form.html";
require_once "includes/Insert-form.html";
require_once "includes/Delete-form.html";
?>
  </body>
</html>
