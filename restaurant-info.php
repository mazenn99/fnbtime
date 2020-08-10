<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
$getTitle = 'Restaurant';
include_once 'init.php';
$error = [];
# Start This is to get All information of restaurant
$resId = isset($_GET['restaurant']) && intval($_GET['restaurant']) ? mysqli_real_escape_string($mysql, filter_var($_GET['restaurant'], FILTER_SANITIZE_NUMBER_INT)) : 0;
$stmt = $mysql->prepare('SELECT restaurant.* , country.name AS couName , city.name AS citName
                                                        FROM restaurant 
                                                        INNER JOIN country ON country.id = restaurant.country 
                                                        INNER JOIN city ON city.id = restaurant.city
                                                        WHERE restaurant.id = ?
                                                        ');
$stmt->bind_param('i', $dbresID);
$dbresID = $resId;
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
# End This is to get All information of restaurant
$openClose = $mysql->query("SELECT day , open , close FROM open_close WHERE res_id = $resId")->fetch_all(MYSQLI_ASSOC); # This to get information about open and close restaurant
if($_SERVER['REQUEST_METHOD'] == 'POST') { # Start This to create new booking

    $fullName = mysqli_real_escape_string($mysql , filter_var($_POST['FullName'] , FILTER_SANITIZE_STRING));
    $email    = mysqli_real_escape_string($mysql , filter_var($_POST['email']    , FILTER_SANITIZE_STRING));
    $phone    = mysqli_real_escape_string($mysql , filter_var($_POST['phone']    , FILTER_SANITIZE_NUMBER_INT));
    $date     = mysqli_real_escape_string($mysql , filter_var($_POST['date']     , FILTER_SANITIZE_STRING));
    $time     = mysqli_real_escape_string($mysql , filter_var($_POST['time']     , FILTER_SANITIZE_STRING));
    $persons  = mysqli_real_escape_string($mysql , filter_var($_POST['persons']  , FILTER_SANITIZE_NUMBER_INT));
    if(empty($fullName) || empty($email) || empty($phone) || empty($date) || empty($time) || empty($persons)) {
        array_push($error , 'please fill all fields with valid value');
    }
    if(!count($error)) {
        $bookingNumber = random_int(1 , 999999);
        $stmt = $mysql->prepare("INSERT INTO `booking` (`user_id`, `res_id`, `booking_number` , `name` , `phone_coustmer` , `person_number` , `time` , `date_booking`, `occasion_DATE`)
                                                              VALUES(? , ? , ? , ? , ? , ? , ? , now() , ?)");
        $stmt->bind_param('iiisssss' , $dbUserID , $dbResID , $dbBookingNumber , $dbName , $dbPhoneCoustmer , $dbPersonNumber , $dbTime , $dbOccasionDate);
        $dbUserID = $_SESSION['userid'];
        $dbResID = $resId;
        $dbBookingNumber = $bookingNumber;
        $dbName = $fullName;
        $dbPhoneCoustmer = $phone;
        $dbPersonNumber = $persons;
        $dbTime = $time;
        $dbOccasionDate = $date;
        $stmt->execute();
        if($stmt->error) {
            echo 'sorry there is something wrong please try again';
            echo $stmt->error;die();
            header('refresh:10; url=' .$_SERVER['HTTP_REFERER']);
            die();
        } else {
            $_SESSION['resid'];
            $_SESSION['booking'] = $bookingNumber;
            header('location:restaurant-reservation-success.php');
            die();
        }
    }
} # End This to create new booking

?>

    <div id="introLoaderRestaurant" class="introLoading"></div>

    <!-- start Container Wrapper -->
    <div class="container-wrapper">

        <?php include_once 'includes/template/navbar.php' ?>

        <!-- start Main Wrapper -->
        <div class="main-wrapper scrollspy-container">

            <!-- start hero-header -->
            <div class="hero hero-detail">

                <div class="container">

                    <div class="hero-detail-inner">

                        <div id="detail-content-sticky-nav-00" class="hero-detail-bottom">

                            <div class="GridLex-grid-bottom">

                                <div class="GridLex-col-8_sm-7_xs-12_xss-12">
                                    <div class="detail-header">
                                        <div class="detail-header-inner"></div>
                                    </div>
                                </div>
                                <?php
                                if(isset($_SESSION['username']) && !empty($_SESSION['username'])):
                                    $stmt = $mysql->prepare("SELECT favorite.* , users.name AS uName , restaurant.name AS resName FROM favorite
                                                        INNER JOIN users ON users.id = favorite.user_id
                                                        INNER JOIN restaurant ON restaurant.id = favorite.res_id
                                                        WHERE favorite.res_id = ?");
                                    $stmt->bind_param('i' , $resID);
                                    $resID = $resId;
                                    $stmt->execute();
                                    $favRows = $stmt->get_result();
                                    if(!($favRows->num_rows)) {?>
                                        <div class="GridLex-col-4_sm-4_xs-12_xss-12">
                                        <div class="text-right text-left-xs">
                                            <div class="btn-holder mt-5">
                                                <button class="btn btn-light anchor-alt" value="<?php echo $result['id'] ?>" id="favorite">Save This Restaurant <i class="fa fa-heart"></i> </button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }
                                    endif;?>

<!--                                    --><?php //} ?>
                            </div>
<!--                            --><?php //endif; ?>

                        </div>

                    </div>

                </div>

            </div>

            <div class="container pt-10 pb-50">

                <div class="breadcrumb-wrapper">

                    <ol class="breadcrumb">

                        <li><a href="index.php">Home</a></li>
                        <li class="active">Restaurant detail</li>

                    </ol>

                </div>

                <div class="row">

                    <div class="col-xs-12 col-sm-8 col-md-9 mb-30">

                        <div class="multiple-sticky for-detail-page">

                            <div class="multiple-sticky-inner">

                                <div class="multiple-sticky-container container">

                                    <div class="multiple-sticky-item clearfix">

                                        <ul id="top-menu" class="multiple-sticky-nav clearfix">
                                            <li>
                                                <a href="#detail-content-sticky-nav-00">Overview</a>
                                            </li>
                                            <li>
                                                <a href="#detail-content-sticky-nav-02">Review</a>
                                            </li>
                                            <li>
                                                <a href="#detail-content-sticky-nav-03">Photo</a>
                                            </li>
                                            <li>
                                                <a href="#detail-content-sticky-nav-04">Location</a>
                                            </li>
                                        </ul>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="clear mb-40"></div>

                        <div class="detail-content-for-sticky-menu for-detail-page">

                            <div class="detail-content-section clearfix">

                                <div class="section-title-02">
                                    <h3><span>Overview</span></h3>
                                </div>

                                <p class="font500"></p>

                                <div class="driver-icon section-title">
                                    <?php $data = getAppDelivery($_GET['restaurant']);
                                    foreach ($data as $item) {

                                    ?>
                                    <div class="section-title-02">
                                        <h3><span>support in</span></h3>
                                    </div>
                                    <?php if(!empty($item['mrsool'])): ?>
                                            <a href="<?php echo $item['mrsool'] ?>"
                                               target="_blank"><img src="images/mrsool.png"></a> <br>
                                            <?php endif; ?>

                                    <?php if(!empty($item['logmaty'])): ?>
                                            <a href="<?php echo $item['logmaty'] ?>"
                                               target="_blank"><img src="images/logmaty.png"></a> <br>
                                           <?php endif; ?>

                                    <?php if(!empty($item['hungerStation'])): ?>
                                        <a href="<?php echo $item['hungerStation'] ?>"
                                           target="_blank"><img src="images/hungerstation.png"></a> <br>
                                    <?php endif; ?>

                                    <?php if(!empty($item['jahiz'])): ?>
                                        <a href="<?php echo $item['jahiz'] ?>"
                                           target="_blank"><img src="images/jahiz.png"></a> <br>
                                    <?php endif; ?>

                                    <?php if(!empty($item['careemNow'])): ?>
                                        <a href="<?php echo $item['careemNow'] ?>"
                                           target="_blank"><img src="images/careemNow.png"></a>
                                    <?php endif; ?>

                                    <?php } ?>
                                </div>
                                <div class="row">

                                    <div class="col-xs-12 col-sm-6 mb-20">

                                        <div class="contact-box">

                                            <h5 class="text-primary">Contact Information</h5>


                                            <ul class="contact-list"></ul>

                                            <a href="#" target="_blank" class="btn btn-primary btn-sm anchor-alt">See map &amp; get route</a>
                                        </div>

                                    </div>

                                    <div class="col-xs-12 col-sm-6">

                                        <div class="open-time-box">

                                            <h5 class="text-primary">Opening Time</h5>

                                            <ul class="open-time-list"></ul>

                                        </div>

                                    </div>

                                </div>

                            </div>
                            
                            <div id="detail-content-sticky-nav-02" class="detail-content-section clearfix">
                                <div class="section-title-02">
                                    <h3><span>Review</span></h3>
                                </div>
                                <div class="review-wrapper">
                                    <div class="review-header">
                                        <div class="GridLex-gap-30">
                                            <div class="GridLex-grid-middle">
                                                <div class="GridLex-col-4_sm-5_xs-12">
                                                    <div class="average-score"></div> <!-- rating and review values are added here -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="review-item-wrapper">
                                        <ul class="review-item-list"></ul> <!-- user reviews are added here -->
                                    </div>
                                </div>
                                <div class="clear mb-15"></div>
                            </div>

                            <div id="detail-content-sticky-nav-03" class="detail-content-section clearfix">

                                <div class="section-title-02">
                                    <h3><span>Photo</span></h3>
                                </div>

                                <div id="detail-food-photo"></div>

                                <div class="clear mb-15"></div>

                            </div>

                            <div id="detail-content-sticky-nav-04" class="detail-content-section clearfix">
                                <div class="section-title-02">
                                    <h3><span>Location</span></h3>
                                </div>
                                <div class="map-holder">
                                    <!-- map -->
                                    <div id="hotel-detail-map" data-lat="25.19739" data-lon="55.28821" style="width: 100%; height: 480px;"></div>
                                </div>
                            </div>

                        </div>

                        <div class="multiple-sticky">
                            <div class="hidden">is used to stop multi-sticky</div>
                        </div>

                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3">

                        <div class="deal-sm clearfix mt-10">


                        </div>

                        <div class="reserve-box mt-30">

                            <h5 class="text-center">Reserve your table</h5> <!-- add class text-center -->
                            <div class="form-wrapper">
                                <?php include_once 'error.php'?>
                                <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] . '?restaurant=' . $resId ?>">

                                    <div class="row">

                                        <div class="col-xs-12 col-sm-12">

                                            <div class="input-group mb-15">
                                            <span class="input-group-addon"><i
                                                        class="glyphicon glyphicon-user"></i></span>
                                                <input type="text" name="FullName" placeholder="Full Name" class="form-control" required/>
                                            </div>

                                            <div class="input-group mb-15">
                                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                                <input type="email" name="email" placeholder="Email Address" class="form-control" required/>
                                            </div>

                                            <div class="input-group mb-15">
                                                <span class="input-group-addon"><i
                                                            class="glyphicon glyphicon-earphone"></i></span>
                                                <input type="text" name="phone" placeholder="Phone Number" class="form-control" required/>
                                            </div>

                                            <div class="input-group mb-15">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="date" name="date" placeholder="dd/mm/yyyy" class="form-control" required/>
                                            </div>

                                            <div class="row gap-15">

                                                <div class="col-xs-6 col-sm-6">

                                                    <div class="input-group mb-15">
                                                    <span class="input-group-addon"><i
                                                                class="glyphicon glyphicon-time"></i></span>
                                                        <input type="time" name="time" placeholder="hh-mm" class="form-control" required/>
                                                    </div>

                                                </div>

                                                <div class="col-xs-6 col-sm-6">

                                                    <div class="input-group mb-15">
                                                        <span class="input-group-addon"><i
                                                                    class="fa fa-male"></i></span>
                                                        <input type="number" name="persons" placeholder="Persons" class="form-control" required/>
                                                    </div>

                                                </div>

                                            </div>

                                            <div class="working-status"></div> <!-- open or closed is added here -->

                                            <?php if (isset($_SESSION['username'])) { # this to check if user verify on system or not
                                                $valid = getVerify($_SESSION['userid']);
                                                if ($valid['verification']) {
                                                    ?>
                                                    <div class="text-center">
                                                        <button class="btn btn-primary btn-block">Reserve now</button>
                                                    </div>
                                                <?php } else {
                                                    echo "<p class='text-center text-capitalize text-danger'>Please Verify You're Email</p>";
                                                }
                                            } else {
                                                echo "<p class='text-primary text-center'><a href='login.php' href='login.php' target='_blank'>Please Register or login</a></p>";
                                            } ?>

                                        </div>

                                    </div>

                                </form>

                            </div>

                        </div>


                    </div>

                </div>

                <div class="row mt-30 container">

                    <div class="col-md-12">

                        <div class="section-title-02">

                            <h3><span>You may also like</span></h3>

                        </div>

                    </div>

                </div>

                <div class="GridLex-gap-30 container">

                    <div class="GridLex-grid-noGutter-equalHeight"></div> <!-- other restaurants in same city are added here -->
                </div>

            </div>

        </div>
    </div>

    <!-- start Footer Wrapper -->
    <div class="footer-wrapper scrollspy-footer">

<?php include_once $tpl . 'footer.php' ?>

