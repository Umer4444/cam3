<?php

function p($misc, $exit = false)
{
    echo "<pre class='p'>" . print_r($misc, true) . "</pre>";
    if ($exit) exit;
}

function today($time = false)
{
    if ($time !== false) return (int)date("Ymd", $time);
    else return (int)date("Ymd");
}

function redirect($url)
{
    header("Location: " . $url);
    exit;
}

function notice($txt, $type = true)
{
    return "<div id='" . ((int)$type == 3 ? "w" : ($type ? "s" : "n")) . "notice'>" . $txt . "</div>";
}

function humanize($string)
{
    return ucwords(strtolower($string));
}

function isLogged()
{
    if (isset($_SESSION['user']) && !empty($_SESSION['user'])) return true;
    else return false;
}

function pass($strlen = 4)
{
    return substr(md5(time() * microtime(true) * rand(1, 15)), -$strlen);
}

function shuffle_assoc($list)
{
    if (!is_array($list)) return $list;
    $keys = array_keys($list);
    shuffle($keys);
    $random = array();
    foreach ($keys as $key) $random[$key] = $list[$key];
    return $random;
}

function checkAgainst($array, $value, $key = null)
{
    if (is_array($array) || is_object($array)) {
        foreach ($array as $row) {
            if (is_null($key) && $row == $value) return true;
            elseif ($row[$key] == $value) return true;
        }
    }
    return false;
}

function extractFrom($array, $value, $key = "id")
{
    if (is_array($array) || is_object($array)) {
        foreach ($array as $row) {
            if ($row[$key] == $value) return $row;
        }
    }
    return false;
}

function clean($string)
{
    return trim(ucwords(strtolower(preg_replace('!\s+!', ' ', $string))));
}

function exceptionHandler($e)
{
    Zend_Registry::get("log")->err($e);
}

function fatalErrorHandler()
{
    $last_error = error_get_last();
    if ($last_error['type'] === E_ERROR) {
        Zend_Registry::get("log")->crit("fatal error: " . $last_error['message'] . " in " . $last_error['file'] . " at line " . $last_error['line']);
        Zend_Registry::get("log")->__destruct();
    }
}

function getTimeDifferenceInWords($firstTime, $secondTime = '')
{
    // convert to unix timestamps
    //$firstTime = strtotime($firstTime);
    // if second time was not supplied, use current time
    //$secondTime = ($secondTime) ? strtotime($secondTime) : time();
    if (!$firstTime)
        return '1+ year ago';
    $secondTime || $secondTime = time();

    // find out the difference in seconds
    $seconds = ($firstTime > $secondTime)
        ? $firstTime - $secondTime
        : $secondTime - $firstTime;

    $minutes = floor($seconds / 60);
    if ($minutes == 0) {
        return 'now';
    }
    if ($minutes == 1) {
        return '1 min ago';
    }
    if ($minutes < 45) {
        return $minutes . ' mins ago';
    }

    $hours = round($minutes / 60);
    if ($hours <= 1) {
        return '1 hr ago';
    }
    if ($hours < 24) {
        return '' . $hours . ' hrs ago';
    }

    $days = round($hours / 24);
    if ($days == 1) {
        return '1 day ago';
    }
    if ($days < 30) {
        return '' . $days . ' days ago';
    }

    $months = round($days / 30);
    if ($months == 1) {
        return '1 month ago';
    }
    if ($months < 12) {
        return '' . $months . ' months ago';
    }

    $years = round($months / 12);
    if ($years == 1) {
        return '1 year ago';
    }
    return '' . $years . ' years ago';
}

function format_bytes($bytes)
{
    if ($bytes < 1024) return $bytes . ' B';
    elseif ($bytes < 1048576) return round($bytes / 1024, 2) . ' KB';
    elseif ($bytes < 1073741824) return round($bytes / 1048576, 2) . ' MB';
    elseif ($bytes < 1099511627776) return round($bytes / 1073741824, 2) . ' GB';
    else return round($bytes / 1099511627776, 2) . ' TB';
}


