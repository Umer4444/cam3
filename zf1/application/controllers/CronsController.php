<?php

class CronsController extends App_Controller_Action
{


    public function init()
    {

        //if(PHP_SAPI != "cli") exit;

        echo date("d.m.Y H:i:s") . ": start cron " . $this->_request->action . "\n";

        // unele cronuri ruleaza mai mult timp
        set_time_limit(3600 * 2); // 2 ore

        parent::liteInit();

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

    }

    public function postDispatch()
    {
        echo date("d.m.Y H:i:s") . ": end cron " . $this->_request->action . "\n";
    }

    public function termscheckoutAction()
    {

        $this->load("model");
        $this->load("static_pages");

        //p(time()-$this->static_pages->getContent('model_release_form')->added);

        if ((time() - $this->static_pages->getContent('model_release_form')->added) > 259200) {
            $this->model->update(array("terms_agreed" => 0), "terms_agreed=2");

        }

    }

    public function cleanchatAction()
    {
        $this->load("model");
        $this->load("webchat_users");
        $this->load("webchat_lines");
        $this->load("webchat_sessions");

        $models_to_delete = $this->webchat_users->fetchAll(
            $this->webchat_users->select()
                ->where("last_activity < SUBTIME(NOW(),'0:5:0')")
                ->where("id_user=concat('model_', id_model)")
        );

        if (count($models_to_delete) > 0) {
            $deleted = array();
            foreach ($models_to_delete as $model) {
                $deleted[] = $model->id_model;
            }

            $deleted = implode(',', $deleted);
            p($deleted);
            $this->webchat_sessions->delete("find_in_set(id_model, '" . $deleted . "') > 0");
        }


        // Reset chat_type if model did not log out properly
        /*
        $webchat_users = $this->webchat_users->fetchAll("last_activity < SUBTIME(NOW(),'0:5:0')");
        if($webchat_users) $webchat_users = $webchat_users->toArray();
        else $webchat_users = array();
        if (count($webchat_users)>0){
            foreach($webchat_users as $user){
                if($user['chat_type'] != 'normal' && $user['id_user'] == 'model_'.$user['id_model']){//reset field in model table
                    $this->model->setChatType($user['id_model'], 'normal');
                }
            }
        }
        */
        // Deleting chats older than 5 minutes and guest users/models inactive for 5 minutes
        $this->webchat_lines->delete("ts < SUBTIME(NOW(),'0:5:0')");
        $this->webchat_users->delete("last_activity < SUBTIME(NOW(),'0:5:0')"); // and chat_type='normal' and id_user!=concat('model_', id_model)");

    }

    public function autologoutAction()
    {
        $this->autoLogout();
    }

    public function autoLogout()
    {

        $this->load('user');
        $this->load('model');
        $this->load('moderator');
        $this->load('webchat_users');

        $users = $this->user->select()->where("online=1");
        $models = $this->model->select()->where("online=1");
        $moderators = $this->moderator->select()->where("online=1");
        $chat_users = $this->webchat_users->fetchAll();


        if (count($users) > 0) {
            foreach ($users as $v) {

                if ((time() - $v->last_activity) > 1800) {
                    //delete session file
                    //set online = 0
                    $this->user->update(array('online' => 0), db()->quoteInto('id=?', user()->id));
                }

            }
        }
        if (count($models) > 0) {
            foreach ($models as $v) {

                if ((time() - $v->last_activity) > 1800) {
                    //delete session file
                    //set online = 0
                    $this->model->update(array('online' => 0), db()->quoteInto('id=?', user()->id));
                }

            }
        }

        if (count($moderators) > 0) {
            foreach ($moderators as $v) {

                if ((time() - $v->last_activity) > 1800) {
                    //delete session file
                    //set online = 0
                    $this->moderator->update(array('online' => 0), db()->quoteInto('id=?', user()->id));
                }

            }
        }


        if (count($chat_users) > 0) {
            foreach ($chat_users as $v) {
                if ((time() - strtotime($v->last_activity)) > 600) {
                    //delete session user
                    $this->webchat_users->delete("id=" . $v->id);
                }

            }
        }


    }

