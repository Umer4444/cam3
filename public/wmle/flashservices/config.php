<?php

$url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';
$url .= $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);

$config['license'] = 'a0cbeff47099e819370bab29daeb77ff22f581d8bc249dda86461212cc52d02428c9bb2403a5120dfdd0f514dd3b145a249642db51ca12797c5184a0006dbf4e';
$config['stream'] = '2bd3878ac5be4211d80b7d3d60a6752e';
$config['rtmp'] = 'rtmp://xexposed.com/oflaDemo';
$config['logo'] = null;
$config['thumbs'] = "captured";

if($_SESSION["group"] == "model"){
    //$config["stream"] = current($_SESSION["streams"]["model"]);

    $config['autoStart'] = isset($_SESSION["user_chat"][$_SESSION["user"]["id"]]["autostart"]) ? $_SESSION["user_chat"][$_SESSION["user"]["id"]]["autostart"] : "false";
    $config['disableInteraction'] = true;
    $config['enablePreview'] = false;
    $config['base'] = $url."/swf/";
    $config['videosPath'] = $config['rtmp'];
    $config['serviceLocation'] = substr(dirname($_SERVER['PHP_SELF'])."/flashservices",1);
    $config['playerPath'] = $url."/swf/StrobeMediaPlaybackPro.swf";
    //$config['mode'] = "EncodingMode::SD";

    $config["broadcastMode"] = isset($_SESSION["user_chat"][$_SESSION["user"]["id"]]['broadcastMode']) ?  $_SESSION["user_chat"][$_SESSION["user"]["id"]]['broadcastMode'] :"duplex";

    //$config["mode"] =  isset($_SESSION["user_chat"][$_SESSION["user"]["id"]]['quality']) ? (strtolower($_SESSION["user_chat"][$_SESSION["user"]["id"]]['quality']) == "hd" ? "EncodingMode::HD" : "EncodingMode::SD") : "EncodingMode::SD";
    $config["mode"] =  isset($_SESSION["user_chat"][$_SESSION["user"]["id"]]['quality']) ? (strtoupper($_SESSION["user_chat"][$_SESSION["user"]["id"]]['quality']) ) : "SD";
} else {
    $config["stream"] = current((array)$_SESSION["streams"]["user"]);

    $config['autoStart'] = true;
    $config['disableInteraction'] = true;
    $config['enablePreview'] = false;
    $config['base'] = $url."/swf_small/";
    $config['videosPath'] = $config['rtmp'];
    $config['serviceLocation'] = substr(dirname($_SERVER['PHP_SELF'])."/flashservices",1);
    $config['playerPath'] = $url."/swf_small/StrobeMediaPlaybackPro.swf";
    //$config['mode'] = "EncodingMode::SD";

    $config["broadcastMode"] = "duplex";

    //$config["mode"] =  isset($_SESSION["user_chat"][$_SESSION["user"]["id"]]['quality']) ? (strtolower($_SESSION["user_chat"][$_SESSION["user"]["id"]]['quality']) == "hd" ? "EncodingMode::HD" : "EncodingMode::SD") : "EncodingMode::SD";
    $config["mode"] =  "SD";

}