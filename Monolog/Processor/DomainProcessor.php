<?php

namespace Troopers\MetricsBundle\Monolog\Processor;

use Symfony\Component\HttpFoundation\RequestStack;

class DomainProcessor
{
    private $extras_fields;
    private $requestStack;

    public function __construct($extras_fields, RequestStack $requestStack)
    {
        $this->extras_fields = $extras_fields;
        $this->requestStack = $requestStack;
    }

    public function __invoke(array $record)
    {
        foreach ($this->extras_fields as $key => $value) {
            $record['extra'][$key] = $value;
        }

        if ($this->requestStack->getCurrentRequest()) {
            $record['extra']['request_uri'] = $this->requestStack->getCurrentRequest()->getUri();
        }

        return $record;
    }
}