function reg()
{
    return Zend_Registry::getInstance();
}

function config()
{
    return reg()->config;
}

function db()
{
    return reg()->db;
}

use Zend\Stdlib\Hydrator;

function user()
{
    $serviceLocator = Zend_Registry::get('service_manager');
    $auth = $serviceLocator->get('zfcuser_auth_service');
    if ($auth->hasIdentity()) {
        $notUsedMethods = array(
          'getSetting',
          'getWebsite',
          'getFolderPath',
        );

        $usr = $auth->getIdentity();

        $methods = get_class_methods($usr);
        $filter = new Zend\Filter\Word\CamelCaseToUnderscore();


        if ($methods) {
            foreach ($methods as $method) {
                if(in_array($method, $notUsedMethods)) {
                    continue;
                }
                if(substr($method, 0, 3) == 'get') {
                   $userArray[strtolower($filter->filter(substr_replace($method, '', 0, 3)))] = $usr->$method();
                }
            }

        }



        $merged = array_merge(
            $userArray,
            (is_array($userArray["extra_fields"]) ? $userArray["extra_fields"] : array())
        );

        unset($merged['extra_fields']);

        return (object)$merged;
    }
    if(isset($_SESSION["user"]["id"])) {
        $_SESSION["user"]["id"] = $_SESSION["user"]["id"];
    }
    return (object)$_SESSION['user'];
}

function user_permissions()
{
    return reg()->user_permissions;
}

function cmp_webchat_users($a, $b)
{
    $id1 = substr($a['id_user'], 0, 4);
    $id2 = substr($b['id_user'], 0, 4);


    if ($id1 == $id2) {
        if ($id1 == 'gues') {
            return 0;
        } else {
            $nr1 = substr($a['id_user'], strpos($a['id_user'], "_") + 1);
            $nr2 = substr($b['id_user'], strpos($b['id_user'], "_") + 1);
            if ($nr1 == $nr2) {
                return 0;
            }
            return ($nr1 < $nr2) ? -1 : 1;
        }

    } else {
        switch ($id1) {
            case 'gues':
                return 1;
                break;
            case 'mode':
                return -1;
                break;
            case 'user':
                switch ($id2) {
                    case 'mode':
                        return 1;
                        break;
                    case 'gues':
                        return -1;
                        break;
                }
                break;
        }
    }
}

/**
 * generate nested dirs for file uploads
 * @param null $base_path
 * @param $name
 * @param int $count
 * @return string $structure
 */
function generateFilePath($base_path = null, $name, $count = 0)
{

    if (!$base_path) return false;
    if ($count >= 10) return false;

    $structure = substr(md5($name), 0, 2) . '/' . substr(md5($name), 4, 2) . '/' . substr(md5($name), 8, 2) . '/' . substr(md5($name), 12, 2) . '/';


    try {
        mkdir($base_path . $structure, 0777, true);
    } catch (\Exception $e) {
       var_dump($e->getMessage());die();
    }

    if (file_exists($base_path . $structure)) {
        return $structure;
    }

    generateFilePath($base_path, $name, ++$count);


}

