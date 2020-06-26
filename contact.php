<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
$getTitle = 'Contact Us';
$error = [];
session_start();
include_once 'init.php'; if($_SERVER['REQUEST_METHOD'] == "POST") {
        $user  = mysqli_real_escape_string($mysql , filter_var($_POST['name'] , FILTER_SANITIZE_STRING));
        $email = mysqli_real_escape_string($mysql , filter_var($_POST['email'] , FILTER_SANITIZE_STRING));
        $text  = mysqli_real_escape_string($mysql , filter_var($_POST['textarea'] , FILTER_SANITIZE_STRING));

        if(empty($user)) {
            array_push($error , 'sorry please fill user fields');
        }
        if(empty($email)) {
            array_push($error , 'sorry please fill email fields');
        }
        if(empty($text)) {
            array_push($error , 'sorry please fill textarea fields');
        }

        if(!count($error)) {



        } else {
            array_push($error , 'please try another time');
        }

    }
?>
    <body class="full-width-container">

    <div id="introLoader" class="introLoading"></div>

    <!-- start Container Wrapper -->
<div class="container-wrapper">

<?php include_once $tpl . 'navbar.php' ?>


    <!-- start Main Wrapper -->
    <div class="main-wrapper scrollspy-container">

        <div class="container pt-10 pb-50">

            <div class="breadcrumb-wrapper">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active">Contact Us</li>
                </ol>
            </div>

            <div class="mt-40">

                <div class="section-title-02 text-center">

                    <h3><span>Contact Us</span></h3>
                    <p>Was are delightful solicitude discovered collecting man day. Resolving neglected sir tolerably
                        but existence conveying for.</p>

                </div>

                <?php include_once 'error.php'?>

                <h6 class="text-center">Send us a Message</h6>

                <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" class="col-lg-offset-4 col-md-offset-4">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Your Name required" name="name" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="Your Email required" name="email" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <textarea name="textarea" id="textarea" placeholder='Please enter the message here' cols="30" rows="10" class="form-control" required></textarea>
                            </div>
                        </div>
                    </div>


                    <button class="btn btn-primary mb-30">Send Message</button>

                </form>


                <!-- start Footer Wrapper -->
                <div class="footer-wrapper scrollspy-footer">

<?php include_once $tpl . 'footer.php' ?>