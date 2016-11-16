[![Troopers](https://cloud.githubusercontent.com/assets/618536/18787530/83cf424e-81a3-11e6-8f66-cde3ec5fa82a.png)](http://troopers.agency/?utm_source=MetricsBundle&utm_medium=github&utm_campaign=OpenSource)

[![License](https://img.shields.io/packagist/l/troopers/metrics-bundle.svg)](https://packagist.org/packages/troopers/metrics-bundle)
[![Version](https://img.shields.io/packagist/v/troopers/metrics-bundle.svg)](https://packagist.org/packages/troopers/metrics-bundle)
[![Packagist DL](https://img.shields.io/packagist/dt/troopers/metrics-bundle.svg)](https://packagist.org/packages/troopers/metrics-bundle)
[![Build Status](https://travis-ci.org/Troopers/MetricsBundle.svg?branch=master)](https://travis-ci.org/Troopers/MetricsBundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/113171b2-e7d1-42ea-86d4-a07be1c468bc/mini.png)](https://insight.sensiolabs.com/projects/113171b2-e7d1-42ea-86d4-a07be1c468bc)
[![Twitter Follow](https://img.shields.io/twitter/follow/troopersagency.svg?style=social&label=Follow%20Troopers)](https://twitter.com/troopersagency)

=============

MetricsBundle
=============

## Synopsis

This bundle works with Monolog and is used to improve monitoring or metric logging process thanks to ELK (Elasticsearch, Logstash, Kibana).

## Install

`composer require troopers/metrics-bundle` and registrer in AppKernel

```php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            ...
            new Troopers\MetricsBundle\MetricsBundle(),
            ...
        );

        return $bundles
```

Be sure some serializer is enabled:

- [Symfony serializer](http://symfony.com/doc/current/cookbook/serializer.html#activating-the-serializer).
- [JMS serializer](http://jmsyst.com/libs/serializer).

## Processors

### UserProcessor

This processor will automatically add user informations to every log.
Among availables informations, we can know if the user is `authenticated` (_authenticated: true/false_), when appropriate his `id` and `username`.
Furthermore, this processor will check if you defined some serializer groups in the `metrics.serializer_user_groups` config.

The config below will tell the symfony serializer to serialize the authenticated user by using the profile group:
```yml
metrics:
    serializer_user_groups:
      - profile
```

By default, this processor, is only available for the `app` channel, you can add an other or ovveride the service declaration to use on other handler or channel.

The results will be prefixed by `user_` (user_id, user_city...) and you don't need to give any additional context.

### ContextSerializerProcessor

This processor stands for serializing objects in order to avoid you to pass serializer every where you need to log something.
To communicate with this processor and tell it to do its job, you'll need to wrap your objects in a `ContextSerializerProcessor`
and give them to the contexts like below:

```php
$logger->info('a log with some context object', [
    new SerializeContextItem($someObject, ['serializingGroupLambda'], 'myalias'),
    'a_different_simple_context_prop' => 42,
    new SerializeContextItem($anotherOne, ['serializingGroup1', 'serializingGroup2']),
]);
```

The processor'll handle the context and start to work when it'll find your SerializeContextItems and add the serialized objects to the context by using the groups given in 2nd constructor argument.
If an alias is given, it will use it to store the property, else it will prefix with the class name.

### AlterDateTimeProcessor

For some reason, you may want to log an event in the past (or future why not ?).
The `AlterDateTimeProcessor` will do it for you, all you need to do is to define the `@datetime` context property with the wanted \DateTime value.

```php
$logger->info('a test in the past', ['@datetime' => new \DateTime('10 days ago')]);
```

By default, this processor, is only available for the `app` channel, you can add an other or ovveride the service declaration to use on other handler or channel.

## Log sandbox

Sometime, we just want to send log to test something, this log sandbox to help you to accomplish this small thing.

Check the metrics routes are registered (in your `app/config/routing.yml` or `routing_dev.yml`):
```yml
MetricsBundle:
    resource: "@MetricsBundle/Controller/"
    type:     annotation
    prefix:   /metrics
```

and go to `/metrics/sandbox/newLog` to get your console:
![Log console sandbox](http://new.tinygrab.com/09b6643d7d41cdfe7be8bac0bc7d5ac2a8c0b4f711.png)

## Dashboard and time filter

Once you finished to build your dashboard in kibana, you'll be able to get an iframe to embed it in your website.
To handle dashboard in your admin with a time filter, add a row in the database like this:
```sql
INSERT INTO `metrics_dashboard` (`id`, `name`, `url`, `height`, `width`)
VALUES (1, 'base', '<iframe src="http://your.kibana.url[...]"></iframe>', 768, 1200);
```

Then, embed the `MetricsBundle:Dashboard:show` and override (or not) the available blocks:
```twig
    {% embed 'MetricsBundle:Dashboard:show.html.twig' %}
        {% block body_title %}
            Some thing before the title
            {{ parent() }}
        {% endblock body_title %}
        {# disable the timeFilterForm #}
        {% block body_timeFilterForm %}{% endblock  %}
    {% endembed %}
```

### Time filters

Kibana doesn't integrate its time filter in embed dashboard. The `TimeFilter` and `TimeFilterForm` is here to navigate into dashboards.

Although, TimeFilter is an entity, so you can add some relations between your user and some dashboard.

Here are the available time filters:

- Today
- This week
- This month
- This year
- Yesterday
- Day before yesterday
- Last 15 minutes
- Last 30 minutes
- Last 1 hour
- Last 4 hours
- Last 12 hours
- Last 24 hours
- Last 7 days
- Last 30 days
- Last 60 days
- Last 90 days
- Last 6 months
- Last 1 year
- Last 2 years
- Last 5 years
