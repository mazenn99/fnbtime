<footer class="secondary-footer">

        <div class="container">

            <h6>Contact us</h6>
            <ul class="social-footer clearfix">
                <li><a href="https://www.instagram.com/fnbtime" target="_blank"><i class="fa fa-instagram"></i></a></li>
                <li><a href="https://twitter.com/fnbtime1" target="_blank"><i class="fa fa-twitter"></i></a></li>
                <li><a href="https://wa.me/966530163633" target="_blank"><i class="fa fa-whatsapp"></i></a></li>
            </ul>
            <!-- SLL Certificate -->
            <div class="sslCertificate">
                <span id="siteseal"><script async type="text/javascript" src="https://seal.starfieldtech.com/getSeal?sealID=uPcwi2FXuhKY0HceGA6YSUxCufTUtxD6hhm2vi94CaepfwdMCbI6TGBkk0bb"></script></span>
            </div>
        </div>

</footer>

    </div>
    <!-- end Footer Wrapper -->

    </div>
    <!-- end Container Wrapper -->

    <!-- start Back To Top -->
    <div id="back-to-top">
        <a href="#"><i class="fa fa-chevron-up"></i></a>
    </div>
    <!-- end Back To Top -->
<div id="ajaxLoginModal" class="modal fade login-box-wrapper" data-width="500" data-backdrop="static" data-keyboard="false" tabindex="-1" style="display: none;"></div>

<div id="ajaxRegisterModal" class="modal fade login-box-wrapper" data-width="500" data-backdrop="static" data-keyboard="false" tabindex="-1" style="display: none;">
</div>

<div id="ajaxForgotPasswordModal" class="modal fade login-box-wrapper" data-width="500" data-backdrop="static" data-keyboard="false" tabindex="-1" style="display: none;"></div>

<!-- JS -->
<script type="text/javascript" src="js/jquery-2.2.4.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.4.1.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/SmoothScroll.min.js"></script>
<script type="text/javascript" src="js/jquery.waypoints.min.js"></script>
<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="js/jquery.slicknav.min.js"></script>
<script type="text/javascript" src="js/spin.min.js"></script>
<script type="text/javascript" src="js/jquery.introLoader.min.js"></script>
<script type="text/javascript" src="js/fancySelect.js"></script>
<script type="text/javascript" src="js/bootstrap-rating.js"></script>
<script type="text/javascript" src="js/select2.full.js"></script>
<script type="text/javascript" src="js/slick.min.js"></script>
<script type="text/javascript" src="js/jquery.placeholder.min.js"></script>
<script type="text/javascript" src="js/ion.rangeSlider.min.js"></script>
<script type="text/javascript" src="js/readmore.min.js"></script>
<script type="text/javascript" src="js/bootstrap-modalmanager.js"></script>
<script type="text/javascript" src="js/bootstrap-modal.js"></script>
<script type="text/javascript" src="js/instagram.min.js"></script>
<script type="text/javascript" src="js/customs.js"></script>

<script type="text/javascript" src="js/images-grid.js"></script>

<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script defer src="https://maps.googleapis.com/maps/api/js?key=Your_API_Key&callback=initMap&libraries=places&v=weekly"></script>
<script src="map/scripts/helper_script.js"></script>
<?php 
    if(preg_match('/index.php$/', $_SERVER['PHP_SELF'])) {
        echo '<script src="map/scripts/index_script.js"></script>';
    } else if(preg_match('/restaurant.php$/', $_SERVER['PHP_SELF'])) {
        echo '<script src="map/scripts/list_script.js"></script>';
    } else if(preg_match('/restaurant-info.php$/', $_SERVER['PHP_SELF'])) {
        echo '<script src="map/scripts/restaurant_script.js"></script>';
    }
?>

<!-- load the images to the slider -->

<script>
    jQuery(function($) {

        "use strict";

        /**
         * Image Grid for Photo
         */

        $('#detail-food-photo').imagesGrid({
            images: [
                <?php
                $img = explode(',', $result['picture']);
                foreach ($img as $item):
                ?>
                { src: 'uploads/<?php echo $item ?>', alt: 'Second image', title: 'Second image', caption: 'Image Caption Twelve' },
                <?php endforeach; ?>
            ],
            cells: 5,
            align: true
        });

    });
</script>


</body>


</html>
