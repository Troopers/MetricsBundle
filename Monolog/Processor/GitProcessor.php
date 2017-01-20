<?php
namespace Troopers\MetricsBundle\Monolog\Processor;

class GitProcessor
{
    private $kernelRootDir;
    private static $cache = false;

    /**
     * GitProcessor constructor.
     * @param $kernelRootDir
     */
    public function __construct($kernelRootDir)
    {
        $this->kernelRootDir = $kernelRootDir;
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

        //Get revision number from .git
        if($gitRev = shell_exec("git rev-parse HEAD")){

            return self::$cache = trim($gitRev);
        }

        //Get revision number from REVISION file
        if($gitRev = file_get_contents($this->kernelRootDir.'/../REVISION')){

            return self::$cache = $gitRev;
        }

        self::$cache = null;
    }
}