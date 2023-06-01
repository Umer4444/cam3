<?php

namespace Application\Fixtures;

use Application\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Query\Expr;
use Videos\Entity;
use Videos\Paginator\VodCategoryPaginator;

use Videos\Entity\Video;
use Videos\Entity\VodCategory;

/**
 * @package Application\Fixtures
 */
class VideosData extends RandomData
{

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        $this->users = $manager->getRepository(User::class)->findAll();
        $fundingType = array('fixed', 'flexible');
        $pledgeType = ['subscription', 'projects', 'bounties', 'fun request'];

        foreach ($this->users as $user) {

            for ($i = 0; $i < $this->nr['shows']; $i++) {

                $show = new \Application\Entity\Show();
                $show->setChipsCost($this->base->numberBetween(0, 100));
                $show->setChipsReserved($this->base->numberBetween(0,1));
                $show->setNrUsers(0);
                $show->setUser($user);

                $manager->persist($show);

            }

            for ($i = 0; $i < $this->nr['pledges']; $i++) {

                $pledge = new \Application\Entity\Pledge();
                $pledge->setModel($user);
                $pledge->setDuration($this->base->numberBetween(1, 600));
                $pledge->setContent($this->lorem->paragraph(1));
                $pledge->setDurationDays($this->base->numberBetween(1,30));
                $pledge->setDurationType('days');
                $pledge->setDislikes($this->base->numberBetween(0,100));
                $pledge->setLikes($this->base->numberBetween(0,100));
                $pledge->setFundingType($fundingType[array_rand($fundingType)]);
                $pledge->setStatus(\PerfectWeb\Core\Utils\Status::ACTIVE);
                $pledge->setStartDate(time());
                $pledge->setEndDate(time() + $this->base->numberBetween(10000, 500000));
                $pledge->setCategoryId($this->base->numberBetween(1, $this->nr['categories']));
                $pledge->setGoalAmount($this->base->numberBetween(10, 100));
                $pledge->setGoalRepShare($this->base->numberBetween(10, 20));
                $pledge->setRating($this->base->numberBetween(0,10));
                $pledge->setVotes($this->base->numberBetween(0,100));
                $pledge->setTitle($this->lorem->sentence($this->base->numberBetween(1, 10)));
                $pledge->setResourceId($this->base->numberBetween(1, 5));
                $pledge->setType($this->person->randomElement($pledgeType));
                $manager->persist($pledge);

            }

            $pledges = $user->getPledges();
            $shows = $user->getShows();

            for ($i = 0; $i < $this->nr['videos']; $i++) {

                if ($this->users[$i]->getCategories()->first()) {
                    $category = $this->users[$i]->getCategories()->first();
                }
                else {
                    continue;
                }

                $videoClass = $this->base->randomElement($this->videoClasses);
                $video = new $videoClass();
                $video->setUser($user);
                $video->setCast($this->lorem->sentence(3));
                $video->setCategory($category);
                $video->setCover($this->generatePhoto(Entity\VideoCoverImage::class));
                $video->setDescription($this->lorem->text(rand(10, 40)));
                $video->setTitle($this->lorem->words(rand(1, 6), true));
                $video->setDuration($this->base->numberBetween(5, 300));
                $video->setFilename('http://clips.vorwaerts-gmbh.de/big_buck_bunny.mp4');
                $video->setTags($this->lorem->words(3));
                if ($video instanceof Entity\PremiereVideo)
                {
                    $video->setPublishOn($this->date->dateTime('now'));
                }
                $video->setStatus(1);

                if ($video instanceof Entity\PremiereVideo || $video instanceof Entity\ProfileVideo) {

                    $video->setEntityReference($user->getId());
//                    $video->setType(
//                        ($video instanceof Entity\PremiereVideo) ?
//                            $this->videoTypes['premiere'] :
//                            $this->videoTypes['profile']
//                    );
//                    $video->setUser($user);

                    $this->addVideoCapture($video);

                }
                elseif ($video instanceof Entity\PledgeVideo) {

//                    if ($pledges && $pledge = $pledges->next()) {
//
//                        $video->setEntityReference($pledge);
//                        $video->setPledge($pledge);
//                        $video->setType($this->videoTypes['pledge']);
//
//                    }
//                    else {
//                        continue;
//                    }

                    $this->addVideoCapture($video);

                }
                elseif ($video instanceof Entity\ShowVideo) {

////                    if ($shows && $show = $shows->next()) {
////
////                        $video->setType($this->videoTypes['show']);
////                        $video->setEntityReference($show->getId());
////                        $video->setShow($show);
////                        $video->setPrice($this->base->randomFloat(2, 0, 10));
//
//                    }
//                    else {
//                        continue;
//                    }

                    $this->addVideoCapture($video);

                }
//                elseif ($video instanceof Entity\VodVideo) {
//                    $video->setType($this->videoTypes['vod']);
//                    $video->setCost($this->base->randomFloat(2, 0, 10));
//                    $video->setCategory(new \Videos\Entity\VodCategory);
//                    $this->addVideoCapture($video);
//                }
//
//                else {
//                    $video->setType(Entity\Video::class);
//                    $video->setEntityReference(Entity\Video::class);
//                }

                $manager->persist($video);

            }

        }

        $manager->flush();
        $manager->clear();

    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }

    function addVideoCapture($video)
    {
        for ($i = 0; $i <= 4; $i++) {
            $video->addCapture($this->generatePhoto(Entity\VideoCaptureImage::class, 'active'));
        }
    }

}