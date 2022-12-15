<?php

function hashify($val) {
    return hash('sha256', PEPPER.":$val");
}

function authenticate($val, $expectedHash, $description) {
    $hash = hashify($val);
    if (!isset($_REQUEST['auth'])) {
        die('Error: Missing parameter in URL!');
    }
    if ($hash !== $expectedHash) {
        die("Error: $description validation failed! Did you tamper with the URL?");
    }
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "PHPMailer/src/Exception.php";
require_once "PHPMailer/src/PHPMailer.php";
require_once "PHPMailer/src/SMTP.php";

function sendEmail($recipient, $subject, $content) {
  $mail = new PHPMailer(true);
  try {
    // Setup Mailer
    $mail->isSMTP();
    $mail->Host = 'outgoing.mit.edu';
    $mail->SMTPAuth = false;
    $mail->Port = 25;
    // Add Details
    $mail->setFrom('2027discordadmin@mit.edu', 'Wide Tim');
    $mail->addAddress($recipient);
    $mail->isHTML(false);
    $mail->Subject = $subject;
    $mail->Body = $content;
    $mail->AltBody = $content;
    // Send It
    $mail->send();
    return true;
  } catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    return false;
  }
}

function sendVerificationEmail($email) {
    $email_content = "Hello!\n\nTo verify your email address, please click on the following link:\n\nhttps://discord2027.mit.edu$_SERVER[REQUEST_URI]&emailauth=" . hashify($email) . "\n\nBest,\nWide Tim";
    return sendEmail($email, 'Verify your email for 2027 Discord', $email_content);
}

/// Ideally, change it to use mysqli_prepare like so https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php
/// Since I am already sanitizing the email it should not be vulnerable to SQL injection

function isAdmit($connection, $email) {
    return mysqli_num_rows(mysqli_query($connection, "SELECT * FROM users2027 where email=\"$email\"")) > 0;
}

function getName($connection, $email) {
    $result = mysqli_query($connection, "SELECT * FROM users2027 where email=\"$email\"");
    if (!$result) {
        die("email $email not found in the database!");
    }
    $result = $result->fetch_array();
    return $result['name'];
}

function hasDiscordAccount($connection, $email) {
    $result = mysqli_query($connection, "SELECT * FROM users2027 where email=\"$email\"");
    if (!$result) {
        die("email $email not found in the database!");
    }
    $result = $result->fetch_array();
    return !is_null($result['discord']);
}

function updateRecord($connection, $email, $name, $discord) {
    $now = time();
    $result = mysqli_query($connection, "UPDATE users2027 SET discord=$discord, name=\"$name\", timestamp=$now WHERE email=\"$email\"");
    if (!$result) {
        die("query failed! please report to 2027discordadmin@mit.edu or DM TO CONTACT STAFF");
    }
}

function redirect($url) {
    header("Location: $url");
    die();
}

/// Code to make POST requests, used for OpenID/OAuth
/// Reference: https://www.php.net/manual/en/context.http.php
function post($url, $args) {
	$postdata = http_build_query($args);
	$opts = array('http' => array(
        'method' => 'POST',
        'header' => 'Content-type: application/x-www-form-urlencoded',
        'content' => $postdata
    ));
	$context = stream_context_create($opts);
	return file_get_contents($url, false, $context);
}

/// Polyfill
/// https://www.php.net/manual/en/function.str-contains.php
if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}

?>
