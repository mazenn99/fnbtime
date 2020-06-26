<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['admin'])) {
    header('location:index.php');
    die('Access Denied');
}
$getTitle = 'Restaurant';
require_once 'init.php';
$error = [];
$allowDo = ['Delete', 'Add', 'Manage', 'Edit', 'Insert', 'Update'];
$do = isset($_GET['do']) && in_array($_GET['do'], $allowDo) ? $_GET['do'] : 'Manage';

# This is to make a pagination
if(isset($_GET['page'])) {
    $page = mysqli_real_escape_string($mysql , filter_var($_GET['page'] , FILTER_SANITIZE_NUMBER_INT));
} else {
    $page = 1;
}
$numPerPage = 15;
$startFrom = ($page - 1) * 15;
# This is to make a pagination

if ($do == 'Manage') {

    ?>
    <div id="wrapper">
        <?php include_once $tpl . 'navbar.php' ?>
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">


                    <li>
                        <a href="dashboard.php"><i class="fa fa-desktop "></i>Dashboard</a>
                    </li>

                    <li>
                        <a href="https://sg2plcpnl0079.prod.sin2.secureserver.net:2096/" target="_blank"><i class="fa fa-envelope-o"></i>Mail box</a>
                    </li>

                    <li class="active-link">
                        <a href="restaurant.php"><i class="fa fa-cutlery"></i>Restaurant <span class="badge"><?php echo totalCount('id' , 'restaurant') ?></span></a>
                    </li>

                    <li>
                        <a href="users.php"><i class="fa fa-users"></i>Users <span class="badge"><?php echo totalCount('id' , 'users') ?></a>
                    </li>

                    <li>
                        <a href="last-booking.php"><i class="fa fa-tags"></i>Last Booking <span class="badge"><?php echo totalCount('id' , 'booking' , 'WHERE status = 0') ?></span></a>
                    </li>

                    <li>
                        <a href="allbooking.php"><i class="fa fa-bar-chart-o"></i>All booking <span class="badge"><?php echo totalCount('id' , 'booking') ?></span></a>
                    </li>

                    <li>
                        <a href="logout.php"></i><i class="fa fa-key"></i>Logout</span></a>
                    </li>

                </ul>
            </div>
        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="text-uppercase"><i class="fa fa-cutlery" style="margin: 0 15px;"></i>restaurant
                            dashboard</h2>
                    </div>
                </div>
                <a href="?do=Add" class="btn btn-primary btn-lg text-capitalize"><i class="fa fa-plus"></i> add new
                    Restaurant
                </a>
                <!-- /. ROW  -->
                <hr/>
                <div class="form-group">
                    <label for="searchUser">Search : </label>
                    <input type="search" class="form-control" id="searchRestaurant" placeholder="Search By restaurant Name please"
                           name="searchUserBooking">
                </div>
                <div class="row pad-top">
                    <div class="container-fluid">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered" id="table-data-restaurant">
                                <thead class='bg-danger'>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>country</th>
                                    <th>city</th>
                                    <th>type food</th>
                                    <th>Restaurant Number</th>
                                    <th>Added At</th>
                                    <th>All booking</th>
                                    <th>control</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach (getAllRestaurantAdminPage($startFrom , $numPerPage) as $result): ?>
                                    <tr>
                                        <td><?php echo $result['id'] ?></td>
                                        <td><?php echo $result['name'] ?></td>
                                        <td><?php echo $result['couName'] ?></td>
                                        <td><?php echo $result['citName'] ?></td>
                                        <td><?php echo $result['type_food'] ?></td>
                                        <td><?php echo $result['number'] ?></td>
                                        <td><?php echo $result['create_at'] ?></td>
                                        <td><?php echo getAllReservation($result['id']) ?></td>
                                        <td>
<!--                                            <a href="?do=Edit&resid=--><?php //echo $result['id'] ?><!--" class="btn btn-success"-->
<!--                                               style="margin:3px 0"><i-->
<!--                                                        class="fa fa-edit" style="margin:0 7px 0 0"></i>Edit</a>-->
                                            <a onclick="return confirm('Are You Sure ?')"
                                               href="?do=Delete&resid=<?php echo $result['id'] ?>"
                                               class="btn btn-danger" style="margin:3px 0"><i
                                                        class="fa fa-trash-o" style="margin:0 7px 0 0"></i>Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                            <!-- This to calculate the number of users and echo pagination -->
                            <?php $stmt = $mysql->query("SELECT id FROM restaurant")->num_rows;
                            $totalPage = ceil($stmt/$numPerPage);
                            for($i = 1 ; $i < $totalPage ; $i++) {
                                echo "<a href='restaurant.php?page=$i' class='btn btn-sm btn-primary'>$i</a>";
                            }
                            ?>
                            <!-- This to calculate the number of users and echo pagination -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
<?php } elseif ($do == 'Add') { ?>
    <div id="page-wrapper">
        <div id="page-inner">
            <div class="panel panel-default">
                <div class="panel-heading text-uppercase "><h3 class="text-primary">add new restaurant</h3></div>
                <div class="panel-body">
                    <form action="?do=Insert" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">Restaurant Name : </label>
                            <input type="text" class="form-control" id="resname" placeholder="Enter Restaurant name"
                                   name="resname" required>
                        </div>

                        <label for="country">country</label>
                        <select class="form-control" id="country" name="country" onchange="changeCountry()" ">
                        <option value="0" selected>...</option>
                        <?php $stmt = $mysql->query("SELECT * FROM country ORDER BY name ASC");
                        while ($row = $stmt->fetch_assoc()) { ?>
                            <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                        <?php }
                        $stmt->close(); ?>
                        </select>

                        <label for="city">city</label>
                        <select class="form-control" id="city" name="city">

                        </select>

                        <div class="form-group">
                            <label for="type_food">type food</label>
                            <input type="text" class="form-control" id="type_food"
                                   placeholder="Enter Type food Spilit With Quma (,) example (arabian , italian , fastfood)"
                                   name="type_food" required>
                        </div>

                        <div class="form-group">
                            <label for="logmaty">Logmaty</label>
                            <input type="url" class="form-control" id="logmaty"
                                   placeholder="Enter the link of restaurant Loqmaty"
                                   name="logmaty" >
                        </div>

                        <div class="form-group">
                            <label for="mrsool">Marsool</label>
                            <input type="url" class="form-control" id="mrsool"
                                   placeholder="Enter the link of restaurant Mrsool"
                                   name="mrsool" >
                        </div>

                        <div class="form-group">
                            <label for="hungerstation">HungerStation</label>
                            <input type="url" class="form-control" id="hungerstation"
                                   placeholder="Enter the link of restaurant hungerstation"
                                   name="hungerstation" >
                        </div>

                        <div class="form-group">
                            <label for="jahiz">Jahiz</label>
                            <input type="url" class="form-control" id="jahiz"
                                   placeholder="Enter the link of jahiz"
                                   name="jahiz" >
                        </div>

                        <div class="form-group">
                            <label for="careemnow">Careem Now</label>
                            <input type="url" class="form-control" id="careemnow"
                                   placeholder="Enter the link of careemnow"
                                   name="careemnow">
                        </div>

                        <div class="form-group">
                            <label for="number">Phone Number</label>
                            <input type="text" class="form-control" id="number"
                                   placeholder="Enter Phone number of restaurant"
                                   name="number" required>
                        </div>


                        <div class="form-group">
                            <label for="textarea">Description :</label>
                            <textarea id="textarea" name="textarea" class="form-control" cols="30" rows="7"
                                      placeholder="Enter Description of restaurant" required></textarea>
                        </div>


                        <div class="form-group">
                            <label for="images">images</label>
                            <input type="file" multiple="multiple" class="form-group" name="images[]" id="images"
                                   required>
                        </div>


                        <div class="form-group">
                            <label for="maps">Location on maps</label>
                            <input type="url" id="maps" name="url" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="keyword">key word</label>
                            <input type="text" id="keyword" name="keyword" placeholder="Enter Key Words food Spilit With Quma (,) example (arabian , italian , fastfood)"" class="form-control" required>
                        </div>

                        <div class="form-inline">
                            Sunday :
                            <br>
                            <div class="form-group">
                                <label for="sundayOpen">open : </label>
                                <input type="text" class="form-control" id="sundayOpen"
                                       name="sundayOpen" required>
                            </div>

                            <div class="form-group">
                                <label for="sundayClose">close : </label>
                                <input type="text" class="form-control" id="sundayClose"
                                       name="sundayClose" required>
                            </div>
                        </div>

                        <div class="form-inline">
                            Monday :
                            <br>
                            <div class="form-group">
                                <label for="mondayOpen">open : </label>
                                <input type="text" class="form-control" id="mondayOpen"
                                       name="mondayOpen" required>
                            </div>

                            <div class="form-group">
                                <label for="mondayClose">close : </label>
                                <input type="text" class="form-control" id="mondayClose"
                                       name="mondayClose" required>
                            </div>
                        </div>

                        <div class="form-inline">
                            Tuesday :
                            <br>
                            <div class="form-group">
                                <label for="tuesdayOpen">open : </label>
                                <input type="text" class="form-control" id="tuesdayOpen"
                                       name="tuesdayOpen" required>
                            </div>

                            <div class="form-group">
                                <label for="tuesdayClose">close : </label>
                                <input type="text" class="form-control" id="tuesdayClose"
                                       name="tuesdayClose" required>
                            </div>
                        </div>

                        <div class="form-inline">
                            Wednesday :
                            <br>
                            <div class="form-group">
                                <label for="wednesdayOpen">open : </label>
                                <input type="text" class="form-control" id="wednesdayOpen"
                                       name="wednesdayOpen" required>
                            </div>

                            <div class="form-group">
                                <label for="wednesdayClose">close : </label>
                                <input type="text" class="form-control" id="wednesdayClose"
                                       name="wednesdayClose" required>
                            </div>
                        </div>

                        <div class="form-inline">
                            Thursday :
                            <br>
                            <div class="form-group">
                                <label for="thursdayOpen">open : </label>
                                <input type="text" class="form-control" id="thursdayOpen"
                                       name="thursdayOpen" required>
                            </div>

                            <div class="form-group">
                                <label for="thursdayClose">close : </label>
                                <input type="text" class="form-control" id="thursdayClose"
                                       name="thursdayClose" required>
                            </div>
                        </div>

                        <div class="form-inline">
                            Friday :
                            <br>
                            <div class="form-group">
                                <label for="fridayOpen">open : </label>
                                <input type="text" class="form-control" id="fridayOpen"
                                       name="fridayOpen" required>
                            </div>

                            <div class="form-group">
                                <label for="fridayClose">close : </label>
                                <input type="text" class="form-control" id="fridayClose"
                                       name="fridayClose" required>
                            </div>
                        </div>

                        <div class="form-inline">
                            Saturday :
                            <br>
                            <div class="form-group">
                                <label for="saturdayOpen">open : </label>
                                <input type="text" class="form-control" id="saturdayOpen"
                                       name="saturdayOpen" required>
                            </div>

                            <div class="form-group">
                                <label for="saturdayClose">close : </label>
                                <input type="text" class="form-control" id="saturdayClose"
                                       name="saturdayClose" required>
                            </div>
                        </div>
                        <button class="btn btn-primary" style="margin-top:18px;"> + Add Restaurant</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } elseif ($do == 'Insert') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $resName = mysqli_real_escape_string($mysql, filter_var($_POST['resname'], FILTER_SANITIZE_STRING));
        $country = mysqli_real_escape_string($mysql, filter_var($_POST['country'], FILTER_SANITIZE_NUMBER_INT));
        $city    = mysqli_real_escape_string($mysql, filter_var($_POST['city'], FILTER_SANITIZE_NUMBER_INT));
        $tFood   = mysqli_real_escape_string($mysql, filter_var($_POST['type_food'], FILTER_SANITIZE_STRING));
        $logmaty = mysqli_real_escape_string($mysql, filter_var($_POST['logmaty'], FILTER_SANITIZE_STRING));
        $mrsool  = mysqli_real_escape_string($mysql, filter_var($_POST['mrsool'], FILTER_SANITIZE_STRING));
        $hunsta  = mysqli_real_escape_string($mysql, filter_var($_POST['hungerstation'], FILTER_SANITIZE_STRING));
        $jahiz   = mysqli_real_escape_string($mysql, filter_var($_POST['jahiz'], FILTER_SANITIZE_STRING));
        $careem  = mysqli_real_escape_string($mysql, filter_var($_POST['careemnow'], FILTER_SANITIZE_STRING));
        $number  = mysqli_real_escape_string($mysql, filter_var($_POST['number'], FILTER_SANITIZE_STRING));
        $desc    = mysqli_real_escape_string($mysql, filter_var($_POST['textarea'], FILTER_SANITIZE_STRING));
        $url     = mysqli_real_escape_string($mysql, filter_var($_POST['url'], FILTER_SANITIZE_URL));
        $keyword = mysqli_real_escape_string($mysql, filter_var($_POST['keyword'], FILTER_SANITIZE_URL));
        $openClose = [
            'sundayOpen' =>     $_POST['sundayOpen'],
            'sundayClose' =>    $_POST['sundayClose'],
            'mondayOpen' =>     $_POST['mondayOpen'],
            'mondayClose' =>    $_POST['mondayClose'],
            'tuesdayOpen' =>    $_POST['tuesdayOpen'],
            'tuesdayClose' =>   $_POST['tuesdayClose'],
            'wednesdayOpen' =>  $_POST['wednesdayOpen'],
            'wednesdayClose' => $_POST['wednesdayClose'],
            'thursdayOpen' =>   $_POST['thursdayOpen'],
            'thursdayClose' =>  $_POST['thursdayClose'],
            'fridayOpen' =>     $_POST['fridayOpen'],
            'fridayClose' =>    $_POST['fridayClose'],
            'saturdayOpen' =>   $_POST['saturdayOpen'],
            'saturdayClose' =>  $_POST['saturdayClose'],
        ];

        if (empty($resName) || empty($country) || empty($city) || empty($tFood) || empty($desc) || empty($url) || empty($open) || empty($close)) {
            array_push($error, 'sorry make sure all fields not empty and have a valid value');
        }

        if (isset($_FILES['images']) && $_FILES['images']['error'][0] == 0) {
            $AllFile = [];
            $file = $_FILES['images'];
            $allowSize = 2222200;
            $allowFile = [
                'jpg'  => 'image/jpg',
                'jpeg' => 'image/jpeg',
                'png'  => 'image/png'
            ];
            $num = count($file['name']);
            if ($file['error'][0] == 0) {
                for ($i = 0; $i < $num; $i++) {
                    $error = [];
                    $type = mime_content_type($file['tmp_name'][$i]);
                    $fileSize = $file['size'][$i];
                    if (!(in_array($type, $allowFile))) {
                        array_push($error, "sorry this file can't allow uploaded file number " . ($i + 1));
                    }
                    if ($fileSize > $allowSize) {
                        array_push($error, 'sorry this size bigger than ' . $allowSize . ' File number ' . ($i + 1));
                    }
                    if (!count($error)) {
                        $rand = time() . $file['name'][$i];
                        move_uploaded_file($file['tmp_name'][$i], $_SERVER['DOCUMENT_ROOT'] . '/fnbtime6/uploads/' . $rand);
                        $AllFile[] = $rand;
                    } else {
                        include '../error.php';
                    }
                }
                $imgFields = implode(',', $AllFile);

            } else {
                array_push($error, 'please uploaded one file at least');
            }
        }


        if (!count($error)) {
            $res = $mysql->prepare("INSERT INTO restaurant (name , country , city , type_food , number  , description  , picture , create_at , map_url)
                                                             VALUES (? , ? , ? , ? , ? , ? , ? ,  now() , ? )");
            $res->bind_param('sissssss', $dbName, $dbCountry, $dbCity, $dbTypeFood, $dbNumber, $dbDescription, $dbPicture, $dbURL);
            $dbName = $resName;
            $dbCountry = $country;
            $dbCity = $city;
            $dbTypeFood = $tFood;
            $dbNumber = $number;
            $dbDescription = $desc;
            $dbPicture = $imgFields;
            $dbURL = $url;
            $res->execute();
            $resID = $res->insert_id;
            $opCl = $mysql->query("INSERT INTO open_close(res_id , day , open , close)
                                          VALUES($resID , 1 , '$openClose[sundayOpen]'   ,   '$openClose[sundayClose]')
                                          ,     ($resID , 2 , '$openClose[mondayOpen]'   , '$openClose[mondayClose]')
                                          ,     ($resID , 3 , '$openClose[tuesdayOpen]'  , '$openClose[tuesdayClose]')
                                          ,     ($resID , 4 , '$openClose[wednesdayOpen]', '$openClose[wednesdayClose]')
                                          ,     ($resID , 5 , '$openClose[thursdayOpen]' , '$openClose[thursdayClose]')
                                          ,     ($resID , 6 , '$openClose[fridayOpen]'   , '$openClose[fridayClose]')
                                          ,     ($resID , 7 , '$openClose[saturdayOpen]' , '$openClose[saturdayClose]') ");

            $keyword = $mysql->query("INSERT INTO keywords(res_id , name)
                                    VALUES ('$resID' , '$keyword')");

            $apps = $mysql->prepare("INSERT INTO `apps` (`res_id` , `mrsool` , `logmaty` , `hungerStation` , `jahiz` , `careemNow`)
                                        VALUES('$resID' , ? , ? , ? , ? , ?)");
            $apps->bind_param('sssss' , $dbMrsool , $dbLogmaty , $dbHunger , $dbJahiz , $dbCareem);
            $dbMrsool = $mrsool;
            $dbLogmaty = $logmaty;
            $dbHunger = $hunsta;
            $dbJahiz = $jahiz;
            $dbCareem = $careem;
            $apps->execute();

            if ($res->error) {
                echo $res->error;
                header('location:' . $_SERVER['HTTP_REFERER']);
                die();
            } else {
                successMessage("inserted $res->affected_rows Record successfully");
                header('refresh:2;url=restaurant.php');
                die();
            }

        } else {
            include '../error.php';
            header('refresh:10 ; url = ' . $_SERVER['HTTP_REFERER']);
        }

    } else {
        errorMessage('sorry you can\'t access this page directly');
        header('refresh:5 ; url=index.php');
        die();
    }

} elseif ($do == 'Delete') {
    $resID = isset($_GET['resid']) ? mysqli_real_escape_string($mysql, filter_var($_GET['resid'], FILTER_VALIDATE_INT)) : 0;
    $open = $mysql->query("DELETE FROM open_close WHERE res_id = $resID");
    $stmt = $mysql->prepare("DELETE FROM restaurant WHERE id = ?");
    $stmt->bind_param('i', $dbResID);
    $dbResID = $resID;
    $stmt->execute();
    if ($stmt->error) {
        echo 'please try again there\'s someting wrong';
        header('location:' . $_SERVER['HTTP_REFERER']);
        die();
    } else {
        successMessage("Deleted $stmt->affected_rows Record successfully");
        header('refresh:2; url=restaurant.php');
        die();
    }
} elseif ($do == 'Edit') { # This to edit restaurant page
    $resID = isset($_GET['resid']) ? mysqli_real_escape_string($mysql, filter_var($_GET['resid'], FILTER_VALIDATE_INT)) : 0;
    $stmt = $mysql->prepare("SELECT restaurant.* , country.name AS couName , city.name AS citName , keywords.name AS keyName  FROM restaurant 
                                                            INNER JOIN country ON country.id = restaurant.country
                                                            INNER JOIN city ON city.id = restaurant.city
                                                            INNER JOIN keywords ON keywords.res_id = restaurant.id 
                                                            WHERE restaurant.id = ?");
    $stmt->bind_param('i', $dbResID);
    $dbResID = $resID;
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $openClose = $mysql->query("SELECT day , open , close FROM open_close WHERE res_id = $resID")->fetch_all(MYSQLI_ASSOC); # This to get information about open and close restaurant
    ?>
    <div id="page-wrapper">
        <div id="page-inner">
            <div class="panel panel-default">
                <div class="panel-heading text-uppercase "><h3 class="text-primary">Update restaurant</h3></div>
                <div class="panel-body">
                    <form action="?do=Update" method="POST" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $result['id'] ?>" name="resid">
                        <div class="form-group">
                            <label for="name">Restaurant Name : </label>
                            <input type="text" class="form-control" id="resname" placeholder="Enter Restaurant name"
                                   name="resname" value="<?php echo $result['name'] ?>" required>
                        </div>

                        <label for="country">country</label>
                        <select class="form-control" id="country" name="country" onchange="changeCountry()" ">
                        <option value="<?php echo $result['country'] ?>"
                                selected><?php echo $result['couName'] ?></option>
                        <?php $stmt = $mysql->query("SELECT * FROM country ORDER BY name ASC");
                        while ($row = $stmt->fetch_assoc()) { ?>
                            <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                        <?php }
                        $stmt->close(); ?>
                        </select>

                        <label for="city">city</label>
                        <select class="form-control" id="city" name="city">
                            <option value="<?php echo $result['city'] ?>"
                                    selected><?php echo $result['citName'] ?></option>

                        </select>

                        <div class="form-group">
                            <label for="type_food">type food</label>
                            <input type="text" class="form-control" id="type_food"
                                   placeholder="Enter Type food Spilit With Quma (,) example (arabian , italian , fastfood)"
                                   name="type_food" value="<?php echo $result['type_food'] ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="number">Phone Number</label>
                            <input type="text" class="form-control" id="number"
                                   placeholder="Enter Phone number of restaurant"
                                   name="number" value="<?php echo $result['number'] ?>" required>
                        </div>


                        <div class="form-group">
                            <label for="textarea">Description :</label>
                            <textarea id="textarea" name="textarea" class="form-control" cols="30" rows="7"
                                      placeholder="Enter Description of restaurant"
                                      required><?php echo $result['description'] ?></textarea>
                        </div>


<!--                        <div class="form-group">-->
<!--                            <label for="images">images</label>-->
<!--                            <input type="file" multiple="multiple" class="form-group" name="images[]" id="images"-->
<!--                                   >-->
<!--                        </div>-->


                        <div class="form-group">
                            <label for="maps">Location on maps</label>
                            <input type="url" id="maps" value="<?php echo $result['map_url'] ?>" name="url"
                                   class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="keyword">key word</label>
                            <input type="text" id="keyword" value="<?php echo $result['keyName'] ?>" name="keyword"
                                   class="form-control"
                                   required>
                        </div>

                            <div class="form-inline">
                                Sunday :
                                <br>
                                <div class="form-group">
                                    <label for="sundayOpen">open : </label>
                                    <input type="text" class="form-control" id="sundayOpen"
                                           name="sundayOpen" value="<?php
                                    foreach ($openClose as $value) {
                                        if($value['day'] == 1) {
                                            echo $value['open'];
                                            break;
                                        }
                                    }
                                    ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="sundayClose">close : </label>
                                    <input type="text" class="form-control" id="sundayClose"
                                           name="sundayClose" value="<?php
                                    foreach ($openClose as $value) {
                                        if($value['day'] == 1) {
                                            echo $value['close'];
                                            break;
                                        }
                                    }
                                    ?>" required>
                                </div>
                            </div>

                            <div class="form-inline">
                                Monday :
                                <br>
                                <div class="form-group">
                                    <label for="mondayOpen">open : </label>
                                    <input type="text" class="form-control" id="mondayOpen"
                                           name="mondayOpen" value="<?php
                                    foreach ($openClose as $value) {
                                        if($value['day'] == 2) {
                                            echo $value['open'];
                                            break;
                                        }
                                    }
                                    ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="mondayClose">close : </label>
                                    <input type="text" class="form-control" id="mondayClose"
                                           name="mondayClose" value="<?php
                                    foreach ($openClose as $value) {
                                        if($value['day'] == 2) {
                                            echo $value['close'];
                                            break;
                                        }
                                    }
                                    ?>" required>
                                </div>
                            </div>

                            <div class="form-inline">
                                Tuesday :
                                <br>
                                <div class="form-group">
                                    <label for="tuesdayOpen">open : </label>
                                    <input type="text" class="form-control" id="tuesdayOpen"
                                           name="tuesdayOpen" value="<?php
                                    foreach ($openClose as $value) {
                                        if($value['day'] == 3) {
                                            echo $value['open'];
                                            break;
                                        }
                                    }
                                    ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="tuesdayClose">close : </label>
                                    <input type="text" class="form-control" id="tuesdayClose"
                                           name="tuesdayClose" value="<?php
                                    foreach ($openClose as $value) {
                                        if($value['day'] == 3) {
                                            echo $value['close'];
                                            break;
                                        }
                                    }
                                    ?>" required>
                                </div>
                            </div>

                            <div class="form-inline">
                                Wednesday :
                                <br>
                                <div class="form-group">
                                    <label for="wednesdayOpen">open : </label>
                                    <input type="text" class="form-control" id="wednesdayOpen"
                                           name="wednesdayOpen" value="<?php
                                    foreach ($openClose as $value) {
                                        if($value['day'] == 4) {
                                            echo $value['open'];
                                            break;
                                        }
                                    }
                                    ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="wednesdayClose">close : </label>
                                    <input type="text" class="form-control" id="wednesdayClose"
                                           name="wednesdayClose" value="<?php
                                    foreach ($openClose as $value) {
                                        if($value['day'] == 4) {
                                            echo $value['close'];
                                            break;
                                        }
                                    }
                                    ?>" required>
                                </div>
                            </div>

                            <div class="form-inline">
                                Thursday :
                                <br>
                                <div class="form-group">
                                    <label for="thursdayOpen">open : </label>
                                    <input type="text" class="form-control" id="thursdayOpen"
                                           name="thursdayOpen" value="<?php
                                    foreach ($openClose as $value) {
                                        if($value['day'] == 5) {
                                            echo $value['open'];
                                            break;
                                        }
                                    }
                                    ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="thursdayClose">close : </label>
                                    <input type="text" class="form-control" id="thursdayClose"
                                           name="thursdayClose" value="<?php
                                    foreach ($openClose as $value) {
                                        if($value['day'] == 5) {
                                            echo $value['close'];
                                            break;
                                        }
                                    }
                                    ?>" required>
                                </div>
                            </div>

                            <div class="form-inline">
                                Friday :
                                <br>
                                <div class="form-group">
                                    <label for="fridayOpen">open : </label>
                                    <input type="text" class="form-control" id="fridayOpen"
                                           name="fridayOpen" value="<?php
                                    foreach ($openClose as $value) {
                                        if($value['day'] == 6) {
                                            echo $value['open'];
                                            break;
                                        }
                                    }
                                    ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="fridayClose">close : </label>
                                    <input type="text" class="form-control" id="fridayClose"
                                           name="fridayClose" value="<?php
                                    foreach ($openClose as $value) {
                                        if($value['day'] == 6) {
                                            echo $value['close'];
                                            break;
                                        }
                                    }
                                    ?>" required>
                                </div>
                            </div>

                            <div class="form-inline">
                                Saturday :
                                <br>
                                <div class="form-group">
                                    <label for="saturdayOpen">open : </label>
                                    <input type="text" class="form-control" id="saturdayOpen"
                                           name="saturdayOpen" value="<?php
                                    foreach ($openClose as $value) {
                                        if($value['day'] == 7) {
                                            echo $value['open'];
                                            break;
                                        }
                                    }
                                    ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="saturdayClose">close : </label>
                                    <input type="text" class="form-control" id="saturdayClose"
                                           name="saturdayClose" value="<?php
                                    foreach ($openClose as $value) {
                                        if($value['day'] == 7) {
                                            echo $value['close'];
                                            break;
                                        }
                                    }
                                    ?>" required>
                                </div>
                            </div>
                        <button class="btn btn-primary" style="margin-top:18px;"> Update Restaurant</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
//} elseif ($do == 'Update') {
//    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//        $resID   = mysqli_real_escape_string($mysql, filter_var($_POST['resid'] , FILTER_SANITIZE_NUMBER_INT));
//        $resName = mysqli_real_escape_string($mysql, filter_var($_POST['resname'], FILTER_SANITIZE_STRING));
//        $country = mysqli_real_escape_string($mysql, filter_var($_POST['country'], FILTER_SANITIZE_NUMBER_INT));
//        $city    = mysqli_real_escape_string($mysql, filter_var($_POST['city'], FILTER_SANITIZE_NUMBER_INT));
//        $tFood   = mysqli_real_escape_string($mysql, filter_var($_POST['type_food'], FILTER_SANITIZE_STRING));
//        $number  = mysqli_real_escape_string($mysql, filter_var($_POST['number'], FILTER_SANITIZE_STRING));
//        $desc    = mysqli_real_escape_string($mysql, filter_var($_POST['textarea'], FILTER_SANITIZE_STRING));
//        $url     = mysqli_real_escape_string($mysql, filter_var($_POST['url'], FILTER_SANITIZE_URL));
//        $keyword = mysqli_real_escape_string($mysql, filter_var($_POST['keyword'], FILTER_SANITIZE_URL));
//        $openClose = [
//            'sundayOpen' => $_POST['sundayOpen'],
//            'sundayClose' => $_POST['sundayClose'],
//            'mondayOpen' => $_POST['mondayOpen'],
//            'mondayClose' => $_POST['mondayClose'],
//            'tuesdayOpen' => $_POST['tuesdayOpen'],
//            'tuesdayClose' => $_POST['tuesdayClose'],
//            'wednesdayOpen' => $_POST['wednesdayOpen'],
//            'wednesdayClose' => $_POST['wednesdayClose'],
//            'thursdayOpen' => $_POST['thursdayOpen'],
//            'thursdayClose' => $_POST['thursdayClose'],
//            'fridayOpen' => $_POST['fridayOpen'],
//            'fridayClose' => $_POST['fridayClose'],
//            'saturdayOpen' => $_POST['saturdayOpen'],
//            'saturdayClose' => $_POST['saturdayClose'],
//        ];
//
//        if (empty($resName) || empty($country) || empty($city) || empty($tFood) || empty($desc) || empty($url)) {
//            array_push($error, 'sorry make sure all fields not empty and have a valid value');
//        }
////        if (isset($_FILES['images']) && $_FILES['images']['error'][0] == 0) {
////            $AllFile = [];
////            $file = $_FILES['images'];
////            $allowSize = 2222200;
////            $allowFile = [
////                'jpg' => 'image/jpg',
////                'jpeg' => 'image/jpeg',
////                'png' => 'image/png'
////            ];
////            $num = count($file['name']);
////            if ($file['error'][0] == 0) {
////                for ($i = 0; $i < $num; $i++) {
////                    $error = [];
////                    $type = mime_content_type($file['tmp_name'][$i]);
////                    $fileSize = $file['size'][$i];
////                    if (!(in_array($type, $allowFile))) {
////                        array_push($error, "sorry this file can't allow uploaded file number " . ($i + 1));
////                    }
////                    if ($fileSize > $allowSize) {
////                        array_push($error, 'sorry this size bigger than ' . $allowSize . ' File number ' . ($i + 1));
////                    }
////                    if (!count($error)) {
////                        $rand = time() . $file['name'][$i];
////                        move_uploaded_file($file['tmp_name'][$i], $_SERVER['DOCUMENT_ROOT'] . '/fnbtime6/uploads/' . $rand);
////                        $AllFile[] = $rand;
////                    } else {
////                        include '../error.php';
////                    }
////                }
////                $imgFields = implode(',', $AllFile);
////
////            } else {
////                array_push($error, 'please uploaded one file at least');
////            }
//
//
//
//
//        if (!count($error)) {
//            $res = $mysql->prepare("UPDATE restaurant SET name = ? ,  country = ? , city = ? , type_food = ? , number = ? , description = ? , map_url = ? WHERE id = ?");
//            $res->bind_param('ssiisssi', $dbName, $dbCountry, $dbCity, $dbTypeFood, $dbNumber, $dbDescription, $dbURL , $dbResID);
//            $dbName     = $resName;
//            $dbCountry  = $country;
//            $dbCity     = $city;
//            $dbTypeFood = $tFood;
//            $dbNumber   = $number;
//            $dbDescription = $desc;
//            $dbURL = $url;
//            $dbResID = $resID;
//            $res->execute();
//            $i = 1;
//            foreach ($openClose as $item => $value) {
//                echo $item[$value];
////                $opCl = $mysql->query("UPDATE open_close
////                SET res_id = $resID , SET day = $i  , open = $value  , close = $value");
//                $i++;
//            }
//    die();
//
////            $opCl = $mysql->query("UPDATE open_close SET res_id = $resID , SET day = , open , close)
////                                          VALUES($resID , 1 , '$openClose[sundayOpen]'   ,   '$openClose[sundayClose]')
////                                          ,     ($resID , 2 , '$openClose[mondayOpen]'   , '$openClose[mondayClose]')
////                                          ,     ($resID , 3 , '$openClose[tuesdayOpen]'  , '$openClose[tuesdayClose]')
////                                          ,     ($resID , 4 , '$openClose[wednesdayOpen]', '$openClose[wednesdayClose]')
////                                          ,     ($resID , 5 , '$openClose[thursdayOpen]' , '$openClose[thursdayClose]')
////                                          ,     ($resID , 6 , '$openClose[fridayOpen]'   , '$openClose[fridayClose]')
////                                          ,     ($resID , 7 , '$openClose[saturdayOpen]' , '$openClose[saturdayClose]') ");
//
//            $keyword = $mysql->query("UPDATE keywords SET = res_id , name = $keyword");
//
//            if ($res->error) {
//                echo $res->error;
//                header('location:' . $_SERVER['HTTP_REFERER']);
//                die();
//            } else {
//                successMessage("inserted $res->affected_rows Record successfully");
//                header('refresh:2;url=restaurant.php');
//                die();
//            }
//
//        } else {
//            include '../error.php';
//            header('refresh:10 ; url = ' . $_SERVER['HTTP_REFERER']);
//        }
//
//    } else {
//        errorMessage('sorry you can\'t access this page directly');
//        header('refresh:5 ; url=index.php');
//        die();
//    }
//}
}
include $tpl . 'footer.php'; ?>
