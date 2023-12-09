<?php

function is_valid_id($id) { //looks to see if id exists
try {
    $db = new PDO(
        "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
        DBUSER,
        DBPASS
    );

    $return = false;

    $select_query = "SELECT * FROM websites WHERE site_id = :id";
    $statement = $db -> prepare($select_query);
    $statement -> execute(
        array(
            'id' => $id
        ));

    $results = $statement -> fetchAll((PDO::FETCH_ASSOC));
    if(count($results) != 0) {
        $return = true;
    } else {
        echo "Please enter a valid ID";
    }

    } catch(PDOException $e) {
        echo '<p>Error in function <code>search</code>:</p>' . "\n";
        echo '<p id="error">' . $e->getMessage() . '</p>' . "\n";
        echo "<p>There might be an issue with the database connection or query execution.</p>\n";
        echo '<p>Click <a href="./">here</a> to go back.</p>';
        exit;
    }
    return $return;
}
function search($attribute, $search) { //Search db
    try {
        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER,
            DBPASS
        );

        $db->exec("SET block_encryption_mode = 'aes-256-cbc'");
        $db->exec("SET @key_str = " . KEY_STR);
        $db->exec("SET @init_vector = " . INIT_VECTOR);

        if($search != '*'){ //show only searched
            $select_query = "SELECT * FROM (
                SELECT *,
                CAST(AES_DECRYPT(password, @key_str, @init_vector) AS CHAR) AS decrypted_password FROM passwords
                JOIN websites ON passwords.password_id = websites.site_id
                JOIN users ON passwords.password_id = users.user_id
                ) AS subquery
                WHERE
                subquery.{$attribute} LIKE CONCAT('%', :search, '%')";

            $statement = $db -> prepare($select_query);
            $statement -> execute(
                array(
                    'search' => $search
                ));

        } else { //show all entrys
            $select_query = "SELECT *, CAST(AES_DECRYPT(password, @key_str, @init_vector) AS CHAR) AS decrypted_password
                FROM passwords
                JOIN websites ON passwords.password_id = websites.site_id
                JOIN users ON passwords.password_id = users.user_id";

                $statement = $db -> prepare($select_query);
                $statement -> execute();
        }

        $results = $statement -> fetchAll((PDO::FETCH_ASSOC));

        if (count($results) == 0) { //if not results return
            echo "Nothing was found";

        } else { //otherwise print table
            echo "<table>\n";
            echo "  <thead>\n";
            echo "    <tr>\n";
            echo "      <th>Website ID</th>\n";
            echo "      <th>Website Name</th>\n";
            echo "      <th>Website URL</th>\n";
            echo "      <th>Username</th>\n";
            echo "      <th>Email</th>\n";
            echo "      <th>Password</th>\n";
            echo "      <th>First Name</th>\n";
            echo "      <th>Last Name</th>\n";
            echo "      <th>Comment</th>\n";
            echo "      <th>Time Stamp</th>\n";
            echo "    </tr>\n";
            echo "  </thead>\n";
            echo "  <tbody>\n";

            foreach ($results as $row) {
                echo "    <tr>\n";
                echo "      <td>" . htmlspecialchars($row['user_id']) . "</td>\n";
                echo "      <td>" . htmlspecialchars($row['site_name']) . "</td>\n";
                echo "      <td>" . htmlspecialchars($row['site_url']) . "</td>\n";
                echo "      <td>" . htmlspecialchars($row['username']) . "</td>\n";
                echo "      <td>" . htmlspecialchars($row['email']) . "</td>\n";
                echo "      <td>" . htmlspecialchars($row['decrypted_password']) . "</td>\n";
                echo "      <td>" . htmlspecialchars($row['first_name']) . "</td>\n";
                echo "      <td>" . htmlspecialchars($row['last_name']) . "</td>\n";
                echo "      <td>" . htmlspecialchars($row['comment']) . "</td>\n";
                echo "      <td>" . htmlspecialchars($row['time_stamp']) . "</td>\n";
                echo "    </tr>\n";
            }

            echo "  </tbody>\n";
            echo "</table>\n";
        }

    } catch(PDOException $e) {
        echo '<p>Error in function <code>search</code>:</p>' . "\n";
        echo '<p id="error">' . $e->getMessage() . '</p>' . "\n";
        echo "<p>There might be an issue with the database connection or query execution.</p>\n";
        echo '<p>Click <a href="./">here</a> to go back.</p>';
        exit;
    }
}

