<?php

namespace Images\Listener;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Images\Entity\Albums;

class AlbumsListener implements EventSubscriber
{

    public function getSubscribedEvents()
    {
        return array(
            Events::postUpdate,
            Events::prePersist,
        );
    }

    public function postUpdate(Albums $album, LifecycleEventArgs $eventArgs)
    {
        $this->updateAlbumImages($album);
        $eventArgs->getObjectManager()->flush();
    }

    public function prePersist(Albums $album, LifecycleEventArgs $eventArgs)
    {
        $this->updateAlbumImages($album);
    }

    private function updateAlbumImages(Albums $album)
    {
        // update photos from the album with album's status
        foreach ($album->getPhotos() as $photo) {
            $photo->setStatus($album->getStatus());
        }
    }

}