function getUserIp()
{
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

/**
 * Limits the number of words in a string.
 *
 * @param string $string
 * @param $word_limit --  number of words to return
 */
function limit_words($string, $word_limit = 20)
{
    $words = explode(' ', $string);
    if ($word_limit < count($words)) {
        return implode(' ', array_slice($words, 0, $word_limit)) . ' ...';
    } else {
        return $string;
    }

}

if (!function_exists(formatTime)) {

    /**
     * @param $unix_time
     * @param int $type
     *
     * @return string
     */
    function formatTime($unix_time, $type = 1)
    {

        switch ($type) {
            case '1':
                // 10/24/2001 at 7:13am
                return date('m/d/Y', $unix_time) . ' at ' . date('h:ia', $unix_time);

                break;
            case '2':
                return;

            case 'fulldate':
                //monday,  january 12, 2013 at 10:02
                return date('l, F j, Y', $unix_time) . ' at ' . date('h:ia', $unix_time);
                break;
        }

    }

}

/**
 * /...e63e860.jpg  --> ...e63e860_t.jpg
 *
 * @param mixed $file
 * @param mixed $size
 */
function getPhotoThumb($filename, $size = 't')
{
    switch ($size) {
        case 't':
            return substr($filename, 0, -4) . '_t' . substr($filename, -4);
            break;

        case 'other':
            return substr($filename, 0, -4) . '_other' . substr($filename, -4);
            break;
    }
    return false;
}

/**
 * @param $text
 * @param string $separator
 * @return mixed|string
 */
function stripText($text, $separator = '-')
{
    $bad = array(
        'À', 'à', 'Á', 'á', 'Â', 'â', 'Ã', 'ã', 'Ä', 'ä', 'Å', 'å', 'Ă', 'ă', 'Ą', 'ą',
        'Ć', 'ć', 'Č', 'č', 'Ç', 'ç',
        'Ď', 'ď', 'Đ', 'đ',
        'È', 'è', 'É', 'é', 'Ê', 'ê', 'Ë', 'ë', 'Ě', 'ě', 'Ę', 'ę',
        'Ğ', 'ğ',
        'Ì', 'ì', 'Í', 'í', 'Î', 'î', 'Ï', 'ï',
        'Ĺ', 'ĺ', 'Ľ', 'ľ', 'Ł', 'ł',
        'Ñ', 'ñ', 'Ň', 'ň', 'Ń', 'ń',
        'Ò', 'ò', 'Ó', 'ó', 'Ô', 'ô', 'Õ', 'õ', 'Ö', 'ö', 'Ø', 'ø', 'ő',
        'Ř', 'ř', 'Ŕ', 'ŕ',
        'Š', 'š', 'Ş', 'ş', 'Ś', 'ś',
        'Ť', 'ť', 'Ť', 'ť', 'Ţ', 'ţ',
        'Ù', 'ù', 'Ú', 'ú', 'Û', 'û', 'Ü', 'ü', 'Ů', 'ů',
        'Ÿ', 'ÿ', 'ý', 'Ý',
        'Ž', 'ž', 'Ź', 'ź', 'Ż', 'ż',
        'Þ', 'þ', 'Ð', 'ð', 'ß', 'Œ', 'œ', 'Æ', 'æ', 'µ');

    $good = array(
        'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'Ae', 'ae', 'A', 'a', 'A', 'a', 'A', 'a',
        'C', 'c', 'C', 'c', 'C', 'c',
        'D', 'd', 'D', 'd',
        'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e',
        'G', 'g',
        'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i',
        'L', 'l', 'L', 'l', 'L', 'l',
        'N', 'n', 'N', 'n', 'N', 'n',
        'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'Oe', 'oe', 'O', 'o', 'o',
        'R', 'r', 'R', 'r',
        'S', 's', 'S', 's', 'S', 's',
        'T', 't', 'T', 't', 'T', 't',
        'U', 'u', 'U', 'u', 'U', 'u', 'Ue', 'ue', 'U', 'u',
        'Y', 'y', 'Y', 'y',
        'Z', 'z', 'Z', 'z', 'Z', 'z',
        'TH', 'th', 'DH', 'dh', 'ss', 'OE', 'oe', 'AE', 'ae', 'u');

    // convert special characters
    $text = str_replace($bad, $good, $text);

    // convert special characters
    $text = utf8_decode($text);
    $text = htmlentities($text);
    $text = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde);/', '$1', $text);
    $text = html_entity_decode($text);

    return $text;
}

