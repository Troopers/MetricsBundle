<?php

namespace Troopers\MetricsBundle\Monolog\Processor;

class GitProcessor
{
    private $appRootDir;
    private static $cache = false;

    /**
     * GitProcessor constructor.
     *
     * @param $appRootDir
     */
    public function __construct($appRootDir)
    {
        $this->appRootDir = $appRootDir;
    }

    public function __invoke(array $record)
    {
        $record['extra']['@git'] = $this->getRevision();

        return $record;
    }

    private function getRevision()
    {
        if (self::$cache !== false) {
            return self::$cache;
        }

        try {
            //Get revision number from .git
            $gitRev = shell_exec('git rev-parse HEAD');
            if (preg_match('/^[0-9a-f]{40}|[0-9a-f]{6,8}$/', $gitRev)) {
                return self::$cache = trim($gitRev);
            }
        } catch (\Exception $e) {
            error_log('GitProcessor, git rev-parse failed "'.$e->getMessage().'"');
        }

        try {
            $gitRev = file_get_contents($this->appRootDir.'/REVISION');
            //Get revision number from REVISION file
            if (preg_match('/^[0-9a-f]{40}|[0-9a-f]{6,8}$/', $gitRev)) {
                return self::$cache = $gitRev;
            }
        } catch (\Exception $e) {
            error_log('GitProcessor, getRevisionread file REVISION failed : "'.$e->getMessage().'"');
        }

        self::$cache = null;
    }

    /**
     * Clear Cache.
     */
    public static function clearCache()
    {
        self::$cache = false;
    }
}