function update($attribute_relation, $input, $id) { //updates values in db
    try {
        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER,
            DBPASS
        );

        $db->exec("SET block_encryption_mode = 'aes-256-cbc'");
        $db->exec("SET @key_str = " . KEY_STR);
        $db->exec("SET @init_vector = " . INIT_VECTOR);

        if($attribute_relation[0] == 'password') { //look to see if password is being updated

            $select_query = "UPDATE {$attribute_relation[1]}
                SET {$attribute_relation[0]} = AES_ENCRYPT('{$input}', @key_str, @init_vector) WHERE password_id = :id";

        } else if ($attribute_relation[1] == 'users') {

            $select_query = "UPDATE {$attribute_relation[1]} SET {$attribute_relation[0]} = '{$input}' WHERE user_id = :id";

        } else {

            $select_query = "UPDATE {$attribute_relation[1]} SET {$attribute_relation[0]} = '{$input}' WHERE site_id = :id";

        }
        $statement = $db -> prepare($select_query);
        $statement -> execute(
            array(
                'id' => $id
            ));

        echo '<p id="response">' . $attribute_relation[0] . ' has been updated </p>';
        }

    catch(PDOException $e) {
        echo '<p>Error in function <code>update</code>:</p>' . "\n";
        echo '<p id="error">' . $e->getMessage() . '</p>' . "\n";
        echo "<p>There might be an issue with the database connection or query execution.</p>\n";
        echo '<p>Click <a href="./">here</a> to go back.</p>';
        exit;
    }
}

function insert($new_website, $new_user, $new_password) { //insert new db entry
    try {
        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER,
            DBPASS
        );

        $db->exec("SET block_encryption_mode = 'aes-256-cbc'");
        $db->exec("SET @key_str = " . KEY_STR);
        $db->exec("SET @init_vector = " . INIT_VECTOR);

        //QUESTION, how to prevent injection attacks and allow special charater's to be put in feild
        $select_query = "INSERT INTO websites (site_name, site_url) VALUES
            (:site_name, :site_url)";
        $statement = $db -> prepare($select_query);
        $statement -> execute(
            array(
                'site_name' => $new_website[0],
                'site_url' => $new_website[1]
            ));

        $select_query = "INSERT INTO users (first_name, last_name, username, email, comment) VALUES
             (:first_name, :last_name, :username, :email, :comment)";
        $statement = $db -> prepare($select_query);
        $statement -> execute(
            array(
                'first_name' => $new_user[0],
                'last_name' => $new_user[1],
                'username' => $new_user[2],
                'email' => $new_user[3],
                'comment' => $new_user[4]
            ));

        $select_query = "INSERT INTO passwords (password) VALUES
              (AES_ENCRYPT(:new_password, @key_str, @init_vector))";
        $statement = $db -> prepare($select_query);
        $statement -> execute(
            array(
                'new_password' => $new_password[0]
            ));

        echo '<p id="response">New entry has been added! </p>';
    }

    catch(PDOException $e) {

        echo '<p>Error in function <code>insert</code>:</p>' . "\n";
        echo '<p id="error">' . $e->getMessage() . '</p>' . "\n";
        echo "<p>There might be an issue with the database connection or query execution.</p>\n";
        echo '<p>Click <a href="./">here</a> to go back.</p>';
        exit;
    }
}

function delete($id) { //Delete tuple from db
    try {
        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER,
            DBPASS
        );

        $select_query = "DELETE FROM websites WHERE websites.site_id = '{$id}'";
        $statement = $db -> prepare($select_query);
        $statement -> execute();

        echo '<p id="response"> Entry has been deleted! </p>';
    }

    catch(PDOException $e) {
        echo '<p>Error in function <code>delete</code>:</p>' . "\n";
        echo '<p id="error">' . $e->getMessage() . '</p>' . "\n";
        echo "<p>There might be an issue with the database connection or query execution.</p>\n";
        echo '<p>Click <a href="./">here</a> to go back.</p>';
        exit;
    }
}
?>
