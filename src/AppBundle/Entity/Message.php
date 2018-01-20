<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Mosque;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Message
 */
class Message
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $content;

    /**
     * @var boolean
     */
    private $enabled = true;

    /**
     * @var integer
     */
    private $position;

    /**
     * @var File
     */
    private $file;

    /**
     * @var string
     */
    private $image;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var Mosque
     */
    public $mosque;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Message
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Message
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    function isEnabled()
    {
        return $this->enabled;
    }

    function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return self
     */
    public function setFile(File $image = null)
    {
        $this->file = $image;

        if ($image) {
            $this->updated = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $imageName
     *
     * @return self
     */
    public function setImage($imageName)
    {
        $this->image = $imageName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage()
    {
        return $this->image;
    }

    function getMosque(): Mosque
    {
        return $this->mosque;
    }

    function setMosque(Mosque $mosque)
    {
        $this->mosque = $mosque;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return self
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

}
