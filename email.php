<?php
$email_address = $_POST['email_address'];
$headers = 'From: nolboo8985@gmail.com' . "\r\n" .
    'Reply-To: nolboo8985@gmail.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
$return_value = mail( $email_address, "vocal data", "thanks", $headers );
if($return_value == TRUE)
  echo "TRUE";
else
  echo "FALSE";
 ?>
