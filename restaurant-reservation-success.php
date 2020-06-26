<?php
session_start();
$getTitle = 'Success Reservation';
if(!isset($_SESSION['username'])) {
    header('location:index.php');
    die('Access Denied');
}
require_once 'init.php';
if(isset($_SESSION['booking']) &&  !empty($_SESSION['booking'])) {
    $stmt = $mysql->prepare("SELECT booking.* , restaurant.name AS resName , users.name AS useName , users.email AS email 
                                  FROM booking INNER JOIN users ON users.id = booking.user_id 
                                  INNER JOIN restaurant ON restaurant.id = booking.res_id 
                                  WHERE booking_number = ?
                                   ");
    $stmt->bind_param('i' , $dbBookingNumber);
    $dbBookingNumber = $_SESSION['booking'];
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $location = $mysql->query("SELECT restaurant.country , restaurant.city , restaurant.number AS resNumber , country.name AS couName , city.name AS citName FROM restaurant
                                INNER JOIN country ON restaurant.country = country.id
                                INNER JOIN city ON city.id = restaurant.city 
                                ")->fetch_assoc();
}
require_once $sendEm . 'SendEmail.php';
$sendEmail = new SendEmail();
$sendEmail->sendBookingInfo($result['email'] , $result['name'] , $result['resName'] , $location['couName'] , $location['citName'] , $result['person_number'] , $result['phone_coustmer'] ,  $_SESSION['booking'] , $result['time'] , $result['occasion_DATE']);
?>

<body class="full-width-container">

	<!-- start Container Wrapper -->
	<div class="container-wrapper">
        <?php include $tpl . 'navbar.php'?>
		
		<!-- start Main Wrapper -->
		<div class="main-wrapper scrollspy-container">
		
			<!-- start hero-header -->
			<div class="hero hero-breadcrumb" style="background-image:url('images/hero-header/hero-image.png');">
			
				<div class="container">

					<p>You have successfully booked your table at</p>
					<h1><?php echo $result['resName'] ?> Restaurant </h1>

				</div>
				
			</div>

			<div class="container pt-10 pb-30">
			
				<div class="breadcrumb-wrapper">
				
					<ol class="breadcrumb">
					
						<li><a href="index.php">Home</a></li>
						<li class="active">Reserve successful</li>
						
					</ol>
					
				</div>
				
				<div class="row mt-40 mb-30">

					<div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
						
						<div class="alert alert-success alert-icon">
						
							<i class="fa fa-check-circle"></i>
							
                            <h4>Your reservation has been Received On Number <strong><?php echo $_SESSION['booking'] ?></strong> We Confirmed To you less than 24 hours</h4>
						
						</div>
						
						<div class="clear mb-10"></div>
						
						<h3>Hello <?php echo $_SESSION['username'] ?></h3>
						
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque deleniti et porro sapiente vero. Aliquam at aut delectus deleniti dolores dolorum esse, ex excepturi exercitationem fuga, inventore ipsa iure labore laborum molestiae natus neque nisi nulla numquam odio possimus quasi quibusdam quos rem suscipit totam voluptatibus? Architecto aut blanditiis deleniti ducimus excepturi nesciunt non numquam provident reprehenderit voluptates. Excepturi, repudiandae!</p>
						
						<div class="clear mt-30 mb-30" style="border-bottom: 3px double #D9D8D7;"></div>
						
						<div class="row">
						
							<div class="col-xs-12 col-sm-6 mb-30-xs">
							
								<h5 class="text-primary">Your reservation details</h5>
								
								<ul class="list-with-icon mt-25 mb-0">
									<li>
										<i class="fa fa-calendar"></i>
										<?php echo $result['occasion_DATE'] ?>
									</li>
                                    <li>
                                        <i class="fa fa-clock-o"></i>
                                        <?php echo $result['time']; ?>
                                    </li>
									<li>
										<i class="fa fa-user"></i>
										<?php ?>
									</li>
									<li>
										<i class="fa fa-user"></i>
										<?php echo $result['person_number'] ?> Persons
									</li>
								</ul>
								
							</div>
							
							<div class="col-xs-12 col-sm-6">
							
								<h5 class="text-primary">Restaurants details</h5>
								
								<ul class="list-with-icon mt-25 mb-0">
									<li>
										<i class="fa fa-cutlery"></i>
										<h6><A href="restaurant-info.php?restaurant=<?php echo $result['res_id'] ?>"><?php echo $result['resName'] ?></a></h6>
									</li>
									<li>
										<i class="fa fa-map-marker"></i>
										<?php echo $location['couName'] . ' , ' . $location['citName'] ?>
									</li>
									<li>
										<i class="fa fa-phone"></i>
										<?php echo $location['resNumber'] ?>
									</li>
								</ul>
								
							</div>
							
						</div>
						

						
					</div>

				</div>

			</div>
			
		</div>
		
		<!-- start Footer Wrapper -->
		<div class="footer-wrapper scrollspy-footer">
		
			<?php include_once $tpl . 'footer.php';
			?>