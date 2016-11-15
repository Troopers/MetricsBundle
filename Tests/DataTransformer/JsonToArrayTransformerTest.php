<?php

namespace Troopers\MetricsBundle\Tests\DependencyInjection;

use Troopers\MetricsBundle\DataTransformer\JsonToArrayTransformer;

class JsonToArrayTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the transformer on a low level.
     */
    public function testForwardTransform()
    {
        $transformer = new JsonToArrayTransformer();

        $array = [
            'item', 'item2', 'item3',
        ];

        $json = json_encode($array);

        $this->assertEquals(
            $json,
            $transformer->transform($array)
        );
    }
}
