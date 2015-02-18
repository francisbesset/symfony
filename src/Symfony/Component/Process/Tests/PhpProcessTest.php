<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Process\Tests;

use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\PhpProcess;

class PhpProcessTest extends \PHPUnit_Framework_TestCase
{
    public function testNonBlockingWorks()
    {
        $expected = 'hello world!';
        $process = new PhpProcess(<<<PHP
<?php echo '$expected';
PHP
        );
        $process->start();
        $process->wait();
        $this->assertEquals($expected, $process->getOutput());
    }

    public function testCommandLine()
    {
        if (defined('PHP_BINARY')) {
            $this->markTestSkipped('The PHP binary is easily available as of PHP 5.4');
        }

        $process = new PhpProcess(<<<PHP
<?php echo 'foobar';
PHP
        );

        $f = new PhpExecutableFinder();
        $commandLine = $f->find();

        $this->assertSame($commandLine, $process->getCommandLine(), '::getCommandLine() returns the command line of PHP');

        $process->start();
        $this->assertSame($commandLine, $process->getCommandLine(), '::getCommandLine() returns the command line of PHP');

        $process->wait();
        $this->assertSame($commandLine, $process->getCommandLine(), '::getCommandLine() returns the command line of PHP');
    }
}
