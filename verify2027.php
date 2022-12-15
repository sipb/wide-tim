<?php

/// Debug (TODO: remove)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

/// Constants
require "constants.php";

/// Hardcoded for now
$server = '1049418013323055124';
$role = '1050870099667603466';

// SQL stuff
$connection = mysqli_connect(SQL_HOST, SQL_USERNAME, SQL_PASSWORD, SQL_DB);

/// Utility functions
require "util.php";

require_once "php-discord-sdk/support/sdk_discord.php";
$discord = new DiscordSDK();
$discord->SetAccessInfo("Bot", TOKEN);

if (isset($_REQUEST['email'])) {
    global $email;
    $email = $_REQUEST['email'];
}

/// Validate email address if given (don't trust the client)
/// Check if given email is adMIT
if (isset($email) && !isset($_REQUEST['email_invalid'])) {
    /// This should not be vulnerable because PHP is short-circuited
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !isAdmit($connection, $email)) {
        redirect("https://discord2027.mit.edu$_SERVER[REQUEST_URI]&email_invalid=true");
    }
}

/// Do user/pass authentication
if (isset($email) && isset($_REQUEST['password'])) {
    die('username and password were provided, TODO implement redirection here');
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

if (isset($_REQUEST['name'])) {
    die('we have the name ' . $_REQUEST['name'] . ' and everything else we need to verify. TODO: finish the code');
} else if (isset($_REQUEST['emailauth']) && !isset($_REQUEST['email_invalid'])) {
    authenticate($email, $_REQUEST['emailauth'], 'E-mail');

?>
    <h1>One more thing!</h1>
    <p><strong>Is the name we have on file correct?</strong></p>
    <p>This is the preferred name you set on your application.</p>
    <p>We do expect everyone in the server to use a name they might be known as at MIT; it's much better once you come to campus for CPW!</p>
    <p>Once you confirm your name, your Discord name on the server will be set to it.</p>
    <form method="post">
        <input type="text" id="name" required name="name" value="<?= getName($connection, $email) ?>">
        <input class="button singlebutton" type="submit" id="btn_yes" value="Finish verification">
    </form>
<?php
} else if (isset($email) && !isset($_REQUEST['email_invalid'])) {
    $result = sendVerificationEmail($email);
    if ($result) {
?>
    <p>Verification email has been sent to <?= $email ?>. It may take a few moments to arrive (or end up in spam).</p>
<?php
    } else {
?>
    <p class="error">There was an error sending the verification email. Please report this to 2027discordadmin@mit.edu</p>
<?php
    }
?>
    <p>If email verification didn't work, you can try your application portal username and password too:</p>
    <form method="post" id="subthing">
        <details>
            <summary>Click to login with password</summary>
            <label for="email">Email:</label>
            <input name="email" type="email" required value="<?= $email ?>">
            <br>
            <label for="password">Password:</label>
            <input name="password" type="password" required>
            <br>
            <input class="button singlebutton" type="submit" value="Login"> 
        </details>
    </form>
<?php
} else {
?>
    <h1>2027 Discord verification</h1>
        <form method="get">
        <p>Hello! To verify that you're an adMIT, please enter the email that you used in your application portal.</p>
        <?= isset($_GET['email_invalid']) ? '<p class="error">You entered an invalid email, please try again! Check for any typos, and make sure you are using the same email as your MIT Admissions portal.</p>' : '' ?>
        <label for="email">Email:</label>
        <input name="email" type="email" required value="<?= isset($email) ? $email : '' ?>">
        <input type="hidden" id="id" name="id" value="<?= isset($_REQUEST['id']) ? $_REQUEST['id'] : '' ?>">
        <input type="hidden" id="auth" name="auth" value="<?= isset($_REQUEST['auth']) ? $_REQUEST['auth'] : '' ?>">
        <br>
        <input class="button singlebutton" type="submit" value="Continue"> 
    </form>
<?php
}
?>
    </div>
</body>
</html>
