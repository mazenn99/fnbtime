<?php
require_once __DIR__ . '/../../init.php';
?>
<header id="header">
    <!-- start Navbar -->
    <nav class="navbar navbar-default navbar-fixed-top ">

        <div class="header-inner">

            <div class="navbar-header">
                <a class="navbar-brand hidden-xs" href="index.php"><img id="logo-img" src="images/logo_only.svg"
                                                                        alt="Image"/></a>
                <a class="navbar-brand visible-xs" href="index.php"><img id="logo-img"
                                                                         src="images/logo_only.svg"
                                                                         alt="Image"/></a>
            </div>

            <div id="navbar" class="collapse navbar-collapse navbar-arrow pull-left">

                <ul class="nav navbar-nav" id="responsive-menu">
                    <li>
                        <a href="index.php">Home</a>
                    </li>
                    <li>
                        <a href="restaurant.php">Restaurant</a>
                    </li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="faq.php">FAQ</a></li>
                </ul>
            </div><!--/.nav-collapse -->

            <div class="pull-right">
                <div class="navbar-mini">
                    <ul class="clearfix">
                        <li class="user-action">
                            <?php if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
                                ?>
                                <li class="dropdown bt-dropdown-click">
									<a id="language-dropdown" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <?php  $stmt = getUserVerification($_SESSION['userid']);
                                        if($stmt['verification']) { ?>
                                        <i class="fa fa-check" data-toggle="tooltip" data-placement="bottom" title="You're Account Is verify" style="margin-right: 2px;background-color: #27ae60;padding: 3px 5px;color: #FFF;border-radius: 50%;;"></i>
                                        <?php } else { ?>
                                        <i class="fa fa-close" data-toggle="tooltip" data-placement="bottom" title="You're Account Isn't verify" style="margin-right: 2px;background-color: #c0392b;padding: 3px 5px;color: #FFF;border-radius: 50%;;"></i>
                                        <?php } ?>
                                        <i class="fa fa-user mr-5"></i>
                                        <?php
                                        echo $_SESSION['username'] ?>
										<span class="caret"></span>
									</a>
									<ul class="dropdown-menu" aria-labelledby="language-dropdown">
										<li><a href="client.php">My Profile</a></li>
										<li><a href="edit.php">Edit Profile</a></li>
										<li><a href="logout.php">logout</a></li>
									</ul>
								</li>
                            <?php } else {?>
                            <a href="login.php" class="btn btn-primary btn-inverse btn-sm">Sign up/in</a>
                            <?php } ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="slicknav-mobile"></div>
    </nav>
    <!-- end Navbar -->

</header>
<!-- end Header -->