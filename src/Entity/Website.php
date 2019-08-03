<?php

namespace App\Entity;

class Website
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $width;

    /**
     * @var string
     */
    private $height;

    /**
     * @var string
     */
    private $device;

    /**
     * @var int
     */
    private $delay;

    private $allowedDelay;

    public function __construct() {
        $this->width = '1920';
        $this->height = '1080';
        $this->device = 'desktop';
        $this->allowedDelay = [0, 200,400, 600, 800, 1000, 2000, 3000, 4000, 5000, 6000, 7000, 8000, 9000, 10000];
        $this->delay = 0;
    }
    
    public function setUrl(string $url) {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setWidth(string $width) {
        $this->width = $width;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function setHeight(string $height) {
        $this->height = $height;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getDimension() {
        return $this->width.'x'.$this->height;
    }

    public function setDevice(string $device) {
        $this->device = $device;
    }

    public function getDevice()
    {
        return $this->device;
    }

    /**
     * @var int delay allowed values = 0, 200,400, 600, 800, 1000, 2000, 3000, 4000, 5000, 6000, 7000, 8000, 9000, 10000
     */
    public function setDelay(int $delay) {
        if(in_array($delay, $this->allowedDelay)) {
            $this->delay = array_search($delay, $this->allowedDelay);
        } else {
            if($delay >= 0 && $delay < count($this->allowedDelay)) {
                $this->delay = $delay;
            }
        }
    }

    public function getDelay() {
        return $this->allowedDelay[$this->delay];
    }

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $imageContent;

    public function setName(string $name) {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setImageContent(string $imageContent) {
        $this->imageContent = $imageContent;
    }

    public function getImageContent()
    {
        return $this->imageContent;
    }
}