<?php

namespace App\Data;

use App\Entity\Type;
use App\Entity\Level;
use App\Entity\Theme;
use App\Entity\Subject;

class SearchFilters
{
    private int $offset = 0;
    private ?string $title = null;
    private $ratingAverage = 0;
    private ?string $orderBy = 'uploadedAt';
    private ?Type $type = null;
    private ?Level $level = null;
    private ?Subject $subject = null;
    private ?Theme $theme = null;

    /**
     * Get the value of offset
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * Set the value of offset
     *
     * @return  self
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of ratingAverage
     */
    public function getRatingAverage()
    {
        return $this->ratingAverage;
    }

    /**
     * Set the value of ratingAverage
     *
     * @return  self
     */
    public function setRatingAverage($ratingAverage)
    {
        $this->ratingAverage = $ratingAverage;

        return $this;
    }

    /**
     * Get the value of type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of level
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set the value of level
     *
     * @return  self
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get the value of subject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set the value of subject
     *
     * @return  self
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get the value of theme
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set the value of theme
     *
     * @return  self
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get the value of orderBy
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * Set the value of orderBy
     *
     * @return  self
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;

        return $this;
    }
}