function siteTime($_gmt)
{

    if ($_gmt[0] == '+') {
        $gmt = 3600 * (str_replace('+', '', $_gmt));
        $gmt += time();
    } elseif ($_gmt[0] == '-') {
        $gmt = 3600 * (str_replace('-', '', $_gmt));
        $gmt = time() - $gmt;
    } else {
        $gmt = time();
    }

    return $gmt;
}

function random_password($length = 6, $characters = 'ABCDEFGHIJKLMNOPRSTUVWXYZabcdefghijklmnoprstuvwzxy1234567890')
{

    if ($characters == '') {
        return '';
    }
    $chars_length = strlen($characters) - 1;

    mt_srand((double)microtime() * 1000000);

    $pwd = '';
    while (strlen($pwd) < $length) {
        $rand_char = mt_rand(0, $chars_length);
        $pwd .= $characters[$rand_char];
    }

    return $pwd;

}

/**
 * @param int $length
 * @param string $characters
 * @return string
 */
function random_string($length = 10, $characters = 'ABCDEFGHIJKLMNOPRSTUVWXYZabcdefghijklmnoprstuvwzxy1234567890')
{
    return random_password($length, $characters);
}

/**
 * @param $file
 * @return bool
 */
function is_video($file)
{

    $mimes = array(
        ".afl" => "video/animaflex",
        ".asf" => "video/x-ms-asf",
        ".asx" => "video/x-ms-asf-plugin",
        ".avi" => "video/x-msvideo",
        ".avs" => "video/avs-video",
        ".dif" => "video/x-dv",
        ".dl" => "video/x-dl",
        ".dv" => "video/x-dv",
        ".fli" => "video/x-fli",
        ".fmf" => "video/x-atomic3d-feature",
        ".gl" => "video/x-gl",
        ".isu" => "video/x-isvideo",
        ".m1v" => "video/mpeg",
        ".m2v" => "video/mpeg",
        ".mjpg " => "video/x-motion-jpeg",
        ".moov" => "video/quicktime",
        ".mov" => "video/quicktime",
        ".movie" => "video/x-sgi-movie",
        ".mp2" => "video/x-mpeq2a",
        ".mp3" => "video/x-mpeg",
        ".mpa" => "video/mpeg",
        ".mpe" => "video/mpeg",
        ".mpeg" => "video/mpeg",
        ".mpg" => "video/mpeg",
        ".mv" => "video/x-sgi-movie",
        ".qt" => "video/quicktime",
        ".qtc" => "video/x-qtc",
        ".rv" => "video/vnd.rn-realvideo",
        ".scm" => "video/x-scm",
        ".vdo" => "video/vdo",
        ".viv" => "video/vnd.vivo",
        ".vivo" => "video/vnd.vivo",
        ".vos" => "video/vosaic",
        ".xdr" => "video/x-amt-demorun",
        ".xsr" => "video/x-amt-showrun",
    );

    if (in_array($file["type"], $mimes)) return true;
    else return false;
}

/**
 * Disqus function for sso
 */


function dsq_hmacsha1($data, $key)
{
    $blocksize = 64;
    $hashfunc = 'sha1';
    if (strlen($key) > $blocksize)
        $key = pack('H*', $hashfunc($key));
    $key = str_pad($key, $blocksize, chr(0x00));
    $ipad = str_repeat(chr(0x36), $blocksize);
    $opad = str_repeat(chr(0x5c), $blocksize);
    $hmac = pack(
        'H*', $hashfunc(
            ($key ^ $opad) . pack(
                'H*', $hashfunc(
                    ($key ^ $ipad) . $data
                )
            )
        )
    );
    return bin2hex($hmac);
}

/**
 * @param null $slug
 * @return strings
 */
