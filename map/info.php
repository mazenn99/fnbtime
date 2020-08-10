<?php 
    include '../admin-panel/dbConnection.php';

    // parsing html string to output xml string
    function parseToXML($htmlStr) {
        $xmlStr = str_replace('<', '&lt;', $htmlStr);
        $xmlStr = str_replace('>', '&gt;', $xmlStr);
        $xmlStr = str_replace('"', '&quot;', $xmlStr);
        $xmlStr = str_replace("'", '&#39;', $xmlStr);
        $xmlStr = str_replace('&', '&amp;', $xmlStr);
        return $xmlStr;
    }

    // Open connection with MySql database
    // $mysql = mysqli_connect($connection['host'], $connection['user'], $connection['password'], $connection['database']);
    // if(!$mysql) {
    //     die("Connection failed: " . mysqli_connect_error());
    // }

    // if city in request is not empty we add this part to the query 
    $cityQuery = '';
    if(isset($_GET['city'])) {
        $cityQuery = " WHERE (INSTR('" . $_GET['city'] . "', Ci.name) > 0 OR INSTR('" . $_GET['city'] . "', Co.name) > 0)";
    }

    $idException = '';
    if(isset($_GET['id'])) {
        $idException = ' AND R.id <> ' . $_GET['id'] . ' LIMIT 0, 4';
    }

    // Select all restaurants in a city or country >>(this part may need change according to your database)<<
    $sql = "SELECT R.id, R.name AS restaurant_name, R.description, Ci.name AS city_name, Co.name AS country_name, R.map_url, R.type_food
            FROM restaurant R
            INNER JOIN city Ci ON Ci.id = R.city
            INNER JOIN country Co ON Co.id = Ci.country_id" . $cityQuery . $idException;

    $result = mysqli_query($mysql, $sql);
    if(mysqli_num_rows($result) > 0) {

        header("Content-type: text/xml");

        // start XML file
        echo "<?xml version='1.0' ?>";
        echo '<restaurants>';
        $ind = 0;
        while($row = mysqli_fetch_assoc($result)) {
            echo '<restaurant ';
            echo 'id="' . $row['id'] . '" ';
            echo 'name="' . $row['restaurant_name'] . '" ';
            echo 'description="' . $row['description'] . '" ';
            echo 'city="' . $row['city_name'] . '" ';
            echo 'country="' . $row['country_name'] . '" ';
            echo 'foodType="' . $row['type_food'] . '" ';
            echo 'url="' . $row['map_url'] . '" ';
            echo '/>';
            $ind = $ind + 1;
        }

        // end XML file
        echo '</restaurants>';
    } else {
        echo "No Results";
    }

    mysqli_free_result($result);

    // mysqli_close($mysql);
?>