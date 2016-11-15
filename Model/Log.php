<?php

namespace Troopers\MetricsBundle\Model;

use Monolog\Logger;

class Log
{
    /**
     * @var \DateTime
     */
    private $datetime;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $level = Logger::INFO;

    /**
     * @var array
     */
    private $context;

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->datetime = new \DateTime();
        $this->context = [];
    }

    /**
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @param \DateTime $datetime
     *
     * @return Log
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return Log
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param array $context
     *
     * @return Log
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @param $key
     * @param $value
     */
    public function addContext($key, $value)
    {
        $this->context[$key] = $value;
    }

    /**
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param string $level
     *
     * @return Log
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }
}