function ro_slug($slug = null)
{
    $slug = trim(strtolower(stripslashes($slug)));
    $map = array(
        '/à|á|å|â|ă|â| |Ă|ă/i' => 'a',
        '/è|é|ê|ẽ|ë/i' => 'e',
        '/ì|í|î|î|Î|Î/i' => 'i',
        '/ò|ó|ô|ø/i' => 'o',
        '/ù|ú|ů|û/i' => 'u',
        '/ș|ș|ş|Ș|Ș|Ş/i' => 's',
        '/ț|ţ|ț|Ț|Ţ/i' => 't',
        '/&icirc;/i' => 'i',
        '/&acirc;/i' => 'a',
        '/”|“|…|’|µ|º|’|&lsquo;|&rsquo;|ldquo|rdquo|„|»|–/i' => '-',
        '/[^[:alnum:]]/' => ' ',
        '/[^\w\s]/' => ' ',
        '/\\s+/' => '-',
#            '/\b([a-z]{1,2})\b/i'=>'',
        '/\-+/' => '-'
    );

    $slug = preg_replace(array_keys($map), array_values($map), $slug);

    return trim($slug, '-');

}

/**
 * @param null $str
 * @return bool|string
 */
function convertAccent($str = null)
{
    if ($str)
        return iconv('Windows-1252', 'ASCII//TRANSLIT//IGNORE', $str);
    else
        return false;
}

/**
 *  functions for creating the slug
 */
function remove_accent($str)
{
    $a = array(
        'á', 'é', 'í', 'ó', 'ú', 'ý', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ý',
        'à', 'è', 'ì', 'ò', 'ù', 'ỳ', 'À', 'È', 'Ì', 'Ò', 'Ù', 'Ỳ',
        'ả', 'ẻ', 'ỉ', 'ỏ', 'ủ', 'ỷ', 'Ả', 'Ẻ', 'Ỉ', 'Ỏ', 'Ủ', 'Ỷ',
        'ã', 'ẽ', 'ĩ', 'õ', 'ũ', 'ỹ', 'Ã', 'Ẽ', 'Ĩ', 'Õ', 'Ũ', 'Ỹ',
        'ạ', 'ẹ', 'ị', 'ọ', 'ụ', 'ỵ', 'Ạ', 'Ẹ', 'Ị', 'Ọ', 'Ụ', 'Ỵ',
        'â', 'ê', 'ô', 'ư', ' ', 'Ê', 'Ô', 'Ư',
        'ấ', 'ế', 'ố', 'ứ', 'Ấ', 'Ế', 'Ố', 'Ứ',
        'ầ', 'ề', 'ồ', 'ừ', 'Ầ', 'Ề', 'Ồ', 'Ừ',
        'ẩ', 'ể', 'ổ', 'ử', 'Ẩ', 'Ể', 'Ổ', 'Ử',
        'ậ', 'ệ', 'ộ', 'ự', 'Ậ', 'Ệ', 'Ộ', 'Ự'
    );
    $b = array(
        'a', 'e', 'i', 'o', 'u', 'y', 'A', 'E', 'I', 'O', 'U', 'Y',
        'a', 'e', 'i', 'o', 'u', 'y', 'A', 'E', 'I', 'O', 'U', 'Y',
        'a', 'e', 'i', 'o', 'u', 'y', 'A', 'E', 'I', 'O', 'U', 'Y',
        'a', 'e', 'i', 'o', 'u', 'y', 'A', 'E', 'I', 'O', 'U', 'Y',
        'a', 'e', 'i', 'o', 'u', 'y', 'A', 'E', 'I', 'O', 'U', 'Y',
        'a', 'e', 'o', 'u', 'A', 'E', 'O', 'U',
        'a', 'e', 'o', 'u', 'A', 'E', 'O', 'U',
        'a', 'e', 'o', 'u', 'A', 'E', 'O', 'U',
        'a', 'e', 'o', 'u', 'A', 'E', 'O', 'U',
        'a', 'e', 'o', 'u', 'A', 'E', 'O', 'U'
    );
    return str_replace($a, $b, $str);
}

/**
 * @param $value
 * @return mixed
 */
