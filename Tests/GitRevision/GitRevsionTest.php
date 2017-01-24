<?php

namespace Troopers\MetricsBundle\Tests\GitRevsion;

use Troopers\MetricsBundle\Monolog\Processor\GitProcessor;

class GitRevsionTest extends \PHPUnit_Framework_TestCase
{
    static $defaultPath = '/tmp';

    /**
     * @before
     */
    public function before(){
        chdir(self::$defaultPath);
        if (file_exists('REVISION')) {
            unlink('REVISION');
        }
    }

    /**
     * @after
     */
    public function after() {
        GitProcessor::clearCache();
    }

    public function testGitFieldIsAdded()
    {
        $rootPath = self::$defaultPath;

        $gitProcessor = new GitProcessor($rootPath);
        $record = $gitProcessor([]);

        $this->assertArrayHasKey('extra', $record);
        $this->assertArrayHasKey('@git', $record['extra']);

        $this->assertEquals(null, $record['extra']['@git']);
    }

    public function testGitRevisionWithGit()
    {
        $rootPath = __DIR__;
        chdir($rootPath);
        $gitProcessor = new GitProcessor($rootPath);
        $record = $gitProcessor([]);

        $this->assertRegExp("/^[0-9a-f]{40}|[0-9a-f]{6,8}$/", $record['extra']['@git']);
    }

    public function testGitRevisionWithBadRevisionFile()
    {
        $rootPath = self::$defaultPath;

        $revisionFile = fopen('REVISION', 'w');
        fputs($revisionFile, 'This is not a git commit sha');
        fclose($revisionFile);

        $gitProcessor = new GitProcessor($rootPath);
        $record = $gitProcessor([]);

        $this->assertEquals(null, $record['extra']['@git']);
    }

    public function testGitRevisionWithGoodRevisionFile()
    {
        $rootPath = self::$defaultPath;

        $revisionFile = fopen('REVISION', 'w');
        fputs($revisionFile, sha1(time()));
        fclose($revisionFile);

        $gitProcessor = new GitProcessor($rootPath);
        $record = $gitProcessor([]);

        $this->assertRegExp("/^[0-9a-f]{40}|[0-9a-f]{6,8}$/", $record['extra']['@git']);
    }
}
