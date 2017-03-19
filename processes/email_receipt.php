<?php
session_start();
include_once('../includes/html2text.php'); 
include_once('../includes/phpmailer/class.phpmailer.php');
include_once('../config.php');
include_once('../includes/phpmailer/PHPMailerAutoload.php');

//make the mail text
// The "source" HTML you want to convert.
$html =$_POST['receipt'];

$h2t =& new html2text($html);

// Simply call the get_text() method for the class to convert
// the HTML to the plain text. Store it into the variable.
$receipt_text = $h2t->get_text();

$mail = new PHPMailer();
$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host = $smtp_host; // SMTP server

if ($smtp_authentication){
	$mail->Port = $smtp_port; //Set the encryption system to use - ssl (deprecated) or tls
	$mail->SMTPSecure = $smtp_secure; //Whether to use SMTP authentication
	$mail->SMTPAuth = true; // turn on SMTP authentication
	$mail->Username = $smtp_username; // SMTP username
	$mail->Password = $smtp_pwd; // SMTP password
} else {
	$mail->SMTPAuth = false;
}

$mail->isHTML(true);

$mail->FromName = $email_from_name;
$mail->From = $email_from_address;//sender addy
$mail->AddAddress($_SESSION['email']);//recip. email addy

$mail->Subject = $email_subject;
$mail->AddEmbeddedImage($receipt_email_header_image, 'logo_head', $receipt_email_header_image);

$mail->Body ="<p><img src=\"cid:logo_head\" /><p>".implode("<p>",$receipt_email_header).str_replace(array("Title:","Item ID:","Call Number:","Date Due:"),array("<br /><br />Title:","<br />Item ID:","<br />Call Number:","<br />Date Due:"),$receipt_text)."<br /><br />"."<p>".implode("\n",$receipt_footer);
$mail->WordWrap = 70;
$mail->Send(); 
?>
<script type="text/javascript">
	window.location.href="processes/logout.php";
</script>
