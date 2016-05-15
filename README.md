###Processors

####UserProcessor

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

####ContextSerializerProcessor

This processor stands for serializing objects in order to avoid you to pass serializer every where you need to log something.
To communicate with this processor and tell it to do its job, you'll need to wrap your objects in a `ContextSerializerProcessor` 
and give them to the contexts like below:

```
$logger->info('a log with some context object', [
    new SerializeContextItem($someObject, ['serializingGroupLambda'], 'myalias'),
    'a_different_simple_context_prop' => 42,
    new SerializeContextItem($anotherOne, ['serializingGroup1', 'serializingGroup2']),
]);
```

The processor'll handle the context and start to work when it'll find your SerializeContextItems and add the serialized objects to the context by using the groups given in 2nd constructor argument.
If an alias is given, it will use it to store the property, else it will prefix with the class name.

####AlterDateTimeProcessor

For some reason, you may want to log an event in the past (or future why not ?).
The `AlterDateTimeProcessor` will do it for you, all you need to do is to define the `@datetime` context property with the wanted \DateTime value.

```php
$logger->info('a test in the past', ['@datetime' => new \DateTime('10 days ago')]);
```


By default, this processor, is only available for the `app` channel, you can add an other or ovveride the service declaration to use on other handler or channel.
