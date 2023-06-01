<?php

class Upload extends App_Model
{

    /**
     * @param $base_dir
     * @param string $field_name
     * @return array
     * @throws Exception
     * @throws Zend_File_Transfer_Exception
     */
    public function uploadPhoto($base_dir, $field_name = 'cover')
    {

        ini_set('gd.jpeg_ignore_warning', 1); //some versions of php throw eroor premature end of jpeg file

        $upload = new Zend_File_Transfer_Adapter_Http();
        //$upload->setDestination(realpath(APPLICATION_PATH . '/../../public/uploads'));
        $upload->addValidator('Size', false, 64 * 1024 * 1024); // Set a file size with 64mb
        $upload->addValidator('Extension', false, 'jpeg,gif,jpg,png');
        $upload->addValidator('ImageSize', false, array('minwidth' => 300, 'minheight' => 200));


        if (!$upload->isValid($field_name)) {
            return array("status" => "error", "message" => "Error: File validation failed! Please retry uploading the file.");
        }

        $file = $upload->getFileInfo();
        $extn = pathinfo($file[$field_name]['name'], PATHINFO_EXTENSION);
        $new_filename = md5($file[$field_name]['name'] . microtime() . rand()) . '.' . $extn;

        $nested = $new_filename;

        if (!$nested) {
            throw new \Exception('cannot upload file, could not create folders');
        }

        $upload->addFilter('Rename', $base_dir.$nested);
        if (!$upload->receive($field_name)) {
            return array(
                "status" => "error",
                "message" => "Error: File upload failed! Please retry uploading the file."
            );
        } else {
            return array(
                "status" => "success",
                "message" => "",
                "file" => $new_filename,
                "path" => $base_dir . $nested,
                'nested' => $nested
            );
        }

    }

    public function uploadWatermark($base_dir, $field_name = 'watermark')
    {

        $upload = new Zend_File_Transfer_Adapter_Http();
        $upload->addValidator('Size', false, 16 * 1024 * 1024); // Set a file size with 16mb
        $upload->addValidator('Extension', false, 'jpeg,gif,jpg,png');
        $upload->addValidator('ImageSize', false, array('minwidth' => 10, 'minheight' => 10));

        //p($upload);exit;
        if (!$upload->isValid($field_name)) {
            return array("status" => "error", "message" => "Error: File validation failed! Please retry uploading the file.");
        }

        $file = $upload->getFileInfo();

        $extn = pathinfo($file[$field_name]['name'], PATHINFO_EXTENSION);

        if ($field_name == "watermark")
            $new_filename = 'watermark.' . $extn;
        else
            $new_filename = 'logo-player.' . $extn;

        $nested = $base_dir;

        if (!$nested) {
            array("status" => "error", "message" => "Error: File was not uploaded! Cound not create folders.");
        }

        $upload->addFilter('Rename', array("target" => $nested . $new_filename, "overwrite" => true));

        $upload->setDestination($nested);


        if (!$upload->receive($field_name)) {
            return array("status" => "error", "message" => "Error: File upload failed! Please retry uploading the file.");
        } else {
            return array("status" => "success", "message" => "", "file" => $nested . $new_filename);
        }

    }

