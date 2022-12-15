<?php

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