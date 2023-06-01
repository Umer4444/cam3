<?php

namespace Application\Controller;

use Application\Entity\Role;
use Application\Entity\User;
use Application\Extended\Zend\Uri\Rtmp;
use Application\View\Helper\Stream;
use PerfectWeb\Core\Traits;
use PerfectWeb\Core\Utils\Status;
use Videos\Entity\VideoCoverImage;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class StreamController
 * @package Application\Controller
 */
class StreamController extends AbstractActionController
{

    use Traits\Ensure;

    public function liveAction()
    {}

    public function broadcastAction()
    {}

    public function configAction()
    {

        $viewHelper = $this->getServiceLocator()->get('ViewHelperManager');

        $config['licencekey'] = 'a0cbeff47099e819370bab29daeb77ff22f581d8bc249dda86461212cc52d02428c9bb2403a5120dfdd0f514dd3b145a249642db51ca12797c5184a0006dbf4e';
        $config['publishName'] = $this->user()->getUser()->getStream($this->params()->fromRoute('camera'));
        $config['playback'] = $config['destination'] = $this->getServiceLocator()->get('ViewHelperManager')->get('url')->__invoke('rtmp/domain/stream', [], ['uri' => new Rtmp()]);
        $config['broadcastMode'] = $config['recordMode'] = 'duplex';
        $config['autoSnapInterval'] = $this->cfg('site.cfg')->getConfigValue('broadcast/snapshot');
        $config['enablePreview'] = true;
        $config['forceQuality'] = 'HD CIF SIF HIGH';
        $config['autoStart'] = true;
        $config['disableInteraction'] = true;
        $config['controlbar'] = "Controlbar_normal_timer.swf";

        $size = $this->params()->fromRoute('size');

        if($this->user()->getUser()->getRole() == Role::PERFORMER && $size != Stream::BROADCAST_SMALL){
            $config['broadcastMode'] = $this->user()->getUser()->getBroadcastType();
            $config['forceQuality'] = 'HD CUSTOM';
        }

        if ($this->params()->fromRoute('type') == 'record') {
            $config['publishName'] = $this->user()->getUser()->getStream(User::RECORDER_STREAM);
            $config['playback'] = $viewHelper->get('url')->__invoke('rtmp/domain/play');
            $config['snaptime'] = 2;
            $config['recordtime'] = 600;
            $config['autoStart'] = false;
            $config['snapshotWidth'] = 800;
            $config['snapshotHeight'] = 600;
        }

        $snapshot = $this->params()->fromPost('image');

        if(empty($snapshot)) {
            $res  = http_build_query($config).'&userPresets='.
                file_get_contents('public/'.($this->params()->fromRoute('type') == 'recorder' ?: 'wmle').'/presets.xml');
        }
        else
        {
            // $snapshot is a base 64 encoded string which is a image
            // decode it and write to file with jpg extension
            $res = 'done=1';

            $folder = $this->user()->getUser()->getFolderPath(User::FOLDER_SNAPSHOTS, true). date('Y-m-d').'/';
            $this->ensureFolder($folder);

            if ($image = @imagecreatefromstring(base64_decode($snapshot))) {

                $filename = $folder.$this->params()->fromPost('recorded').uniqid().'.jpg';
                @ImageJPEG($image, $filename, 100);

                if (
                    $this->params()->fromPost('recorded') &&
                    $this->flashMessenger()->hasCurrentMessages(\Videos\Entity\Video::class)
                ) {

                    $messages = $this->flashMessenger()->getMessages(\Videos\Entity\Video::class);
                    $videoType = $messages[0];
                    $title = $messages[1];

                    /** @var \Videos\Entity\Video $video */
                    $video = new $videoType;

                    $video->setUser($this->user()->getLoggedUser());
                    $video->setFilename($this->params()->fromPost('recorded'));
                    $video->setDuration($this->params()->fromPost('duration'));
                    $video->setStatus(Status::INACTIVE);
                    $video->setTitle($title);

                    $folder = $this->user()->getUser()->getFolderPath(User::FOLDER_VIDEOS, true);
                    $this->ensureFolder($folder);
                    $file = $folder.uniqid().'.jpg';
                    copy($filename, $file);

                    $cover = new VideoCoverImage();
                    $cover->setStatus(Status::ACTIVE);
                    $cover->setUser($this->user()->getUser());
                    $cover->setFilename($file);
                    $video->setCover($cover);

                    $this->getEntityManager()->persist($video);
                    $this->getEntityManager()->flush();

                }
            }

        }

        echo $res;
        exit;

    }

}