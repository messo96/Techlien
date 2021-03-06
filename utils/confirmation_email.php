<?php
require_once("../db/database.php");

//for confirm email
if(isset($_GET["email"]) && isset($_GET["token"]) ){
  $dbh = new DatabaseHelper();

  if($dbh -> checkToken($_GET["email"], $_GET["token"])){
  ?>
  <div class="modal-dialog modal-confirm">
      <div class="modal-content">
          <div class="modal-header">
              <div class="icon-box">
                  <i class="material-icon"></i>
              </div>
              <h4 class="modal-title">Email has been confirmed, Welcome!</h4>
          </div>
          <div class="modal-body">
              <p class="text-center">In 5 sec will be redirect in Home.</p>
          </div>
      </div>
  </div>

<?php
    header( "refresh:5;url=/unibowebsite/login.php" );
  }
  else{
    echo "There is a problem, please retry";

  }
}

//for send email-confirm
function send_emailConfirm($firstName, $lastName, $email, $username, $password){

  require_once('PHPMailer/Exception.php');
  require_once('PHPMailer/PHPMailer.php');
  require_once('PHPMailer/SMTP.php');

  $token = rand();

  //PHPMailer Object
  $mail = new PHPMailer\PHPMailer\PHPMailer(); //Argument true in constructor enables exceptions
  $mail ->IsSMTP();
  $mail->SMTPAuth = true;
  $mail ->SMTPSecure = 'ssl';
  $mail ->Host = 'smtp.gmail.com';
  $mail ->Port = '465';
  $mail ->Username = 'websiteunibo@gmail.com';
  $mail ->Password = 'WebsiteUnibo00';


  //From email address and name
  $mail->From = "TECHLIEN@staff.com";
  $mail->FromName = "STAFF TECHLIEN";

  //To address and name
  $mail->addAddress($email, $username);

  //Address to which recipient will reply
  //$mail->addReplyTo("reply@yourdomain.com", "Reply");


  $mail->Subject = "Confirm Mail TECHLIEN";
  $mail->Body = '
  <html>
    <body>
      <p>Caro '.$firstName.' '.$lastName.' (oppure dovremmo chiamarti '.$username.')</p>
      <p> Benvenuto,
          <a href="localhost/unibowebsite/utils/confirmation_email.php?email='.$email.'&token='.$token.'">Click here</a>
          and confirm registration to TECHLIEN
      </p>
    </body>
  </html>';
  $mail->AltBody = "Confirm registration to TECHLIEN";
  //Send HTML or Plain Text email
  $mail->isHTML(true);

  try {
    $mail->send();
    echo "<p>Message has been sent successfully</p>";
    echo "<p>Check out your email</p>";
    $dbh = new DatabaseHelper();
    $dbh -> createUser($firstName, $lastName, $email, $username, $password, $token);
  } catch (Exception $e) {
    echo "Mailer Error: " . $mail->ErrorInfo;
  }
}
?>
