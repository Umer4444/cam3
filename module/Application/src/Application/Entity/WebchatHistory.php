<?php
/**
 * Created by PhpStorm.
 * User: Razvan
 * Date: 30-Oct-14
 * Time: 15:12 PM
 */

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WebchatHistory
 *
 * @ORM\Table(name="webchat_history",
 * indexes={@ORM\Index(name="id", columns={"id", "user_id"})})
 * @ORM\Entity
 */
class WebchatHistory {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="string", nullable=false, length=32, unique=true)
     * @ORM\Id
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer",  nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="webchatHistory")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     */
    protected $userHistory;

    /**
     * @var int
     *
     * @ORM\Column(name="start", type="integer",  nullable=false)
     */
    private $start;

    /**
     * @var int
     *
     * @ORM\Column(name="end", type="integer",  nullable=true)
     */
    private $end;

    /**
     * @var int
     *
     * @ORM\Column(name="rating_surround", type="integer",  nullable=true, options={"default":0})
     */
    private $ratingSurround;

    /**
     * @var int
     *
     * @ORM\Column(name="votes_surround", type="integer",  nullable=true, options={"default":0})
     */
    private $votesSurround;

    /**
     * @var int
     *
     * @ORM\Column(name="rating_appearance", type="integer",  nullable=true, options={"default":0})
     */
    private $ratingAppearance;

    /**
     * @var int
     *
     * @ORM\Column(name="votes_appearance", type="integer",  nullable=true, options={"default":0})
     */
    private $votesAppearance;

    /**
     * @var int
     *
     * @ORM\Column(name="rating_sound", type="integer",  nullable=true, options={"default":0})
     */
    private $ratingSound;

    /**
     * @var int
     *
     * @ORM\Column(name="votes_sound", type="integer",  nullable=true, options={"default":0})
     */
    private $votesSound;

    /**
     * @var int
     *
     * @ORM\Column(name="rating_light", type="integer",  nullable=true, options={"default":0})
     */
    private $ratingLight;

    /**
     * @var int
     *
     * @ORM\Column(name="votes_light", type="integer",  nullable=true, options={"default":0})
     */
    private $votesLight;

    /**
     * @return int
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param int $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getRatingAppearance()
    {
        return $this->ratingAppearance;
    }

    /**
     * @param int $ratingAppearance
     */
    public function setRatingAppearance($ratingAppearance)
    {
        $this->ratingAppearance = $ratingAppearance;
    }

    /**
     * @return int
     */
    public function getRatingLight()
    {
        return $this->ratingLight;
    }

    /**
     * @param int $ratingLight
     */
    public function setRatingLight($ratingLight)
    {
        $this->ratingLight = $ratingLight;
    }

    /**
     * @return int
     */
    public function getRatingSound()
    {
        return $this->ratingSound;
    }

    /**
     * @param int $ratingSound
     */
    public function setRatingSound($ratingSound)
    {
        $this->ratingSound = $ratingSound;
    }

    /**
     * @return int
     */
    public function getRatingSurround()
    {
        return $this->ratingSurround;
    }

    /**
     * @param int $ratingSurround
     */
    public function setRatingSurround($ratingSurround)
    {
        $this->ratingSurround = $ratingSurround;
    }

    /**
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param int $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param int $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getVotesAppearance()
    {
        return $this->votesAppearance;
    }

    /**
     * @param int $votesAppearance
     */
    public function setVotesAppearance($votesAppearance)
    {
        $this->votesAppearance = $votesAppearance;
    }

    /**
     * @return int
     */
    public function getVotesLight()
    {
        return $this->votesLight;
    }

    /**
     * @param int $votesLight
     */
    public function setVotesLight($votesLight)
    {
        $this->votesLight = $votesLight;
    }

    /**
     * @return int
     */
    public function getVotesSound()
    {
        return $this->votesSound;
    }

    /**
     * @param int $votesSound
     */
    public function setVotesSound($votesSound)
    {
        $this->votesSound = $votesSound;
    }

    /**
     * @return int
     */
    public function getVotesSurround()
    {
        return $this->votesSurround;
    }

    /**
     * @param int $votesSurround
     */
    public function setVotesSurround($votesSurround)
    {
        $this->votesSurround = $votesSurround;
    }

    /**
     * @return int
     */
    public function getUserHistory()
    {
        return $this->userHistory;
    }

    /**
     * @param int $userHistory
     */
    public function setUserHistory($userHistory)
    {
        $this->userHistory = $userHistory;
    }


}