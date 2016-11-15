<?php

namespace Troopers\MetricsBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Troopers\MetricsBundle\DependencyInjection\MetricsExtension;

class MetricsExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testDefault()
    {
        $container = new ContainerBuilder();
        $loader = new MetricsExtension();
        $loader->load([[
            'serializer_user_groups' => [
                'profile',
            ]
        ]], $container);

        $this->assertTrue($container->hasDefinition('troopers_metrics.monolog_processor.contextSerializer_processor'));
        $this->assertTrue($container->hasDefinition('troopers_metrics.monolog_processor.alterDateTime_processor'));
        $this->assertTrue($container->hasDefinition('troopers_metrics.monolog_processor.user_processor'));
        $this->assertEquals(['profile'], $container->getParameter('metrics.serializer.user_groups'));
    }
}
