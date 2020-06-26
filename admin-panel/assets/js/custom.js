


    async function changeCountry() { // this function to get city when country change send request to ajax.php
        var select = document.getElementById("city");
        var length = select.options.length;
        for (i = length-1; i >= 0; i--) {
            select.options[i] = null;
        }
        let country = document.getElementById('country').value;
        let response = await fetch('ajax.php?country='+country);
        let data = await response.json();
        let city = document.getElementById('city');
        for (let key of data) {
            let option = document.createElement('option');
            option.value = key['id'];
            option.innerHTML = key['name'];
            city.appendChild(option);
        }
    }


(function ($) {
    "use strict";
    var mainApp = {

        main_fun: function () {
           
            /*====================================
              LOAD APPROPRIATE MENU BAR
           ======================================*/
            $(window).bind("load resize", function () {
                if ($(this).width() < 768) {
                    $('div.sidebar-collapse').addClass('collapse')
                } else {
                    $('div.sidebar-collapse').removeClass('collapse')
                }
            });

          
     
        },

        initialization: function () {
            mainApp.main_fun();

        }

    }
    // Initializing ///

    $(document).ready(function () {
        mainApp.main_fun();


        // This to search user on users page
        $("#searchUser").keyup(function () {
            let search = $(this).val();
            $.ajax({
                url: 'ajax.php',
                method:'POST',
                data:{searchUser : search},
                success:function (response) {
                    $('#table-data').html(response);
                }
            })
        })
        // This to search user on users page


        // This to search booking on two page
        $("#searchUserBooking").keyup(function () {
            let searchBooking = $(this).val();
            $.ajax({
                url:'ajax.php',
                method:'POST',
                data:{searchBooking : searchBooking},
                success:function (response) {
                    $("#table-data-last-booking").html(response)
                }
            })
        })
        // This to search booking on two page

        $("#searchRestaurant").keyup(function () {
            let searchRes = $(this).val();
            $.ajax({
                url:'ajax.php',
                method:'POST',
                data:{searchRestaurant:searchRes},
                success:function (response) {
                    $('#table-data-restaurant').html(response);
                }
            })
        })


    });

}(jQuery));