    public function statsAction()
    {

        $last_h = 3000;
        $this->load('chips');
        $data = $this->chips->fetchLastActivity($last_h);

        if ($data) {
            $statementDelete = "DELETE FROM earning_stats where time >= '" . (time() - (3600 * $last_h)) . "'";
            $statementInsert = "INSERT INTO earning_stats (id_model, amount, type, time) values ";

            foreach ($data as $stats) {
                $statementInsert .= " ('" . $stats['id_receiver'] . "' , '" . $stats['total_amount'] . "' , '" . $stats['type'] . "', '" . $stats['time'] . "'),";
            }

            $statementInsert = trim(trim($statementInsert, ","));


            db()->beginTransaction();
            try {
                db()->query($statementDelete);
                db()->query($statementInsert);

                db()->commit();


            } catch (Exception $e) {
                db()->rollBack();
                throw $e;
            }
        }
    }

    public function getVideoTime($file)
    {
        //$time = exec("ffmpeg -i ". realpath(APPLICATION_PATH."/../public/".$file) . " 2>&1 | grep Duration | cut -d ' ' -f 4 | sed s/,// ", $t2);
        $cmd = "ffmpeg -i " . $file . " 2>&1 | grep Duration | cut -d ' ' -f 4 | sed s/,// ";

        $time = exec($cmd);
        return $time;
    }

    public function makeVideoThumbs($file, $time)
    {

        $frames = config()->screenshots_per_video;

        $path_parts = pathinfo($file);
        $img = $path_parts['dirname'] . '/' . "screen-%x%.png";

        list($hours, $mins, $secs) = explode(':', substr($time, 0, -3));
        $seconds = ($hours * 3600) + ($mins * 60) + $secs;
        //if($seconds > 600) $seconds = 600;

        $fps = $seconds / $frames;

        //$file = realpath(APPLICATION_PATH."/../public/".$file);
        //$img = realpath(APPLICATION_PATH."/../public/screen-%02d.png");
        $jump = (int)$fps;


        for ($i = 1; $i <= $frames; $i++) {
            $img_ = str_replace("%x%", $i, $img);
            $command = "ffmpeg  -i {$file} -ss " . gmdate("H:i:s", $jump * $i) . " -vframes 1 -f image2 -s 160x120 " . $img_;

            exec($command);
        }
        return;
        //exec("ffmpeg -i {$file} -r 1/{$fps} {$img}");
        //$command = "ffmpeg -ss ".$capture_time." -i /path/to/videos/myvideo.avi -vframes 1 -f image2 -s 320x240 /path/to/thumbs/thumb.jpg"
    }

    public function makeConvert($file, $time)
    {
        //public function convertvideoAction() {
        // $file = "files/videos/87/aa/36/e4/1acfcd7a7b3b2f6119232597c667035c.flv";
//        $file = realpath(APPLICATION_PATH."/../public/".$file);
//      $file

        $path_parts = pathinfo($file);
        $new_filename = $path_parts['dirname'] . '/' . $path_parts['filename'];
        //$new_filename .= '.converted'.'.'.$path_parts["extension"];
        $new_filename .= '.converted.mp4';

        $finalFileName = str_replace(".converted", "", $new_filename);

        $libx264_normal = "-coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partb8x8 -me_method hex -subq 6 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 3 -refs 2 -directpred 3 -trellis 0 -flags2 +wpred+dct8x8+fastpskip -wpredp 2";
        //$cmd = "ffmpeg -y -i ".$file." -vcodec libx264 -vsync -1 -bug autodetect -strict experimental ".$libx264_normal." -b 250k -bt 50k -acodec libfaac -ab 56k -ac 2 -s 140x80 ".$new_filename."  2>&1";

        $cmd = "ffmpeg -y -i " . $file . " -vcodec libx264 -vsync -1 -bug autodetect -strict experimental  -b 250k -bt 50k -acodec libmp3lame -b:a 56k -ac 2 -s 768x376 " . $new_filename;

        //new cmd
        //$cmd = "ffmpeg -i ".$file." -codec:v libx264 -preset: high -preset slow -b:v 500k -maxrate 500k -bufsize 1000k -vf scale=-1:480 -threads 0 -codec:a libfaac -b:a 128k " . $new_filename;
        $cmd = "ffmpeg -i " . $file . " -c:v libx264 -preset medium -crf 22 -c:a copy " . $new_filename;


        exec($cmd, $out);

        if (file_exists($new_filename)) {
            if (filesize($new_filename) > 0) {
                $this->makeVideoThumbs($new_filename, $time);

                unlink($file);
                rename($new_filename, $finalFileName);
                return $finalFileName;
                //make thumbs
            }
        }

        return false;
        //unlink($file);
        //rename($new_filename, $finalFileName);
        //update
    }