function slug($value)
{
    $value = remove_accent($value);
    $value = str_replace(".", '-', $value);
    $value = preg_replace("/@/", ' at ', $value);
    $value = preg_replace("/&/", ' and ', $value);
    $value = preg_replace("/£/", ' pound ', $value);
    $value = preg_replace("/#/", ' hash ', $value);
    $value = preg_replace("/[\-+]/", ' ', $value);
    $value = preg_replace("/[\s+]/", ' ', $value);
    $value = preg_replace("/[\.+]/", '.', $value);
    $value = preg_replace("/[^A-Za-z0-9\.\s]/", '', $value);
    $value = preg_replace("/[\s]/", '-', $value);
    $value = preg_replace("/\-\-+/", '-', $value);

    $value = strtolower($value);

    if (substr($value, -1) == "-") {
        $value = substr($value, 0, -1);
    }
    if (substr($value, 0, 1) == "-") {
        $value = substr($value, 1);
    }

    return $value;
}


/**
 * Functie pentru creare field form
 *
 * @param mixed $input -  array("type" => "", "value"=>"", name=> "", "label"=>"")
 * @param mixed $attr - array - atribute
 * @param mixed $options - array - optiuni pentru select
 * @param mixed $icon - icon nume pentru prepend
 */
//   echo form_field(array("type" => "", "value"=>"", name=> "", "label"=>""), $attr = array(), $options = array(), $icon = null)

function form_field($input = array(), $attr = array(), $options = array(), $icon = null, $help = null)
{

    if (!isset($input["type"]) || !isset($input["name"])) return false;

    $type = trim($input["type"]);
    $name = trim($input["name"]);
    $value = isset($input["value"]) ? trim($input["value"]) : null;
    $label = isset($input["label"]) ? trim($input["label"]) : null;

    $attrib_string = "";
    if (!empty($attr)) {
        foreach ($attr as $k => $v) {
            $attrib_string .= " " . $k . '="' . $v . '"';
        }
    }

    $field = str_repeat("\t", 0) . '<div class="control-group' . ($type == "hidden" ? " no-margin" : "") . '">' . "\n";
    if (isset($label))
        $field .= str_repeat("\t", 1) . '<label class="control-label" for="' . (isset($attr["id"]) ? $attr["id"] : $name) . '">' . $label . '</label>' . "\n";

    $field .= str_repeat("\t", 1) . '<div class="controls' . ($type == "hidden" ? " no-margin" : "") . '"> ' . "\n";
    if ($icon) {
        $field .= str_repeat("\t", 2) . '<div class="input-prepend">' . "\n";
        $field .= str_repeat("\t", 3) . '<span class="add-on"><i class="' . $icon . '"></i></span> ' . "\n";
    }

    if (in_array($type, array("text", "password", "radio", "checkbox", "hidden", "submit", "file"))) {
        $field .= str_repeat("\t", 3) . '<input type="' . $type . '"  ';
        $field .= (isset($attr["id"]) ? "" : 'id="' . $name . '"');
        $field .= (isset($attr["name"]) ? "" : 'name="' . $name . '"');

        if ($value)
            $field .= (isset($attr["value"]) ? "" : 'value="' . $value . '"');

        $field .= ' ' . $attrib_string . '> ' . "\n";

    } elseif ($type == "select") {
        //select

        $field .= str_repeat("\t", 3) . "<select ";
        $field .= (isset($attr["id"]) ? "" : 'id="' . $name . '"');
        $field .= (isset($attr["name"]) ? "" : 'name="' . $name . '"');
        $field .= ' ' . $attrib_string . '> ' . "\n";

        //$field .= str_repeat("\t",4).'<option value="">-- select value --</option>';
        if (!empty($options)) {
            foreach ($options as $ko => $vo) {
                $selected = "";
                if ((string)$ko == (string)$value || (string)$vo == (string)$value) $selected = " selected";
                $field .= str_repeat("\t", 4) . '<option value="' . $ko . '" ' . $selected . '>' . $vo . '</option>';
            }
        }
        $field .= str_repeat("\t", 3) . '</select>';

    } elseif ($type == "textarea") {
        //textarea
        $field .= str_repeat("\t", 3) . '<textarea ';
        $field .= (isset($attr["id"]) ? "" : 'id="' . $name . '"');
        $field .= (isset($attr["name"]) ? "" : 'name="' . $name . '"');
        $field .= ' ' . $attrib_string . '>';
        $field .= $value;
        $field .= '</textarea>';

    } elseif ($type == "button") {
        // button
        $field .= str_repeat("\t", 3) . '<button ';
        $field .= (isset($attr["id"]) ? "" : 'id="' . $name . '"');
        $field .= (isset($attr["name"]) ? "" : 'name="' . $name . '"');
        $field .= ' ' . $attrib_string . '> ' . "\n";
        $field .= str_repeat("\t", 4) . $value;
        $field .= str_repeat("\t", 3) . '</button>';
    }

    if ($icon)
        $field .= str_repeat("\t", 2) . '</div>' . "\n";

    if ($help)
        $field .= str_repeat("\t", 1) . '<span class="help-inline">' . $help . '</span>  ' . "\n";
    $field .= str_repeat("\t", 1) . '</div>  ' . "\n";
    $field .= str_repeat("\t", 0) . '</div>' . "\n";

    return $field;
}


