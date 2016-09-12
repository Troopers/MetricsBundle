<?php

namespace Troopers\MetricsBundle\Entity\Dashboard;

use Doctrine\ORM\Mapping as ORM;

/**
 * TimeFilter
 *
 * @ORM\Table(name="metrics_time_filter")
 * @ORM\Entity
 */
class TimeFilter
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="since_custom", type="datetime", nullable=true)
     */
    private $sinceCustom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="since", type="string", length=55)
     */
    private $since = 'last week';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="until", type="datetime", nullable=true)
     */
    private $until;

    /**
     * @var string
     *
     * @ORM\Column(name="timezone", type="string", nullable=true, options={
     *     "default" : "Europe/Paris"
     * })
     */
    private $timezone;

    public function __construct($timezone = "Europe/Paris") {
        $this->timezone = $timezone;
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set since
     *
     * @param string $since
     *
     * @return TimeFilter
     */
    public function setSince($since)
    {
        $this->since = $since;

        return $this;
    }

    /**
     * Get since
     *
     * @return string
     */
    public function getSince($autoTransform = true)
    {
        $now = new \DateTime('now', new \DateTimeZone($this->getTimezone()));
        if ($autoTransform) {
            switch ($this->since) {
                case 'this week':
                    return ($now->format('w') == 1) ? 'today' : 'last monday';
                    break;
                case 'this month':
                    return $now->format('Y/m/01');
                    break;
                case 'this year':
                    return $now->format('Y/01/01');
                    break;
            }
        }
        return $this->since;
    }

    /**
     * Set sinceCustom
     *
     * @param \DateTime $sinceCustom
     *
     * @return TimeFilter
     */
    public function setSinceCustom($sinceCustom)
    {
        $this->sinceCustom = $sinceCustom;

        return $this;
    }

    /**
     * Get sinceCustom
     *
     * @return \DateTime
     */
    public function getSinceCustom()
    {
        return null !== $this->since ? null : $this->sinceCustom;
    }

    /**
     * Set until
     *
     * @param \DateTime $until
     *
     * @return TimeFilter
     */
    public function setUntil($until)
    {
        $this->until = $until;

        return $this;
    }

    /**
     * Get until
     *
     * @return \DateTime
     */
    public function getUntil()
    {
        $until = null !== $this->since ? null : $this->until;
        switch ($this->getSince(false)) {
            case 'yesterday':
                return new \DateTime(
                    'today -1 second',
                    new \DateTimeZone($this->getTimezone())
                );
                break;
            case 'yesterday -1 day':
                return new \DateTime(
                    'yesterday -1 second',
                    new \DateTimeZone($this->getTimezone())
                );
                break;
        }
        return $until;
    }

    /**
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @param string $timezone
     * @return TimeFilter
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;

        return $this;
    }
}

