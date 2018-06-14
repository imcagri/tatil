<?php

$username = 'imcagri';
$token    = '****';

class TestTatil extends PHPUnit\Framework\TestCase
{

    public function testDirectoryIsExist(): void
    {
        $this->assertDirectoryExists($_SERVER['DOCUMENT_ROOT'] . '/cache/');
    }

    public function testDirectoryIsWritable(): void
    {
        $this->assertDirectoryIsWritable($_SERVER['DOCUMENT_ROOT'] . '/cache/');
    }

    public function testRepoCount(): void
    {
        global $username, $token;
        include_once dirname(__FILE__) . '/../tatil.class.php';
        $tatil_ins = new Tatil($username, $token);
        //ihave just 2 repo. It will be 3 when i push this.
        $this->assertEquals(3, sizeof($tatil_ins->getRepoList()));
    }

}