/**
 * function used by array_walk as callback
 *
 * @param mixed $item - value - added by array_walk
 * @param mixed $key - key value - added by array_walk
 * @param mixed $words - array - user custom param passed in this function
 */
function badWords(&$item = null, $key, $words = array())
{
    if (!is_array($words)) return false;

    $skip = array("password", "upload_video", "Filedata");

    if (in_array($key, $skip))
        $item = preg_replace($words["words"], $words["replacements"], $item);

}

/**
 * @param null $text
 * @param int $chars
 * @return bool|string
 */
function short_description($text = null, $chars = 100)
{
    if (!$text) return false;
    $text = strip_tags($text);
    return preg_replace('/\s+?(\S+)?$/', '', substr($text, 0, $chars)) . (strlen($text) > $chars ? '...' : '');
}

function checkChipsMessage($title, $subject)
{

    if (strpos(strtolower($title), 'chips') !== false or strpos(strtolower($title), 'chips') !== false) {
        $class = "chips_message";
        return $class;
    } else {
        return false;
    }

}


/** function to test begining of string
 *
 *
 * @param mixed $haystack
 * @param mixed $needle
 */
function startsWith($haystack, $needle)
{
    return strncmp($haystack, $needle, strlen($needle)) == 0;
}

/**
 * function to test if ending of string match
 *
 * @param mixed $haystack
 * @param mixed $needle
 */
function endsWith($haystack, $needle)
{
    return substr_compare($haystack, $needle, -strlen($needle)) == 0;
}

/**
 * @param $status
 * @return string
 */
function statusType($status)
{
    if ($status == 2) return "<b style='color:#71CCFF'>Pending</b>";

    if ($status == 1) return "<b style='color:#13F100'>Active</b>";
    if ($status == -1) return "<b style='color:#f00'>Denied</b>";

    return "";
}

/**
 * @param $zones
 * @return string
 */
function bannerZones($zones)
{

    if (!$zones) return "No zones selected";

    $zoneDecode = array();
    $zoneDecode = json_decode($zones);
    $formatZone = '';
    foreach ($zoneDecode as $key => $zone) {

        $formatZone .= $zone . " ";
    }

    return $formatZone;

}

if (!function_exists('ago')) {

    /**
     * @param $time
     *
     * @return string
     */
    function ago($time)
    {

        $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

        $now = time();

        $difference = $now - $time;
        $tense = "ago";

        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);

        if ($difference != 1) {
            $periods[$j] .= "s";
        }

        return "$difference $periods[$j] $tense ";
    }

}
