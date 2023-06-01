<?php
include "./../config.php";
include "com/flashvisions/camcorder/OperationMode.php";
include "com/flashvisions/camcorder/BroadcastQuality.php";
include "com/flashvisions/camcorder/EncodingMode.php";

define("RED5", "red5");
define("FMS", "fms");
define("WOWZA", "wowza");

define("LICENSE", $config['license']);
define("RTMP", $config['rtmp']);
define("STREAM", $config['stream']);
define("THUMBS_DIR", $config['thumbs']);
define("LOGO", $config['logo']);
define("AUTOSTART", $config['autoStart']);
//define("AUTOSTART", isset($_SESSION["autostart"]) ? $_SESSION["autostart"] : $config['autoStart']);
define("DISABLEINTERACTION", $config['disableInteraction']);
define("ENABLEPREVIEW", $config['enablePreview']);

define("BROADCASTMODE", $config['broadcastMode']);
define("QUALITY", $config['quality']);
define("MODE", $config['mode']);

class HDBroadcaster 
{ 
	var $key = "";
	var $capture_directory;
	var $destination;
	var $stream;
	var $bwcheck;
	var $server = RED5;
	var $logo;
	var $autoStart;
	var $disableInteraction;
    var $enablePreview;
    var $broadcastMode;
	var $mode;
	
	function  HDBroadcaster()
	{		
		$this->enablePreview = ENABLEPREVIEW;
		$this->disableInteraction = DISABLEINTERACTION;
		$this->autoStart = AUTOSTART;
		$this->key = LICENSE;
		$this->capture_directory = "../../".THUMBS_DIR."/"; 
		$this->stream = STREAM;
		$this->destination = (isset($_SESSION['rtmp']))?$_SESSION['rtmp']:RTMP;
        $this->logo = LOGO;
        $this->broadcastMode = BROADCASTMODE;
		$this->mode = EncodingMode::SD;
		//$this->mode = "EncodingMode::hD";
		/* clear session data */
		unset($_SESSION['stream']);
		unset($_SESSION['rtmp']);
	}	
	
		
	function loadSettings()
	{		
		$params = func_get_args();                                         
		$instace_id	 = $params[0];
		$htmlParams = $params[1];
      
	    //$this->stream = $_SESSION["streams"]["model"][(int)$htmlParams["current_stream"]];
		$settings["licencekey"] = $this->key;
        $settings["autoStart"] = $this->autoStart;
        
        if(isset($htmlParams["current_stream"])) {
            if($htmlParams["autoStart"]) $settings["autoStart"] = $htmlParams["autoStart"];
		    $settings["publishName"] = $_SESSION["streams"]["model"][(int)$htmlParams["current_stream"]];
        } else
            $settings["publishName"] = $this->stream;
                
		$settings["destination"] = $this->destination;
		$settings["playback"] = $settings["destination"];
		$settings["autoSnapInterval"] = 30;
		$settings["bwcheck"] = $this->bwcheck;
		$settings["server"] = $this->server;
		$settings["logo"] = $this->logo;
		$settings["enablePreview"] = $this->enablePreview;
		// change quality of view - quality constaces are find in /flashservices/service/com/flashvisions/camcorder/BroadcastQuality.php
        $settings["forceQuality"] = (MODE == "HD") ? BroadcastQuality::HD_GOOD : BroadcastQuality::HIGH;
		$settings["broadcastMode"] = $this->broadcastMode;
		$settings["disableInteraction"] = $this->disableInteraction;
		
		$settings["bwStrict"] = false;

		$settings["userPresets"] = (MODE == "HD") ? file_get_contents("encoderpresets_hd.xml") : file_get_contents("encoderpresets_sd.xml");            

        $settings["mode"] = (MODE == "HD") ? EncodingMode::HD : EncodingMode::SD;

		//$settings["debug"] = true;
		//mail("razvan.moldovan@perfectweb.ro","sete", print_r($htmlParams,1).print_r($settings,1));
        
		return $settings;
	}
	
	function saveSession()
	{
		$params = func_get_args();
		$instace_id	 = $params[0];
		$htmlParams = $params[1];
		$streamname = $params[2];
		$preview = $params[3];
		$duration = $params[4];
		
		$outputfile = $this->capture_directory.$streamname.".jpg";
		if($preview->data) {
		file_put_contents($outputfile,$preview->data);
		$this->generateThumb($outputfile,$outputfile,320,240);
		}
		
		return;
	}
	
	function generateThumb($img,$output,$w,$h)
	{
		$x = @getimagesize($img);
		$sw = $x[0];
		$sh = $x[1];

		$im = @ImageCreateFromJPEG ($img) or $im = false;
		 
		if ($im) {
			$thumb = @ImageCreateTrueColor ($w, $h);
			@ImageCopyResampled ($thumb, $im, 0, 0, 0, 0, $w, $h, $sw, $sh);
			@ImageJPEG ($thumb,$output,100);
			imagedestroy($thumb);
			imagedestroy($im);
		}
	}
	
} 