    public function convertvideoAction()
    {

        $path = realpath(APPLICATION_PATH . "/../../public");
        $video_dir = $path . '/uploads/videos/';


        $this->load("video");
        $this->load("model");

        $videos = $this->video->getUnconverted();

        if ($videos) {

            foreach ($videos as $video) {

                $time = $this->getVideoTime($video_dir . $video->filename);

                //convert to seconds
                $time = strtotime("0000-00-00 $time") - strtotime("0000-00-00 00:00:00");

                $this->video->update(array("state" => "2"), "id=" . $video->id);

                $model = $this->model->getModel($video->id_model);

                $converted_file = $this->makeConvert($video_dir . $video->filename, $time);

                $this->finalizedConversion($converted_file, $video_dir, $time, $video, $model);

                /*                if($converted_file){

                                    $new_path =  str_replace($video_dir,"",$converted_file);
                                    $path_parts = pathinfo($new_path);
                                    $new_cover = $path_parts['dirname']."/screen-3.png";

                                    $this->video->update(array("state" => "1", "cover" => $new_cover, "duration" => $time, "filename" => $new_path), "id=".$video->id);
                                    //notificate admin and moderator



                                    $addNotification = array(
                                            "id_from"       => $model->id,
                                            "type_from"     => "model",
                                            "id_to"         => $model->assigned_to ? $model->assigned_to : 0,
                                            "type_to"       => "moderator",
                                            "type"          => "video_upload",
                                            "notification"  => "Performer ". $model->screen_name . " has uploaded new video ",
                                            "ip"            => $_SERVER["REMOTE_ADDR"],
                                            "date"          => $video->added,
                                            "resource"      => $video->id
                                            );
                                    if ($model->assigned_to != 0) $this->addNotification($addNotification, "admin");
                                    $this->addNotification($addNotification, "moderator");


                                } else {

                                   $model = $this->model->getModel($video->id_model);

                                   $this->video->update(array("state" => "-1"), "id=".$video->id);
                                   $addNotification = array(
                                            "id_from"       => $model->id,
                                            "type_from"     => "model",
                                            "id_to"         => $model->assigned_to ? $model->assigned_to : 0,
                                            "type_to"       => "moderator",
                                            "type"          => "video_upload",
                                            "notification"  => "Performer ". $model->screen_name . " has uploaded new video. Failed conversion ",
                                            "ip"            => $_SERVER["REMOTE_ADDR"],
                                            "date"          => $video->added,
                                            "resource"      => $video->id
                                            );

                                   if($model->assigned_to != 0) $this->addNotification($addNotification, "admin");
                                   $this->addNotification($addNotification, "moderator");

                                   $addNotification = array(
                                            "id_from"       => "0",
                                            "type_from"     => "moderator",
                                            "id_to"         => $model->id,
                                            "type_to"       => "model",
                                            "type"          => "video_upload",
                                            "notification"  => "Uploaded video failed to convert ",
                                            "ip"            => $_SERVER["REMOTE_ADDR"],
                                            "date"          => $video->added,
                                            "resource"      => $video->id
                                            );
                                    $this->addNotification($addNotification, "moderator");
                                }*/
            }
        }
    }

