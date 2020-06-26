<?php
session_start();
$getTitle = $_SESSION['username'] .' Details';
include_once 'init.php';
if (!(isset($_SESSION['username'])) && empty($_SESSION['username'])) {
    header('location:login.php');
    die();
}
?>
    <body>
    <!-- start Container Wrapper -->
<div class="container-wrapper">

<?php include_once 'includes/template/navbar.php' ?>


    <!-- start Main Wrapper -->
    <div class="main-wrapper scrollspy-container">

        <!-- start hero-header -->
        <div class="hero hero-breadcrumb" style="background-image:url('images/hero-header/hero-image.png');">

            <div class="container">

                <h1>all <?php echo $_SESSION['username'] ?> Reservation</h1>

            </div>

        </div>
        <div class="container pt-10 pb-30">

            <div class="breadcrumb-wrapper">

                <ol class="breadcrumb">

                    <li><a href="#">Home</a></li>
                    <li class="active"><?php echo $_SESSION['username'] ?> Reservation</li>

                </ol>

            </div>

            <div class="row mt-40 mb-30 text-center">

                <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                    <div class="section-title-02 mb-20">

                        <h3><span><?php echo $_SESSION['username'] ?> Reservation Details</span></h3>

                    </div>
                    <?php if (!empty(getClientBooking($_SESSION['userid']))) { ?>
                        <?php foreach (getClientBooking($_SESSION['userid']) as $result): ?>
                            <div class="reservation-summary-wrapper">

                                <ul class="reservation-summary-list">

                                    <li>
                                        <div class="image">
                                            <?php $img = explode(',', $result['resPic']); ?>
                                            <img src="uploads/<?php echo $img[0] ?>" alt="Restaurant image"/>
                                        </div>
                                    </li>

                                    <li>
                                        <span class="block text-muted text-uppercase">Restaurant</span>
                                        <h6><a href="restaurant-info.php?restaurant=<?php echo $result['res_id'] ?>"
                                               target="_blank"><?php echo $result['resName'] ?></a></h6>
                                    </li>

                                    <li>
                                        <span class="block text-muted text-uppercase">Date</span>
                                        <h6><?php echo $result['occasion_DATE'] ?></h6>
                                    </li>

                                    <li>
                                        <span class="block text-muted text-uppercase">Time</span>
                                        <h6><?php echo $result['time'] ?></h6>
                                    </li>

                                    

                                    <li>
                                        <span class="block text-muted text-uppercase">Guests</span>
                                        <h6><?php echo $result['person_number'] ?></h6>
                                    </li>

                                    <li>
                                        <span class="block text-muted text-uppercase">Booking Number</span>
                                        <h6><?php echo $result['booking_number'] ?></h6>
                                    </li>

                                    <li>

                                    <span class="btn btn-primary">
                                        <?php
                                        switch ($result['status']) {
                                            case 0 :
                                                echo 'pending';
                                                break;
                                            case 1 :
                                                echo 'confirm';
                                                break;
                                            case 2 :
                                                echo 'canceled';
                                                break;
                                        }
                                        ?>
                                    </span>
                                    </li>

                                </ul>

                            </div>
                    <?php endforeach; ?>
                    <?php } else {
                        echo "<h4 class='text-center text-danger' style='display: block'>You did not Add any booking Untill Now</h4>";
                    } ?>

                    <div class="submite-list-wrapper">

                        <div class="row">

                            <div class="col-md-8">

                                <div class="section-title-02 mb-20">

                                    <h3><span><?php echo $_SESSION['username'] ?> favourite restaurants</span></h3>

                                </div>

                            </div>

                        </div>
                <?php
                    $fav = getAllFavorite($_SESSION['userid']);

                    if(!empty($fav)) {


                        foreach ($fav as $result) {
                    ?>
                        <div class="reservation-summary-wrapper">

                            <ul class="reservation-summary-list">

                                <li>
                                    <div class="image">
                                        <?php $img = explode(',', $result['resPic']); ?>
                                        <img src="uploads/<?php echo $img[0] ?>" alt="Restaurant image"/>
                                    </div>
                                </li>

                                <li>
                                    <span class="block text-muted text-uppercase">Restaurant</span>
                                    <h6><a href="restaurant-info.php?restaurant=<?php echo $result['res_id'] ?>"><?php echo $result['resName'] ?></a></h6>
                                </li>

                                <li>
                                    <span class="block text-muted text-uppercase">Country</span>
                                    <h6><?php echo $result['couName'] ?></h6>
                                </li>

                                <li>
                                    <span class="block text-muted text-uppercase">City</span>
                                    <h6><?php echo $result['citName'] ?></h6>
                                </li>


                                <li>
                                    <?php
                                        # This is to remove favorite restaurant
                                        if($_SERVER["REQUEST_METHOD"] == 'POST') {
                                            $favID = mysqli_real_escape_string($mysql , filter_var($_POST['fav_id'] , FILTER_SANITIZE_NUMBER_INT));
                                            $stmt = $mysql->prepare('DELETE FROM favorite WHERE id = ?');
                                            $stmt->bind_param('i' , $dbFavID);
                                            $dbFavID = $favID;
                                            $stmt->execute();
                                            if($stmt->error)
                                                echo 'error please try again';
                                            else {
                                                echo "<script>location.href='client.php'</script>";
                                            }
                                        }
                                    # This is to remove favorite restaurant ?>
                                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                                        <input type="hidden" name="fav_id" value="<?php echo $result['id'] ?>">
                                        <button onclick="return confirm('Are You Sure ? ')" class="btn btn-primary">Remove</button>
                                    </form>
                                </li>

                            </ul>

                        </div>
                    <?php }
                    } else {
                        echo "<h4 class='text-center text-danger' style='display: block'>You did not Add any restaurant to favorite Untill Now</h4>";

                    } ?>


                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- start Footer Wrapper -->
    <div class="footer-wrapper scrollspy-footer">

<?php include_once $tpl . 'footer.php' ?>