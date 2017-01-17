<?php

$data = array(
    'status' => 'live',
    'now' => time()
    );

if(false !== strpos($_SERVER['HTTP_ACCEPT'], 'text/html')) {
    header('Content-Type: text/html');
    echo "<pre>";
    print_r($data);
    echo "</pre>";
} elseif(false !== strpos($_SERVER['HTTP_ACCEPT'], 'text/xml')) {
    $simplexml = simplexml_load_string('<?xml version="1.0" ?><data />');
    foreach($data as $key => $value) {
        $simplexml->addChild($key, $value);
    }

    header('Content-Type: text/xml');
    echo $simplexml->asXML();
} else {
    // return json
    header('Content-Type: application/json');
    echo json_encode($data);
}