    private function finalizedConversion($converted_file = null, $video_dir, $time, $video, $model)
    {

        $this->load("video");
        $this->load("model");

        //$video = $this->video->getVideoById($video_id);

        //$model = $this->model->getModel($video->id_model);

        if ($converted_file) {

            $new_path = str_replace($video_dir, "", $converted_file);
            $path_parts = pathinfo($new_path);
            $new_cover = $path_parts['dirname'] . "/screen-3.png";

            $this->video->update(array("state" => "1", "cover" => $new_cover, "duration" => $time, "filename" => $new_path), "id=" . $video->id);
            //notificate admin and moderator

            $addNotification = array(
                "id_from" => $model->id,
                "type_from" => "model",
                "id_to" => $model->assigned_to ? $model->assigned_to : 0,
                "type_to" => "moderator",
                "type" => "video_upload",
                "notification" => "Performer " . $model->screen_name . " has uploaded new video ",
                "ip" => $_SERVER["REMOTE_ADDR"],
                "date" => $video->added,
                "resource" => $video->id
            );
            $this->addNotification($addNotification, "admin");
            if ($model->assigned_to != 0) $this->addNotification($addNotification, "moderator");

        } else {


            $this->video->update(array("state" => "-1"), "id=" . $video->id);
            $addNotification = array(
                "id_from" => $model->id,
                "type_from" => "model",
                "id_to" => $model->assigned_to ? $model->assigned_to : 0,
                "type_to" => "moderator",
                "type" => "video_upload",
                "notification" => "Performer " . $model->screen_name . " has uploaded new video. Failed conversion ",
                "ip" => $_SERVER["REMOTE_ADDR"],
                "date" => $video->added,
                "resource" => $video->id
            );

            $this->addNotification($addNotification, "admin");
            if ($model->assigned_to != 0) $this->addNotification($addNotification, "moderator");

            $addNotification = array(
                "id_from" => "0",
                "type_from" => "moderator",
                "id_to" => $model->id,
                "type_to" => "model",
                "type" => "video_upload",
                "notification" => "Uploaded video failed to convert ",
                "ip" => $_SERVER["REMOTE_ADDR"],
                "date" => $video->added,
                "resource" => $video->id
            );
            $this->addNotification($addNotification, "moderator");
        }
    }

    //** send notifications *//

    public function alertfollowersAction()
    {
        $this->load("model_actions");
        $alerts = $this->model_actions->getAlerts();

        $this->load("followers");
        $followers = $this->followers->getFollowers();

        $count_video = array_count_values($alerts["new_video"]);
        $count_photo = array_count_values($alerts["new_photo"]);
        $count_blog = array_count_values($alerts["blog"]);
        $count_pledge = array_count_values($alerts["pledge"]);


        $statementInsert = "INSERT INTO user_notifications (`id_from`, `type_from`, `id_to`, `type_to`, `type`, `notification`, `ip`, `read`, `date`) VALUES ";
        $hasFollowers = false;
        foreach ($followers as $f) {

            if ($f["new_video"] != 0) {
                if (array_key_exists($f["id_follower"], $count_video)) {
                    $hasFollowers = true;
                    $notification = "Model " . $f["model"] . " uploaded " . $count_video[$f["id_follower"]] . " video" . (($count_video[$f["id_follower"]] > 1) ? "s" : '');

                    $statementInsert .= " ('" . $f["id_follower"] . "', 'model' , '" . $f['id_user'] . "', 'user', 'new_video', ";
                    $statementInsert .= "'" . $notification . "', '" . $_SERVER["SERVER_ADDR"] . "', '1', '" . time() . "'),";
                }
            }
            if ($f["new_photo"] != 0) {
                if (array_key_exists($f["id_follower"], $count_photo)) {
                    $hasFollowers = true;
                    $notification = "Model " . $f["model"] . " uploaded " . $count_photo[$f["id_follower"]] . " photo" . (($count_photo[$f["id_follower"]] > 1) ? "s" : '');

                    $statementInsert .= " ('" . $f["id_follower"] . "', 'model', '" . $f['id_user'] . "', 'user', 'new_photo', ";
                    $statementInsert .= "'" . $notification . "', '" . $_SERVER["SERVER_ADDR"] . "' , '1', '" . time() . "'),";
                }
            }
            if ($f["blog"] != 0) {
                if (array_key_exists($f["id_follower"], $count_blog)) {
                    $hasFollowers = true;
                    $notification = "Model " . $f["model"] . " posted " . $count_blog[$f["id_follower"]] . "  blog post" . (($count_blog[$f["id_follower"]] > 1) ? "s" : '');

                    $statementInsert .= " ('" . $f["id_follower"] . "', 'model', '" . $f['id_user'] . "', 'user', 'blog', ";
                    $statementInsert .= "'" . $notification . "', '" . $_SERVER["SERVER_ADDR"] . "' , '1', '" . time() . "'),";
                }
            }
            if ($f["pledge"] != 0) {
                if (array_key_exists($f["id_follower"], $count_pledge)) {
                    $hasFollowers = true;
                    $notification = "Model " . $f["model"] . " added " . $count_blog[$f["id_follower"]] . " pledge" . (($count_blog[$f["id_follower"]] > 1) ? "s" : '');

                    $statementInsert .= " ('" . $f["id_follower"] . "', 'model', '" . $f['id_user'] . "', 'user', 'pledge', ";
                    $statementInsert .= "'" . $notification . "', '" . $_SERVER["SERVER_ADDR"] . "' , '1', '" . time() . "'),";
                }
            }
        }


        $statementInsert = trim(trim($statementInsert, ","));
        $statementDelete = "DELETE FROM model_actions";

        if ($hasFollowers) {
            db()->beginTransaction();
            try {
                db()->query($statementInsert);
                db()->query($statementDelete);
                db()->commit();
            } catch (Exception $e) {
                db()->rollBack();
                throw $e;
            }
        }

    }

