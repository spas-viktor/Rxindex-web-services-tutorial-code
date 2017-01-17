<?php

function parseAcceptHeader( $header )
{
    if ( !preg_match_all(
            '(
                (?P<value>[a-z*][a-z0-9_/*+.-]*)
                    (?:;q=(?P<priority>[0-9.]+))?
             \\s*(?:,|$))ix', $header, $matches, PREG_SET_ORDER ) )
    {
        return false;
    }

    $accept = array();
    foreach ( $matches as $values )
    {
        if(!isset($values['priority']) || 
            (isset($values['priority']) && $values['priority'] == 1))
        {
            $accept[] = isset( $values['value'] ) ? strtolower( $values['value'] ) : null;
        }
    }

    return $accept;
}

$data = array(
    'format' => 'json',
    'status' => 'live'
    );

$original_accept_header = $_SERVER['HTTP_ACCEPT'];

$accept = parseAcceptHeader($original_accept_header);

if(in_array('text/html', $accept)) {
    header('Content-Type: text/html');
    echo "<pre>";
    print_r($data);
    echo "</pre>";
} elseif(in_array('text/xml', $accept)) {
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

