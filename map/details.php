<?php
    include '../admin-panel/dbConnection.php';

    function parseToXML($htmlStr) {
        $xmlStr = str_replace('<', '&lt;', $htmlStr);
        $xmlStr = str_replace('>', '&gt;', $xmlStr);
        $xmlStr = str_replace('"', '&quot;', $xmlStr);
        $xmlStr = str_replace("'", '&#39;', $xmlStr);
        $xmlStr = str_replace('&', '&amp;', $xmlStr);
        return $xmlStr;
    }

    if(isset($_GET['id'])) {
        // Open connection with MySql database
        // $mysql = mysqli_connect($servername, $username, $password, $dbname);
        // if(!$mysql) {
        //     die("Connection failed: " . mysqli_connect_error());
        // }

        $sql = "SELECT R.id, R.name AS restaurant_name, R.description, C.name AS city_name, R.map_url 
                FROM restaurant R
                INNER JOIN  city C ON C.id = R.city WHERE R.id =" . $_GET['id'];
        $result = mysqli_query($mysql, $sql) or die(mysqli_error($mysql));
        $row = mysqli_fetch_assoc($result);
        
        header("Content-type: text/xml");

        // start XML file
        echo "<?xml version='1.0' ?>";
        echo '<restaurants>';
        echo '<restaurant ';
        echo 'id="' . $row['id'] . '" ';
        echo 'name="' . $row['restaurant_name'] . '" ';
        echo 'description="' . $row['description'] . '" ';
        echo 'city="' . $row['city_name'] . '" ';
        echo 'url="' . $row['map_url'] . '" ';
        echo '/>';
        echo '</restaurants>';

        mysqli_free_result($result);
    }
?>