    public function alertfollowersonlineAction()
    {

        $this->load("model_actions");
        $alerts = $this->model_actions->getAlerts();

        $this->load("followers");
        $followers = $this->followers->getFollowers();


        $count_online = array_count_values($alerts["online"]);

        $statementInsert = "INSERT INTO user_notifications (`id_from`, `type_from`, `id_to`, `type_to`, `type`, `notification`, `ip`, `read`, `date`, `resource`) VALUES ";
        $hasFollowers = false;
        foreach ($followers as $f) {

            if ($f["when_online"] != 0) {
                if (array_key_exists($f["id_follower"], $count_online)) {
                    $hasFollowers = true;
                    $notification = "Model " . $f["model"] . " is broadcasting";

                    $statementInsert .= " ('" . $f["id_follower"] . "', 'model' , '" . $f['id_user'] . "', 'user', 'model_online',";
                    $statementInsert .= "'" . $notification . "', '" . $_SERVER["SERVER_ADDR"] . "', '0', '" . time() . "', '" . $f["id_follower"] . "'),";
                }
            }
        }

        $statementInsert = trim(trim($statementInsert, ","));
        // p($statementInsert,1);
        $statementDelete = "DELETE FROM model_actions";
        if ($hasFollowers) {
            db()->beginTransaction();
            try {
                db()->query($statementInsert);
                db()->query($statementDelete);
                db()->commit();
            } catch (Exception $e) {
                db()->rollBack();
                throw $e;
            }
        }

    }

    public function newsletterAction()
    {


        $this->load("newsletter");
        //$select = $this->newsletter->select()->where("publish_date<=?", time())->where("status=0");
        $resultSet = $this->newsletter->findUsers($result->id_website);

        foreach ($resultSet as $result) {
            // p($result->toArray(),1);
            $mail = new Zend_Mail();
            $mail->setFrom("no-reply@" . $result->url);
            $mail->addTo($result->email, $result->username);
            $mail->setSubject($result->title);
            $mail->setBodyHtml($result->content);
            $mail->send();
        }

        $this->newsletter->update(array("status" => "1"), new Zend_Db_Expr("status=0"));


    }

    public function manualAction()
    {
        $this->alertfollowersonlineAction();
        $this->alertfollowersAction();
        $this->cleanchatAction();
    }

    /**
     * get active calls, charge them
     *
     */
    public function callchargeAction()
    {
        $this->load("call_log");
        $this->load("user");

        $calls = $this->call_log->getActiveCalls();

        $users_ids = null;

        foreach ($calls as $cal) {
            // take chips from user
            // if not enough chips <say>
            // end call
            //users only -- need mods for moderators and other performers
            $users_ids .= $cal->id_from . ',';

        }

        if ($users_ids)
            $users = $this->user->getUsersChipsByIdSet($users_ids);
        else
            $users = null;

        if ($users) {
            foreach ($users as $id_user => $chips) {
                if ($chips < config()->call_cost) {
                    //say end call
                } else {
                    // charge user, update db
                    $this->user->updateChips(config()->call_cost, $id_user);
                }
            }

        }

    }
}
