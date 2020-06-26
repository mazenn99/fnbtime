<?php
session_start();
$header = '';
include_once 'init.php';
if (isset($_GET['country']) && intval($_GET['country'])) { # This is to get a city of related country
    $cityId = mysqli_real_escape_string($mysql, filter_var($_GET['country'], FILTER_SANITIZE_NUMBER_INT));
    $stmt = $mysql->prepare("SELECT id , name FROM city WHERE country_id = ? ORDER BY name ASC");
    $stmt->bind_param('i', $dbCountryID);
    $dbCountryID = $cityId;
    $stmt->execute();
    $city = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    echo json_encode($city);
}

#This function using to search
if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($mysql, filter_var($_POST['search'], FILTER_SANITIZE_STRING));
    $stmt = $mysql->prepare("SELECT restaurant.* , keywords.name AS keywords , country.name AS couName , city.name AS citName FROM restaurant 
                                      INNER JOIN keywords ON keywords.res_id = restaurant.id
                                      INNER JOIN country ON country.id = restaurant.country
                                      INNER JOIN city ON city.id = country.id
                                      WHERE restaurant.name LIKE CONCAT('%' , ? , '%') OR keywords.name LIKE CONCAT('%' , ? , '%')");
    $stmt->bind_param('ss', $dbSearchInput, $dbSearchKeyword);
    $dbSearchInput = $_POST['search'];
    $dbSearchKeyword = $_POST['search'];
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($data as $resInfo) { ?>
            <div class="reservation-summary-wrapper">
                <a href="restaurant-info.php?restaurant=<?php echo $resInfo['id'] ?>" target="_blank">
                <ul class="reservation-summary-list">

                            <li>
                                <div class="image">
                                    <?php $img = explode(',', $resInfo['picture']); ?>
                                    <img src="uploads/<?php echo $img[0] ?>" alt="Restaurant image"/>
                                </div>
                            </li>

                            <li>
                                <span class="block text-muted text-uppercase">Restaurant</span>
                                <h6><a href="restaurant-info.php?restaurant=<?php echo $resInfo['id'] ?>"
                                       target="_blank"><?php echo $resInfo['name'] ?></a></h6>
                            </li>

                            <li>
                                <span class="block text-muted text-uppercase">Country</span>
                                <h6><?php echo $resInfo['couName'] ?></h6>
                            </li>

                            <li>
                                <span class="block text-muted text-uppercase">city</span>
                                <h6><?php echo $resInfo['citName'] ?></h6>
                            </li>

                </ul>
                </a>

            </div>
        <?php }
        ?>
    <?php } else {
        echo '<a href="#" class=" text-left list-group-item">No Data</a>';
    }
}

# This to add restaurant to favorite of coustomer
if(isset($_POST['favorite'])) {
    $fav = mysqli_real_escape_string($mysql , filter_var($_POST['favorite'] , FILTER_SANITIZE_NUMBER_INT));
    $stmt = $mysql->prepare("INSERT INTO favorite (user_id , res_id)
                                  VALUES(? , ?)");
    $stmt->bind_param('ii' , $dbUserID , $dbResID);
    $dbUserID = $_SESSION['userid'];
    $dbResID  = $fav;
    $stmt->execute();
    if($stmt->error) {
        echo 'sorry try again';
    } else {
        echo 'Restaurant Saved';
    }
}
# This to add restaurant to favorite of coustomer

# This to get Type Cuisine of restaurant page
if(isset($_POST['filterType'])) {
    $filter = mysqli_real_escape_string($mysql , filter_var($_POST['filterType'] , FILTER_SANITIZE_STRING));
    $stmt = $mysql->prepare("SELECT restaurant.* , country.name AS couName , city.name AS citName FROM restaurant 
                                      INNER JOIN keywords ON keywords.res_id = restaurant.id
                                      INNER JOIN country ON country.id = restaurant.country
                                      INNER JOIN city ON city.id = country.id
                                      WHERE restaurant.type_food LIKE CONCAT('%' , ? , '%')");
    $stmt->bind_param('s' , $dbFilter);
    $dbFilter = $filter;
    $stmt->execute();
    $data = $stmt->get_result();
    if($data->num_rows){
        $result = $data->fetch_all(MYSQLI_ASSOC);
    ?>
    <div class="restaurant-list-item-wrapper no-last-bb">
        <?php foreach ($result as $data): ?>
            <div class="restaurant-list-item clearfix">

                <div class="GridLex-grid-noGutter-equalHeight">

                    <div class="GridLex-col-3_sm-3_xss-12">
                        <div class="image"> <?php $img = explode(',', $data['picture']); ?>
                            <img src="uploads/<?php echo $img[0] ?>" alt="Image"/>
                        </div>
                    </div>

                    <div class="GridLex-col-9_sm-9_xss-12">

                        <div class="GridLex-grid-noGutter-equalHeight">

                            <div class="GridLex-col-9_sm-12 content-wrapper">

                                <div class="content">
                                    <h5>
                                        <a href="restaurant-info.php?restaurant=<?php echo $data['id'] ?>"><?php echo $data['name'] ?></a>
                                    </h5>
                                    <p class="location"><i
                                                class="fa fa-map-marker"></i> <?php echo $data['couName'] . ' ' . $data['citName'] ?>
                                    </p>
                                    <p class="short-info"><?php if (strlen($data['description']) > 40)
                                            echo substr($data['description'], 0, 300);
                                        ?></p>
                                    <p class="cuisine">
                                        Cuisine: <?php $type = explode(',', $data['type_food']); # This is get the type of restaurant
                                        foreach ($type as $types):?>
                                            <span><?php echo $types ?></span>
                                        <?php endforeach; ?>
                                    </p>
                                </div>

                            </div>

                            <div class="GridLex-col-3_sm-12 meta-wrapper">

                                <div class="meta">


                                    <div class="right-bottom">
                                        <?php if (getAllReservation($data['id'])) { ?>
                                            <div class="price">All Reservation
                                                <span><?php echo getAllReservation($data['id']) ?><i
                                                            class="fa fa-bookmark mr-5"
                                                            style="margin-left:3px"></i></span>
                                            </div>
                                            <div class="clear"></div>
                                        <?php } ?>
                                        <a href="restaurant-info.php?restaurant=<?php echo $data['id'] ?>"
                                           class="btn btn-primary btn-sm btn-block">Details</a>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        <?php endforeach; ?>

    </div>
    <?php } else {
        echo '<a href="#" class=" text-left list-group-item">No Data</a>';

    }

}

