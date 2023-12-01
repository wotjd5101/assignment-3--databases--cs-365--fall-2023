<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>CRUD Operations via a Web Interface</title>
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <header>
      <h1>Password's Database</h1>
    </header>
    <form id="clear-results" method="post"
          action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input id="clear-results" type="submit" value="Clear Results">
    </form>
    <?php
    require_once "includes/config.php";
    require_once "includes/helpers.php";

    if (isset($_POST['submitted']) && $_POST['input'] != "") {
        switch ($_POST['submitted']) {
            case '0': //search
                search($_POST['search-attribute'], $_POST['input']);

                break;

            case '1': //update
                if(is_valid_id($_POST['id'])) {
                    $attribute_relation = explode(',', $_POST['update-attribute']);
                    update($attribute_relation, $_POST['input'], $_POST['id']);
                }

                break;

            case '2': //insert
                $new_website = array(
                    $_POST['site_name'],
                    $_POST['site_url'],
                );

                $new_user = array (
                    $_POST['first_name'],
                    $_POST['last_name'],
                    $_POST['username'],
                    $_POST['email'],
                    $_POST['comment']
                );

                $new_password = array(
                    $_POST['password'],
                );

                insert($new_website, $new_user, $new_password);
                break;

            case '3': //delete
                if(is_valid_id($_POST['input'])) {
                    delete($_POST['input']);
                    break;
                }
        }
    } else {
        echo '<div id="error">Please enter a search term.</div>' . "\n";
    }
    ?>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <fieldset>
            <legend>Search</legend>
            Search the passwords database,
            <select name="search-attribute" id="search-attribute">
                <option value="user_id">Website ID</option>
                <option value="site_name">Website Name</option>
                <option value="site_url">Website URL</option>
                <option value="username">Username</option>
                <option value="email">Email</option>
                <option value="decrypted_password">Password</option>
                <option value="first_name">First Name</option>
                <option value="last_name">Last Name</option>
                <option value="comment">Comment</option>
                <option value="time_stamp">Time of Creation</option>
            </select>
            <input type="text" name="input" placeholder="Search" required>
            <input type="hidden" name="submitted" value="0">
            <p>
                <input type="submit" value="Search">
            </p>
        </fieldset>
    </form>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <fieldset>
            <legend>Update</legend>
            Update the passwords database,
            <select name="update-attribute" id="update-attribute">
                <option value="site_name,websites">Website Name</option>
                <option value="site_url,websites">Website URL</option>
                <option value="username,users">Username</option>
                <option value="email,users">Email</option>
                <option value="password,passwords">Password</option>
                <option value="first_name,users">First Name</option>
                <option value="last_name,users">Last Name</option>
                <option value="comment,users">Comment</option>
            </select>
            <input type="text" name="input" placeholder="Value" required>
            <input type="text" name="id" placeholder="ID to update" required>
            <input type="hidden" name="submitted" value="1">
            <p>
                <input type="submit" value="Update">
            </p>
        </fieldset>
    </form>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <fieldset>
            <legend>Insert</legend>
            Insert into the password's database,
            <input type="text" name="site_name" placeholder="Website Name" required>
            <input type="text" name="site_url" placeholder="Website URL" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="text" name="email" placeholder="Email" required>
            <input type="text" name="password" placeholder="Password" required>
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <br>
                <textarea id="comment" name="comment" rows="5" cols="40" placeholder="Enter your comment here..." required></textarea>
            <br>
            <input type="hidden" name="input" value="true">
            <input type="hidden" name="submitted" value="2">

            <p>
                <input type="submit" value="Insert">
            </p>
        </fieldset>
    </form>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <fieldset>
            <legend>Delete</legend>
            Delete from the password's database,
            <input type="text" name="input" placeholder="ID to delete" required>
            <input type="hidden" name="submitted" value="3">
            <p>
                <input type="submit" value="Update">
            </p>
        </fieldset>
    </form>

