<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use PerfectWeb\Core\Traits;

/**
 * BadWords
 *
 * @ORM\Table(name="bad_words")
 * @ORM\Entity
 */
class BadWords
{

    use Traits\User;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="word", type="string", nullable=false)
     */
    private $word;

    /**
     * @var string
     *
     * @ORM\Column(name="replacement", type="string", nullable=false)
     */
    private $replacement;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="badWords")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * @param string $word
     */
    public function setWord($word)
    {
        $this->word = $word;
    }

    /**
     * @return string
     */
    public function getReplacement()
    {
        return $this->replacement;
    }

    /**
     * @param string $replacement
     */
    public function setReplacement($replacement)
    {
        $this->replacement = $replacement;
    }

}