<?php

namespace Troopers\MetricsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Troopers\MetricsBundle\Entity\Dashboard\TimeFilter;

/**
 * Dashboard.

 * @ORM\Table(name="metrics_dashboard")
 * @ORM\Entity
 */
class Dashboard
{
    public $sinceDate;
    public $untilDate;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="text")
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="height", type="integer", nullable=true)
     */
    private $height;

    /**
     * @var string
     *
     * @ORM\Column(name="width", type="text", nullable=true)
     */
    private $width;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Dashboard
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set url.
     *
     * @param string $url
     *
     * @return Dashboard
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        if (0 === strpos($this->url, '<iframe')) {
            preg_match('/src="([^"]+)"/', $this->url, $match);
            $this->url = $match[1];
        }

        return $this->url;
    }

    /**
     * @param TimeFilter $timeFilter
     *
     * @return $this
     */
    public function handleTimeFilter(TimeFilter $timeFilter)
    {
        if (null === $this->sinceDate = $timeFilter->getSinceCustom()) {
            $this->sinceDate = new \DateTime($timeFilter->getSince());
        }
        $this->untilDate = $timeFilter->getUntil() ?: new \DateTime(
            $timeFilter->getTimezone()
        );

        //replace time value in url
        $this->url = preg_replace(
            '/time:\((from:.*)\)\)&/',
            sprintf(
                'time:(from:\'%s\',mode:absolute,to:\'%s\'))&',
                $this->sinceDate->format('Y-m-d\TH:i:s.0'),
                $this->untilDate->format('Y-m-d\TH:i:s.0')
            ),
            $this->url
        );

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
     * @param int $height
     *
     * @return $this
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     *
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }
}