    public function resize_image($image, $new, $newwidth, $newheight, $watermark = false, $method = 'compress', $xoffset = 0, $yoffset = 0, $width = 0, $height = 0)
    {

        $parts = explode('.', $image);
        $ext = $parts[count($parts) - 1];
        if ($ext == 'gif')
            $img = imagecreatefromgif($image);
        else if ($ext == 'png')
            $img = imagecreatefrompng($image);
        else
            $img = imagecreatefromjpeg($image);

        $width = imagesx($img);
        $height = imagesy($img);

        if ($img) {

            $scale = min($newwidth / $width, $newheight / $height);
            $check = max($newwidth / $width, $newheight / $height);

            // Get the new dimensions
            $new_width = ceil($scale * $width);
            $new_height = ceil($scale * $height);

            if ($scale < 1 && $check < 1) {
                $sx = $sy = 0;
                $tmp_img = imagecreatetruecolor($new_width, $new_height);
                imagecopyresampled($tmp_img, $img, 0, 0, $sx, $sy, $new_width, $new_height, $width - ($xoffset * 2), $height - ($yoffset * 2));
                imagedestroy($img);
                $img = $tmp_img;
            } else {
                $sx = $sy = 0;
                $tmp_img = imagecreatetruecolor($new_width, $new_height);
                $width -= $sx;

                imagecopyresampled($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagedestroy($img);
                $img = $tmp_img;
            }

            if ($watermark && file_exists($watermark)) {

                $parts = explode('.', $watermark);
                $ext = $parts[count($parts) - 1];
                if ($ext == 'gif')
                    $wimg = imagecreatefromgif($watermark);
                else if ($ext == 'png')
                    $wimg = imagecreatefrompng($watermark);
                else
                    $wimg = imagecreatefromjpeg($watermark);

                $wwidth = imagesx($wimg);
                $wheight = imagesy($wimg);

                imagecopyresampled($img, $wimg, $wwidth, $wheight, 0, 0, $new_width - $wwidth - 5, $new_height - $wheight - 5, $wwidth, $wheight);

            }
        } else return false;

        $save = imagejpeg($img, $new, 100);
        imagedestroy($img);

        if ($wimg) imagedestroy($wimg);
        if ($save) return true;
        else return false;

    }

    public function create_square_image($original_file, $destination_file = NULL, $square_size = 96)
    {

        if (isset($destination_file) and $destination_file != NULL) {
            if (!is_writable($destination_file)) {
                echo '<p style="color:#FF0000">Oops, the destination path is not writable. Make that file or its parent folder wirtable.</p>';
            }
        }

        // get width and height of original image
        $imagedata = getimagesize($original_file);
        $original_width = $imagedata[0];
        $original_height = $imagedata[1];

        if ($original_width > $original_height) {
            $new_height = $square_size;
            $new_width = $new_height * ($original_width / $original_height);
        }
        if ($original_height > $original_width) {
            $new_width = $square_size;
            $new_height = $new_width * ($original_height / $original_width);
        }
        if ($original_height == $original_width) {
            $new_width = $square_size;
            $new_height = $square_size;
        }

        $new_width = round($new_width);
        $new_height = round($new_height);

        // load the image
        if (substr_count(strtolower($original_file), ".jpg") or substr_count(strtolower($original_file), ".jpeg")) {
            $original_image = imagecreatefromjpeg($original_file);
        }
        if (substr_count(strtolower($original_file), ".gif")) {
            $original_image = imagecreatefromgif($original_file);
        }
        if (substr_count(strtolower($original_file), ".png")) {
            $original_image = imagecreatefrompng($original_file);
        }

        $smaller_image = imagecreatetruecolor($new_width, $new_height);
        $square_image = imagecreatetruecolor($square_size, $square_size);

        imagecopyresampled($smaller_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

        if ($new_width > $new_height) {
            $difference = $new_width - $new_height;
            $half_difference = round($difference / 2);
            imagecopyresampled($square_image, $smaller_image, 0 - $half_difference + 1, 0, 0, 0, $square_size + $difference, $square_size, $new_width, $new_height);
        }
        if ($new_height > $new_width) {
            $difference = $new_height - $new_width;
            $half_difference = round($difference / 2);
            imagecopyresampled($square_image, $smaller_image, 0, 0 - $half_difference + 1, 0, 0, $square_size, $square_size + $difference, $new_width, $new_height);
        }
        if ($new_height == $new_width) {
            imagecopyresampled($square_image, $smaller_image, 0, 0, 0, 0, $square_size, $square_size, $new_width, $new_height);
        }


        // if no destination file was given then display a png
        if (!$destination_file) {
            imagepng($square_image, NULL, 9);
        }

        // save the smaller image FILE if destination file given
        if (substr_count(strtolower($destination_file), ".jpg")) {
            imagejpeg($square_image, $destination_file, 100);
        }
        if (substr_count(strtolower($destination_file), ".gif")) {
            imagegif($square_image, $destination_file);
        }
        if (substr_count(strtolower($destination_file), ".png")) {
            imagepng($square_image, $destination_file, 9);
        }

        imagedestroy($original_image);
        imagedestroy($smaller_image);
        imagedestroy($square_image);

    }

    public function resize_image_proportional($original_file = null, $destination_file = null, $destination_width = 190, $destination_height = 140, $type = 0)
    {


        if (!$destination_file || !$original_file) return false;

        // load the image
        if (substr_count(strtolower($original_file), ".jpg") || substr_count(strtolower($original_file), ".jpeg")) {

            $original_image = imagecreatefromjpeg($original_file);
        }
        if (substr_count(strtolower($original_file), ".gif")) {
            $original_image = imagecreatefromgif($original_file);
        }
        if (substr_count(strtolower($original_file), ".png")) {
            $original_image = imagecreatefrompng($original_file);
        }


        if(!isset($original_image)) return false;
        // $type (1=crop to fit, 2=letterbox)
        $source_width = imagesx($original_image);
        $source_height = imagesy($original_image);
        $source_ratio = $source_width / $source_height;
        $destination_ratio = $destination_width / $destination_height;
        if ($type == 1) {
            // crop to fit
            if ($source_ratio > $destination_ratio) {
                // source has a wider ratio
                $temp_width = (int)($source_height * $destination_ratio);
                $temp_height = $source_height;
                $source_x = (int)(($source_width - $temp_width) / 2);
                $source_y = 0;
            } else {
                // source has a taller ratio
                $temp_width = $source_width;
                $temp_height = (int)($source_width / $destination_ratio);
                $source_x = 0;
                $source_y = (int)(($source_height - $temp_height) / 2);
            }
            $destination_x = 0;
            $destination_y = 0;
            $source_width = $temp_width;
            $source_height = $temp_height;
            $new_destination_width = $destination_width;
            $new_destination_height = $destination_height;
        } else {
            // letterbox
            if ($source_ratio < $destination_ratio) {
                // source has a taller ratio
                $temp_width = (int)($destination_height * $source_ratio);
                $temp_height = $destination_height;
                $destination_x = (int)(($destination_width - $temp_width) / 2);
                $destination_y = 0;
            } else {
                // source has a wider ratio
                $temp_width = $destination_width;
                $temp_height = (int)($destination_width / $source_ratio);
                $destination_x = 0;
                $destination_y = (int)(($destination_height - $temp_height) / 2);
            }
            $source_x = 0;
            $source_y = 0;
            $new_destination_width = $temp_width;
            $new_destination_height = $temp_height;
        }
        $destination_image = imagecreatetruecolor($destination_width, $destination_height);
        if ($type > 1) {
            imagefill($destination_image, 0, 0, imagecolorallocate($destination_image, 0, 0, 0));
        }
        imagecopyresampled($destination_image, $original_image, $destination_x, $destination_y, $source_x, $source_y, $new_destination_width, $new_destination_height, $source_width, $source_height);

        // if no destination file was given then display a png
        if (!$destination_file) {
            imagepng($destination_image, $destination_file, 9);
        }

        // save the smaller image FILE if destination file given
        if (substr_count(strtolower($destination_file), ".jpg")) {
            imagejpeg($destination_image, $destination_file, 100);
        }
        if (substr_count(strtolower($destination_file), ".gif")) {
            imagegif($destination_image, $destination_file);
        }
        if (substr_count(strtolower($destination_file), ".png")) {
            imagepng($destination_image, $destination_file, 9);
        }

        imagedestroy($destination_image);
        imagedestroy($original_image);

        // return $destination_image;
    }

    public function uploadVideo($base_dir, $field_name)
    {
        $uploadsDir = 'uploads/videos/';
        $upload = new Zend_File_Transfer_Adapter_Http();
        $upload->setDestination(realpath(APPLICATION_PATH . '/../../public/' . $uploadsDir));

     /*   $upload->addValidator('Size', false, array("max" => 1024 * 1024 * 1024, "min" => "0")); // Set a file size
        $upload->addValidator('Extension', false, array("afl", "asf", "asx", "avi", "avs", "dif", "dl", "dv", "flv", "fli", "fmf", "gl", "isu", "m1v", "m2v", "mjpg", "moov", "mov", "movie", "mp2", "mp3", "mp4", "m4v", "mpa", "mpe", "mpeg", "mpg", "mv", "qt", "qtc", "rv", "scm", "vdo", "viv", "vivo", "vos", "xdr", "xsr", "wmv"));

        $mimes = array(
            "video/animaflex",
            "video/x-ms-asf",
            "video/x-ms-asf-plugin",
            "video/x-msvideo",
            "video/avs-video",
            "video/x-dv",
            "video/x-dl",
            "video/x-dv",
            "video/x-fli",
            "video/x-atomic3d-feature",
            "video/x-gl",
            "video/x-isvideo",
            "video/mpeg",
            "video/mpeg",
            "video/x-motion-jpeg",
            "video/quicktime",
            "video/quicktime",
            "video/x-sgi-movie",
            "video/x-mpeq2a",
            "video/x-mpeg",
            "video/mpeg",
            "video/mpeg",
            "video/mpeg",
            "video/mpeg",
            "video/x-sgi-movie",
            "video/quicktime",
            "video/x-qtc",
            "video/vnd.rn-realvideo",
            "video/x-scm",
            "video/vdo",
            "video/vnd.vivo",
            "video/vnd.vivo",
            "video/vosaic",
            "video/x-amt-demorun",
            "video/x-amt-showrun",
            "video/x-flv",
            "video/mp4",
            "application/x-mpegURL",
            "video/MP2T",
            "video/3gpp",
            "video/quicktime",
            "video/x-msvideo",
            "video/x-ms-wmv",
            "application/annodex",
            "application/mp4",
            "application/ogg",
            "application/vnd.rn-realmedia",
            "application/x-matroska",
            "video/3gpp",
            "video/3gpp2",
            "video/annodex",
            "video/divx",
            "video/flv",
            "video/h264",
            "video/mp4",
            "video/mp4v-es",
            "video/mpeg",
            "video/mpeg-2",
            "video/mpeg4",
            "video/ogg",
            "video/ogm",
            "video/quicktime",
            "video/ty",
            "video/vdo",
            "video/vivo",
            "video/vnd.rn-realvideo",
            "video/vnd.vivo",
            "video/webm",
            "video/x-bin",
            "video/x-cdg",
            "video/x-divx",
            "video/x-dv",
            "video/x-flv",
            "video/x-la-asf",
            "video/x-m4v",
            "video/x-matroska",
            "video/x-motion-jpeg",
            "video/x-ms-asf",
            "video/x-ms-dvr",
            "video/x-ms-wm",
            "video/x-ms-wmv",
            "video/x-msvideo",
            "video/x-sgi-movie",
            "video/x-tivo",
            "video/avi",
            "video/x-ms-asx",
            "video/x-ms-wvx",
            "video/x-ms-wmx",
            "application/octet-stream",


        );

        $upload->addValidator('MimeType', false, $mimes);

        if (!$upload->isValid($field_name)) {
            return array("status" => "error", "message" => "Error: File validation failed! Please retry uploading the file. " . implode(", " . $upload->messages));
        }*/

        $file = $upload->getFileInfo();
        $extn = pathinfo($file[$field_name]['name'], PATHINFO_EXTENSION);
        $new_filename = md5($file[$field_name]['name'] . microtime() . rand()) . '.' . $extn;

        $nested = generateFilePath($base_dir, $new_filename);

        if (!$nested) {
            return array("status" => "error", "message" => "Error: File was not uploaded! Cound not create folders.");
        }

        $upload->addFilter('Rename', $base_dir . $nested . $new_filename);

        if (!$upload->receive($field_name)) {
            return array("status" => "error", "message" => "Error: File upload failed! Please retry uploading the file. ");
        } else {
            return array("status" => "success", "message" => "success", "file" => $uploadsDir .$nested . $new_filename);
        }
    }

    public function getVideoTime($file)
    {
        $time = exec("ffmpeg -i " . realpath(APPLICATION_PATH . "/../public/" . $file) . " 2>&1 | grep Duration | cut -d ' ' -f 4 | sed s/,// ", $t2);
        return $time;
    }

    public function getVideoThumbs($time, $file)
    {
        $fps = $time / 7;
        $file = realpath(APPLICATION_PATH . "/../public/" . $file);
        exec("ffmpeg -i {$file} -f image2 -vf fps=fps=1/{$fps} thumb%02d.bmp");

    }

}