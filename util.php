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

// TODO: use SMTP instead
function sendEmail($recipient, $subject, $content) {
    return mail($recipient, $subject, $content);
}

function sendVerificationEmail($email) {
    $email_content = "Hello!\n\nTo verify your email address, please click on the following link:\n\nhttps://discord2027.mit.edu/$_SERVER[REQUEST_URI]&emailauth=" . hashify($email);
    return sendEmail($email, 'Verify your email for 2027 Discord', $email_content);
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


?>