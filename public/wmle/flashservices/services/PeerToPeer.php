<?php
include "lib/cryptlib.php";

class PeerToPeer 
{ 
	/* An array of channels. You can add a new channel here by simply 
	replicating the line [ "id" => "password", ] id : is your channel id 
	and password iss your channel password. 
	*/
	static $channels = array(
            "idcode" => array("channel" => "idcode","password" => "PASSWORD","stream" => "c14a0e9b3bd14c88394b1c1d5f2a43a4","rtmfp" => "rtmfp://p2p.rtmfp.net/f052f4a46c53c1d9b71513b4-5a97f0c7d21e/")
			);
	
	
	/* An array of html domains */
	var $htmlDomains = array(
    "dev.xexposed.com",
    "xexposed.com",
	"www.xexposed.com",
	"wmle.flashvisions.com"
	);	
	
	
	/* An array of swf domains */
	var $swfDomains = array(
	"dev.xexposed.com",
    "xexposed.com",
    "www.xexposed.com",
    "wmle.flashvisions.com"
	);
	
    
    static function addChannel($channel){
        
        self::$channels[$channel['channel']] = $channel;
    
    }
    
    static function getChannels(){
        
        return self::$channels;
    
    }
	function PeerToPeer()
	{
		
	}
	
	/* Function that checks for channel id in the channels list and 
	   replies back to viewer client with the details of playback.
	*/
	
	function initChannel()
	{
		$arg_list = func_get_args();
		$htmlPage = $arg_list[0];
		$swfPage = $arg_list[1];
		$channelid = $arg_list[2];
		
		//checks for valid channel id
		if($channelid == null || $channelid == '')
		trigger_error ("Invalid Channel !", E_USER_ERROR);
		
		//checks against list of html domains
		if (!in_array($htmlPage, $this->htmlDomains))
		trigger_error ("Domain Not Allowed !", E_USER_ERROR);
		
		//checks against list of swf domains
		if (!in_array($swfPage, $this->swfDomains))
		trigger_error ("Domain Not Allowed !", E_USER_ERROR);
		
        self::$channels[$_SESSION['new_channel']['channel']] = $_SESSION['new_channel'];
        
		//check if channel id exists and replies accordingly
		if (array_key_exists($channelid, self::$channels))
		{
			$data = self::$channels[$channelid];

			/* Init Encryption */
			$crypto = new Crypt;
			$crypto->init('PASSWORD');
			
			$response['rtmfp'] = $crypto->encrypt(utf8_encode($data['rtmfp']));
			$response['channel'] = $crypto->encrypt(utf8_encode($data['channel']));
			$response['stream'] =  $crypto->encrypt(utf8_encode($data['stream']));
			
			return $response;
		}
		else
		{
			trigger_error ("Channel not found !", E_USER_ERROR);
		}
		
		
		
		return;
	}
} 
?>