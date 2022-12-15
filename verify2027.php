<?php

/// Debug (TODO: remove)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

/// Constants
require "constants.php";

/// Utility functions
require "util.php";

/// Hardcoded for now
$server = '1049418013323055124';
$role = '1050870099667603466';

// SQL stuff
$connection = mysqli_connect(SQL_HOST, SQL_USERNAME, SQL_PASSWORD, SQL_DB);

require_once "php-discord-sdk/support/sdk_discord.php";
$discord = new DiscordSDK();
$discord->SetAccessInfo("Bot", TOKEN);

/// Validate email address if given (don't trust the client)
/// TODO: check if not adMIT here too
if (isset($_REQUEST['email']) && !isset($_REQUEST['email_invalid']) && !filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) {
    redirect("https://discord2027.mit.edu$_SERVER[REQUEST_URI]&email=".$_REQUEST['email']."&email_invalid=true");
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="verify.css">
</head>
<body>
    <div id="main">

<?php

/// Check for kerb
if (isset($_SERVER['SSL_CLIENT_S_DN_Email'])) {
    die('You have a kerb: kerb verification will be added on May 1 for 2027s. If you are not a 2027, you should get verified by the admins instead.<br>If you need help, email 2027discordadmin@mit.edu');
}

/// Authenticate Discord member (make sure they came from clicking the link, and therefore own the account)
authenticate(intval($_REQUEST['id']), $_REQUEST['auth'], 'Discord');

if (isset($_REQUEST['emailauth'])) {
    authenticate($_REQUEST['email'], $_REQUEST['emailauth'], 'E-mail');
    die('verified correct person. TODO: now implement role setting');
    /// TODO: before giving the role, check if adMIT again and save to database!
} else if (isset($_REQUEST['email']) && !isset($_REQUEST['email_invalid'])) {
    /// TODO: check that email is adMIT!!!! Very important!
    $email = $_REQUEST['email'];
    $result = sendVerificationEmail($email);
    if ($result) {
?>
    <p>Verification email has been sent to <?= $email ?>. It may take a few moments to arrive (or end up in spam).</p>

<?php
        die("verification email has been sent to $email (may take a few moments to arrive), pls check your email. TODO: improve this page.");
    } else {
        die('There was an error sending the verification email. Please report this to 2027discordadmin@mit.edu');
    }
} else {
?>
    <h1>2027 Discord verification</h1>
        <form method="post">
        <p>Hello! To verify that you're an adMIT, please enter the email that you used in your application portal.</p>
        <?= isset($_GET['email_invalid']) ? '<p class="error">You entered an invalid email, please try again! Check for any typos, and make sure you are using the same email as your MIT Admissions portal.</p>' : '' ?>
        <label for="email">Email:</label>
        <input name="email" type="email" required <?= isset($_REQUEST['email']) ? $_REQUEST['email'] : '' ?> >
        <br>
        <input class="button singlebutton" type="submit" value="Continue"> 
    </form>
<?php
}
?>
    </div>
</body>
</html>
