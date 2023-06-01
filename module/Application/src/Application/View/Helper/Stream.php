<?php

namespace Application\View\Helper;

use Application\Mapper\Injector;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\Helper\AbstractHelper;
use Application\Extended\Zend\Uri\Rtmp;
use PerfectWeb\Core\Traits;

/**
 * Class Stream
 * @package Application\View\Helper
 */
class Stream extends AbstractHelper implements ServiceLocatorAwareInterface
{

    use Traits\Ensure;
    use Traits\User;

    const BROADCAST_FULL = 'full';
    const BROADCAST_SMALL = 'small';

    protected $width = 400;
    protected $height = 400;

    function __invoke($user)
    {
        $this->setUser($this->ensureUser($user));
        return $this;
    }

    function getIframe($width = '400x400', $height = null)
    {

        if ($width && !$height) {
            list($width, $height) = preg_split('/x/', strtolower($width));
            $this->setHeight($height);
            $this->setWidth($width);
        }

        $params = [Injector::USER => $this->getUser()->getId(), 'width' => $this->getWidth(), 'height' => $this->getWidth()];

        return '<iframe width="'.$width.'" height="'.$height.'" frameborder="0" src="' .
            $this->getServiceLocator()->get('url')->__invoke('stream/type', $params, ['force_canonical' => true]) . '"></iframe>';

    }

    function getBroadcast($size = self::BROADCAST_SMALL, $camera = 1)
    {

        if ($this->getServiceLocator()->getServiceLocator()->get('zfcuser_auth_service')->getIdentity()->getId() != $this->getUser()->getId()) {
            echo 'Please login first';
            return false;
        }

        if ($size == self::BROADCAST_SMALL) {
            $path = $size == self::BROADCAST_SMALL ? '_small' : '';
        }
        else {
            $this->setWidth('100%');
            $this->setHeight('520');
        }

        $params = [Injector::USER => $this->getUser()->getId(), 'size' => $size, 'camera' => $camera];

        $id = uniqid();
        echo '<object type="application/x-shockwave-flash" id="'.$id.'" name="'.$id.'" data="/wmle/swf'.$path.'/HDBroadcaster.swf" width="'.$this->getWidth().'" height="'.$this->getHeight().'"><param name="base" value="/wmle/swf'.$path.'/"><param name="flashvars" value="scriptLocation='.
            trim($this->getServiceLocator()->get('url')->__invoke('stream/config', $params), '/').'"></object>';
    }

    function getLive($camera = 1)
    {

        $urlHelper = $this->getServiceLocator()->get('url');
        $m3u8 = $urlHelper('hls/stream', ['stream' => $this->getUser()->getStream($camera, true)]);

        echo '<div style="width: '.$this->getWidth().'px; height: '.$this->getHeight().'px;"
                class="flowplayer" data-live="true" data-rtmp="'.$urlHelper('rtmp/domain/stream', [], ['uri' => new Rtmp()]).'">
                <video autoplay>
                    <source type="application/x-mpegurl" src="'.$m3u8.'">
                    <source type="application/vnd.apple.mpegurl" src="'.$m3u8.'">
                    <source type="video/flash" src="'.$this->getUser()->getStream($camera).'">
                </video>
            </div><style>.flowplayer .fp-waiting {display: none !important;}</style>';
    }

    function __toString()
    {
        return $this->getLive();
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param $width
     *
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param $height
     *
     * @return $this
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

}