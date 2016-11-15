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

/**
 * Alter the datetime to log in another date than now.
 *
 * @author Leny Bernard
 */
class AlterDateTimeProcessor
{
    /**
     * @param array $record
     *
     * @return array
     */
    public function __invoke(array $record)
    {
        if (isset($record['context']['@datetime'])) {
            $record['datetime'] = $record['context']['@datetime'];
            unset($record['context']['@datetime']);
        }

        return $record;
    }
}
