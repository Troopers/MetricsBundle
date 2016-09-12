<?php

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Troopers\MetricsBundle\Monolog\Processor;

use Monolog\Logger;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Serializer\Serializer;
use Troopers\MetricsBundle\Monolog\SerializeContextItem;

/**
 * Serialize context objects and inject to log.
 *
 * @author Leny Bernard
 */
class ContextSerializerProcessor
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * UserProcessor constructor.
     *
     * @param Serializer   $serializer
     */
    public function __construct($serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param  array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        foreach ($record['context'] as $key => $contextItem) {
            if ($contextItem instanceof SerializeContextItem) {
                $properties = $contextItem->serialize($this->serializer);
                foreach ($properties as $_prop => $_value) {
                    $record['context'][$contextItem->getLabel().'_'.$_prop] = $_value;
                }
                unset($record['context'][$key]);
            }
        }

        return $record;
    }
}
