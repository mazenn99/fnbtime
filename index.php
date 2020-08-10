<?php
ini_set('display_errors', 1);
error_reporting(-1);
session_start();
include_once 'init.php';
$allowDo = ['resend']; # This is allow $_GET
if (isset($_GET['do']) && in_array($_GET['do'] , $allowDo)) { # This is to resend to user email verification
    require_once $sendEm . 'SendEmail.php';
    $sendEm = new SendEmail();
    if(isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        $stmt = $mysql->prepare("SELECT verification FROM users WHERE email = ?");
        $stmt->bind_param('s' , $dbEmail);
        $dbEmail = $_SESSION['email'];
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if($result['verification'] === 0) {
            deleteTokens($_SESSION['userid']); # This to delete all old Tokens
            $token = bin2hex(random_bytes(30));
            $expire = date('Y-m-d H:i:s', strtotime('+120 min'));
            $verify = $mysql->query("INSERT INTO `verification` (`user_id` ,  `token` , `create_at` , `expire_at`)
                                            VALUES('$_SESSION[userid]' , '$token' , now() , '$expire')");
            $sendEm->sendVerificationEmail($_SESSION['email'] , $_SESSION['username'] , $token);
            $_SESSION['sendMessage'] = true;
        } else {
            echo 'sorry You are already verified You\'re Account you can\'t verified again';
            die();
        }
    } else {
        echo 'sorry There\'s some error please sign in and try again';
        die();
    }
}
?>
    <div id="introLoader" class="introLoading"></div>

    <!-- start Container Wrapper -->
    <div class="container-wrapper">
<?php include $tpl . 'navbar.php' ?>
<?php if (!empty($_SESSION['success_message'])): ?>
    <div class="alert alert-dismissible text-center alert-success margin-0">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>You Successfully Logout See You soon</strong>
    </div>
    <?php unset($_SESSION['success_message']); endif;
?>

<?php
    if(isset($_SESSION['userid'])) {
    $stmt = getUserVerification($_SESSION['userid']);
    if ($stmt['verification'] == 0):
    if(isset($_SESSION['sendMessage']) == false) { ?>


        <div class="alert alert-dismissible text-center alert-success margin-0">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong><?php echo "We Send An Email To $_SESSION[email]  Please Verify it , check You're junk mail" ?> , To resend Again please <a href="?do=resend" style="margin-left: 10px" class="btn btn-primary btn-sm" id='resend'>Click Here</a href="#"></strong>
        </div>
    <?php

} else { ?>
        <div class="alert alert-dismissible text-center alert-success margin-0">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>successfully resend An Email To <?php echo $_SESSION['email'] ?> Check it please , check junk mail</strong>
        </div>
    <?php }

    endif;
    }?>

    <!-- map container which is hidden but needed for geocoding and places api usage -->
    <div class="map-holder" style="display: none;">
        <div id="hotel-detail-map" data-lat="25.19739" data-lon="55.28821" style="width: 100%; height: 480px;"></div>
    </div>
    
    <!-- start Main Wrapper -->
    <div class="main-wrapper scrollspy-container">

        <div id="kb" class="carousel kb_elastic kb_wrapper hero-kb_elastic hero-kb_elastic-alt-height"
             data-ride="carousel" data-interval="4000" data-pause="false">

            <div class="carousel-inner" role="listbox">

                <div class="item active img-bg" style="background-image:url('images/hero-header-slider/hero-image.png');">
                </div>

                <div class="item img-bg" style="background-image:url('images/hero-header-slider/hero-image.png');">
                </div>

                <div class="item img-bg" style="background-image:url('images/hero-header-slider/hero-image.png');">
                </div>

                <div class="kb_overlay"></div>

            </div>

            <div class="hero-kb_elastic-inner text-center"> <!-- add class text-center to center the content -->

                <div class="container">

                    <h1>All You're Fine Dine booking</h1>
                    <p>More than 20,000 restaurants all around the world and in your country or city</p>

                    <div class="home-search-form mt-20-xs">

                        <div class="clear"></div>

                        <div class="home-search-form" style="display: inline-block;text-align: center">

                            <form action="restaurant.php" method="GET">

                                <div class="form-group location-form">
                                    <input type="text" id="search" name="search" class="form-control"
                                           placeholder="What would you like to eat?">
                                </div>

                                <button class="btn btn-primary btn-form">Find a Table</button>

                            </form>

                            <div class="list-group" id="show-list-search" style="display: none;">

                            </div>

                        </div>
                    </div>


                </div>

            </div>

        </div>

        <section class="bg-white">

            <div class="container">

                <div class="row">

                    <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">

                        <div class="section-title-02 text-center">

                            <h3><span>FNBTime Booking Restaurant</span></h3>

                            <p>We Only Lead You To The Best Places On Earth
                            </p>

                        </div>

                    </div>

                </div>

                <div class="restaurant-grid-wrapper mb-30">
                    <div class="GridLex-gap-30 GridLex-gap-20-mdd">
                        <div class="GridLex-grid-noGutter-equalHeight GridLex-grid-center">
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <a href="restaurant.php" class="btn btn-primary">More Restaurants</a>
                </div>

            </div>

        </section>

        <section>

            <div class="container">

                <div class="row">

                    <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">

                        <div class="section-title-02 text-center">

                            <h3><span>How It Works</span></h3>

                            <p>it's Just three Ways And you can reserve you're best restaurant.</p>

                        </div>

                    </div>

                </div>

                <div class="process-wrapper">

                    <div class="GridLex-gap-30 GridLex-gap-20-xs">

                        <div class="GridLex-grid-noGutter-equalHeight GridLex-grid-center">

                            <div class="GridLex-col-3_sm-6_xs-6_xss-12">

                                <div class="process-item">

                                    <div class="process-inner">

                                        <div class="number">
                                            01
                                        </div>

                                        <div class="content">

                                            <div class="icon"><i class="flaticon-restaurant-elements-placeholder"></i>
                                            </div>
                                            <h6>Search by Name</h6>
                                            <p>You can search restaurant for name and Tags Or keyword.</p>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="GridLex-col-3_sm-6_xs-6_xss-12">

                                <div class="process-item">

                                    <div class="process-inner">

                                        <div class="number">
                                            02
                                        </div>

                                        <div class="content">

                                            <div class="icon"><i class="flaticon-restaurant-elements-restaurant"></i>
                                            </div>
                                            <h6>Select a restaurant</h6>
                                            <p>You can select You're best restaurant and you can see everything </p>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="GridLex-col-3_sm-6_xs-6_xss-12">

                                <div class="process-item">

                                    <div class="process-inner">

                                        <div class="number">
                                            03
                                        </div>

                                        <div class="content">

                                            <div class="icon"><i class="flaticon-restaurant-elements-dish"></i></div>
                                            <h6>Reserve a table</h6>
                                            <p>You can Reserve a table for restaurant and we confirm it </p>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>
            <div id="x"></div>
    <!-- end Main Wrapper -->

    <!-- start Footer Wrapper -->
    <div class="footer-wrapper scrollspy-footer">

<?php include_once $tpl . 'footer.php' ?>