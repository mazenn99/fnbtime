<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('location:index.php');
    die('Access Denied');
}
$getTitle = 'Dashboard';
require_once 'init.php' ?>
<div id="wrapper">
    <?php include_once $tpl . 'navbar.php'?>
    <!-- /. NAV TOP  -->
    <nav class="navbar-default navbar-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav" id="main-menu">


                <li class="active-link">
                    <a href="dashboard.php"><i class="fa fa-desktop "></i>Dashboard</a>
                </li>

                <li>
                    <a href="https://sg2plcpnl0079.prod.sin2.secureserver.net:2096/" target="_blank"><i class="fa fa-envelope-o"></i>Mail box</a>
                </li>

                <li>
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
                    <h2 class="text-uppercase"><i class="fa fa-lock" style="margin: 0 15px;"></i>admin dashboard</h2>
                </div>
            </div>
            <!-- /. ROW  -->
            <hr/>
            <!-- /. ROW  -->
            <div class="row text-center pad-top">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
                    <div class="div-square">
                        <a href="https://sg2plcpnl0079.prod.sin2.secureserver.net:2096/" target="_blank">
                            <i class="fa fa-envelope-o fa-5x"></i>
                            <h4>Mail Box</h4>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
                    <div class="div-square">
                        <a href="restaurant.php">
                            <i class="fa fa-cutlery fa-5x"></i>
                            <h4>All Restaurant</h4>
                            <h4 class="badge"><?php echo totalCount('id' , 'restaurant'); ?></h4>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
                    <div class="div-square">
                        <a href="last-booking.php">
                            <i class="fa fa-bookmark fa-5x"></i> <br>
                            <h4>Last Booking</h4>
                            <h4 class="badge"><?php echo totalCount('id' , 'booking' , 'WHERE status = 0') ?></h4>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
                    <div class="div-square">
                        <a href="users.php">
                            <i class="fa fa-users fa-5x"></i> <br>
                            <span class="text-success " ><i class="fa fa-check-circle" style="margin: 0 5px"> </i>(<?php echo totalCount('id' , 'users' , 'WHERE verification = 1'); ?>)</span>
                            <span class="text-success " ><i class="fa fa-times-circle" style="margin: 0 5px"> </i>(<?php echo totalCount('id' , 'users' , 'WHERE verification = 0'); ?>)</span>

                            <h4 style="margin: 0">All Users</h4>
                            <h4 class="badge"><?php echo totalCount('id' , 'users'); ?></h4>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
                    <div class="div-square">
                        <a href="allbooking.php">
                            <i class="fa fa-bookmark fa-5x"></i> <br>
                            <span class="text-success " ><i class="fa fa-check-circle" style="margin: 0 5px"> </i>(<?php echo totalCount('id' , 'booking' , 'WHERE status = 1'); ?>)</span>
                            <span class="text-success " ><i class="fa fa-exclamation-circle" style="margin: 0 5px"> </i>(<?php echo totalCount('id' , 'booking' , 'WHERE status = 2'); ?>)</span>
                            <span class="text-success " ><i class="fa fa-times-circle" style="margin: 0 5px"> </i>(<?php echo totalCount('id' , 'booking' , 'WHERE status = 0'); ?>)</span>
                            <h4>All Booking</h4>
                            <h4 class="badge"><?php echo totalCount('id' , 'booking') ?></h4>
                        </a>
                    </div>
                </div>
            </div>
            <!-- /. ROW  -->

            <!-- /. ROW  -->
            <!-- /. ROW  -->
        </div>
        <!-- /. PAGE INNER  -->
    </div>
    <!-- /. PAGE WRAPPER  -->
</div>

<?php include_once $tpl . 'footer.php'?>