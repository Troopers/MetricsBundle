<?php
namespace Troopers\MetricsBundle\Monolog;

use Doctrine\Common\Util\ClassUtils;
use JMS\Serializer\SerializationContext;
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
    public function serialize($serializer)
    {
        if ($serializer instanceof Serializer) {
            $serializedObject = $serializer->serialize(
                $this->object,
                $this->format,
                ['groups' => $this->serializerGroups]
            );
        } elseif (
            class_exists('\JMS\Serializer\Serializer') &&
            $serializer instanceof \JMS\Serializer\Serializer
        ) {
            $serializedObject = $serializer->serialize(
                $this->object,
                'json',
                SerializationContext::create()
                    ->setGroups(array_values($this->serializerGroups))
            );
        } else {
            throw new \Exception(sprintf(
                'Serialization with %s is not implement', get_class($serializer)
            ));
        }

        return json_decode($serializedObject, true);
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