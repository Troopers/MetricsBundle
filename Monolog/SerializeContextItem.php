<?php
namespace Troopers\MetricsBundle\Monolog;

use Doctrine\Common\Util\ClassUtils;
use Symfony\Component\Serializer\Serializer;

/**
 * Class SerializeContextItem
 *
 * @package Troopers\MetricsBundle\Monolog
 */
class SerializeContextItem
{
    protected $format;
    /**
     * @var Object
     */
    protected $object;
    /**
     * @var array
     */
    protected $serializerGroups;
    /**
     * @var string
     */
    private $label;

    /**
     * SerializeContextItem constructor.
     *
     * @param object $object
     * @param array  $serializerGroups
     * @param string $label
     * @param string $format
     */
    public function __construct($object, array $serializerGroups, $label = null, $format = 'json')
    {
        $this->object = $object;
        $this->serializerGroups = $serializerGroups;
        $this->format = $format;
        $this->label = $label;
    }

    /**
     * @param Serializer $serializer
     *
     * @return []
     */
    public function serialize(Serializer $serializer)
    {
        return json_decode(
            $serializer->serialize($this->object, $this->format, ['groups' => $this->serializerGroups]), true
        );
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return ClassUtils::getClass($this->object);
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label ?: $this->getClassName();
    }
}