<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/phpmailer/phpmailer/src/Exception.php';
    require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require 'vendor/phpmailer/phpmailer/src/SMTP.php';

    require 'vendor/autoload.php';

    class SendEmail {
        private $infoMail = 'info@fnbtime.com';
        private $senderMail = 'no-reply@fnbtime.com';
        private $password   = 'ZhSoe-Y31kwl';
        private $host = 'sg2plcpnl0079.prod.sin2.secureserver.net';
        private $smtpSecure ="TLS";
        private $port = 587;
        private $smtpAuth = true;

        # This function send Email verification when user Register in web site
        public function sendVerificationEmail($email , $name , $token) { # This function when user sign up
            $mail = new PHPMailer(true);
            $mail->IsSMTP(); // set mailer to use SMTP
            $mail->From = $this->senderMail;
            $mail->FromName = $this->senderMail;
            $mail->Host =  $this->host;
            $mail->SMTPSecure= $this->smtpSecure;
            $mail->Port = $this->port;
            $mail->SMTPAuth = $this->smtpAuth;
            $mail->Username = $this->senderMail; // SMTP username
            $mail->Password = $this->password;
            $mail->AddAddress($email , $email);
            $mail->AddReplyTo($this->infoMail);
            $mail->WordWrap = 50; // set word wrap
            $mail->IsHTML(true); // set email format to HTML
            $mail->Subject = "Please Verify You're Email";
            $mail->Body = "
                <!DOCTYPE html>
                <html lang='en'>
                <head>
                <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
                </head>
                    <body>
                        Hi $name , <br>
                        Thanks for signing up on Fnbtime <br>
                        We're thrilled to have you ! <br>
                        To get started , please click the link below to verify we\'ve got your correct email address. <br>
                        <a href='https://www.fnbtime.com/confirm.php?token=$token'>Click here to verify your account.</a> <br>
                        Regards, <br>
                        <strong><a href='https://www.fnbtime.com'>Fnbtime</a> Team</strong>
                    </body>
                </html>";
            if(!$mail->Send()){
                echo 'there\'s some problem please try again';
                die();
            }
        }
        # This function send all booking information when client reservation
        public function sendBookingInfo($email , $name , $resName , $country , $city , $persons , $phoneCous , $bookingNumber , $time , $date) {
            $mail = new PHPMailer(true);
            $mail->IsSMTP(); // set mailer to use SMTP
            $mail->From = $this->senderMail;
            $mail->FromName = $this->senderMail;
            $mail->Host =  $this->host;
            $mail->SMTPSecure= $this->smtpSecure;
            $mail->Port = $this->port;
            $mail->SMTPAuth = $this->smtpAuth;
            $mail->Username = $this->senderMail; // SMTP username
            $mail->Password = $this->password;
            $mail->AddAddress($email , $email);
            $mail->AddReplyTo($this->infoMail);
            $mail->WordWrap = 50; // set word wrap
            $mail->IsHTML(true); // set email format to HTML
            $mail->Subject = "Booking Information";
            $mail->Body = "
                <!DOCTYPE html>
                <html lang='en'>
                <head>
                <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
                </head>
                    <body>
                        Hi $name , <br>
                        Thanks for Booking With Fnbtime <br>
                        This is all reservation details
                        Booking Number : $bookingNumber <br>
                        Restaurant is : $resName <br>
                        Country-city : $country $city <br>
                        Guest :  $persons <br>
                        Phone Number : $phoneCous <br>
                        Date : $date <span> </span> $time <br>
                        We confirm to you less than 24 hours 
                        You Can check The status of reservation 
                        <a href='https://www.fnbtime.com/client.php'>Click Here</a> <br>
                        Regards, <br>
                        <strong><a href='https://www.fnbtime.com'>Fnbtime</a> Team</strong>
                    </body>
                </html>";
            if(!$mail->Send()){
                echo 'there\'s some problem please try again';
                die();
            }
        }

        public function sendAcceptBooking($email , $name , $resName , $bookingNumber , $persons , $date , $time) {
            $mail = new PHPMailer(true);
            $mail->IsSMTP(); // set mailer to use SMTP
            $mail->From = $this->senderMail;
            $mail->FromName = $this->senderMail;
            $mail->Host =  $this->host;
            $mail->SMTPSecure= $this->smtpSecure;
            $mail->Port = $this->port;
            $mail->SMTPAuth = $this->smtpAuth;
            $mail->Username = $this->senderMail; // SMTP username
            $mail->Password = $this->password;
            $mail->AddAddress($email , $email);
            $mail->AddReplyTo($this->infoMail);
            $mail->WordWrap = 50; // set word wrap
            $mail->IsHTML(true); // set email format to HTML
            $mail->Subject = "Confirm Booking Information";
            $mail->Body = "
                <!DOCTYPE html>
                <html lang='en'>
                <head>
                <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
                </head>
                    <body>
                        Hi $name , <br>
                        We Confirm to You You're Reservation in $resName <br>
                        at booking number $bookingNumber <br>
                        for $persons Guests <br>
                        at $date <span> </span> $time
                        you can check for all reservation details 
                        <a href='https://www.fnbtime.com/client'>Click Here</a> <br>
                        <strong><a href='https://www.fnbtime.com'>Fnbtime</a> Team</strong>
                    </body>
                </html>";
            if(!$mail->Send()){
                echo 'there\'s some problem please try again';
                die();
            }
        }
        public function sendCancelBooking($name , $email , $bookingNumber , $resName , $message) {
            $mail = new PHPMailer(true);
            $mail->IsSMTP(); // set mailer to use SMTP
            $mail->From = $this->senderMail;
            $mail->FromName = $this->senderMail;
            $mail->Host =  $this->host;
            $mail->SMTPSecure= $this->smtpSecure;
            $mail->Port = $this->port;
            $mail->SMTPAuth = $this->smtpAuth;
            $mail->Username = $this->senderMail; // SMTP username
            $mail->Password = $this->password;
            $mail->AddAddress($email , $email);
            $mail->AddReplyTo($this->infoMail);
            $mail->WordWrap = 50; // set word wrap
            $mail->IsHTML(true); // set email format to HTML
            $mail->Subject = "Cancel Booking Information";
            $mail->Body = "
                <!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
                </head>
                    <body>
                        Hi $name , <br>
                        We're So sorry to Cancel You're Reservation in $resName <br>
                        booking number $bookingNumber <br>
                        because $message <br>
                        <strong><a href='https://www.fnbtime.com'>Fnbtime</a> Team</strong>
                    </body>
                </html>";
            if(!$mail->Send()){
                echo 'there\'s some problem please try again';
                die();
            }
        }

        public function sendForgetPassword($name , $email , $token) {
            $mail = new PHPMailer(true);
            $mail->IsSMTP(); // set mailer to use SMTP
            $mail->From = $this->senderMail;
            $mail->FromName = $this->senderMail;
            $mail->Host =  $this->host;
            $mail->SMTPSecure= $this->smtpSecure;
            $mail->Port = $this->port;
            $mail->SMTPAuth = $this->smtpAuth;
            $mail->Username = $this->senderMail; // SMTP username
            $mail->Password = $this->password;
            $mail->AddAddress($email , $email);
            $mail->AddReplyTo($this->infoMail);
            $mail->WordWrap = 50; // set word wrap
            $mail->IsHTML(true); // set email format to HTML
            $mail->Subject = "Reset Your Password";
            $mail->Body = "
                <!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
                </head>
                    <body>
                        Hi $name , <br>
                        Change You're password please <a href='https://www.fnbtime.com/reset-password-form.php?token=$token'>Click Here</a> <br>       
                        <strong><a href='https://www.fnbtime.com'>Fnbtime</a> Team</strong>
                    </body>
                </html>";
            if(!$mail->Send()){
                echo 'there\'s some problem please try again';
                die();
            }
        }

    }