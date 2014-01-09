<?php

use October\Rain\Config\Rewrite;

class RewriteTest extends TestCase
{

    public function testToFile()
    {
        $writer = new Rewrite;

        $filePath = __DIR__ . '../../fixtures/Config/sample-config.php';
        $tmpFile = __DIR__ . '../../fixtures/Config/temp-config.php';
        copy($filePath, $tmpFile);

        $contents = $writer->toFile($tmpFile, ['connections.sqlite.driver'=>'sqlbite']);

        $result = include $tmpFile;
        $this->assertArrayHasKey('connections', $result);
        $this->assertArrayHasKey('sqlite', $result['connections']);
        $this->assertArrayHasKey('driver', $result['connections']['sqlite']);
        $this->assertEquals('sqlbite', $result['connections']['sqlite']['driver']);

        unlink($tmpFile);
    }

    public function testToContent()
    {
        $writer = new Rewrite;

        /*
         * Rewrite a single level string
         */
        $contents = file_get_contents(__DIR__ . '../../fixtures/Config/sample-config.php');
        $contents = $writer->toContent($contents, ['url' => 'http://octobercms.com']);
        $result = eval('?>'.$contents);

        $this->assertTrue(is_array($result));
        $this->assertArrayHasKey('url', $result);
        $this->assertEquals('http://octobercms.com', $result['url']);

        /*
         * Rewrite a second level string
         */
        $contents = $writer->toContent($contents, ['memcached.host' => '69.69.69.69']);
        $result = eval('?>'.$contents);

        $this->assertArrayHasKey('memcached', $result);
        $this->assertArrayHasKey('host', $result['memcached']);
        $this->assertEquals('69.69.69.69', $result['memcached']['host']);

        /*
         * Rewrite a third level string
         */
        $contents = $writer->toContent($contents, ['connections.mysql.host' => '127.0.0.1']);
        $result = eval('?>'.$contents);

        $this->assertArrayHasKey('connections', $result);
        $this->assertArrayHasKey('mysql', $result['connections']);
        $this->assertArrayHasKey('host', $result['connections']['mysql']);
        $this->assertEquals('127.0.0.1', $result['connections']['mysql']['host']);

        /*
         * Test alternative quoting
         */
        $contents = $writer->toContent($contents, ['timezone' => 'The Fifth Dimension']);
        $contents = $writer->toContent($contents, ['timezoneAgain' => 'The "Sixth" Dimension']);
        $result = eval('?>'.$contents);

        $this->assertArrayHasKey('timezone', $result);
        $this->assertArrayHasKey('timezoneAgain', $result);
        $this->assertEquals('The Fifth Dimension', $result['timezone']);
        $this->assertEquals('The "Sixth" Dimension', $result['timezoneAgain']);

        /*
         * Rewrite a boolean
         */
        $contents = $writer->toContent($contents, ['debug' => false]);
        $contents = $writer->toContent($contents, ['debugAgain' => true]);
        $contents = $writer->toContent($contents, ['bullyIan' => true]);
        $contents = $writer->toContent($contents, ['booLeeIan' => false]);
        $contents = $writer->toContent($contents, ['memcached.weight' => false]);
        $contents = $writer->toContent($contents, ['connections.pgsql.password' => true]);
        $result = eval('?>'.$contents);

        $this->assertArrayHasKey('debug', $result);
        $this->assertArrayHasKey('debugAgain', $result);
        $this->assertArrayHasKey('bullyIan', $result);
        $this->assertArrayHasKey('booLeeIan', $result);
        $this->assertFalse($result['debug']);
        $this->assertTrue($result['debugAgain']);
        $this->assertTrue($result['bullyIan']);
        $this->assertFalse($result['booLeeIan']);

        $this->assertArrayHasKey('memcached', $result);
        $this->assertArrayHasKey('weight', $result['memcached']);
        $this->assertFalse($result['memcached']['weight']);
        
        $this->assertArrayHasKey('connections', $result);
        $this->assertArrayHasKey('pgsql', $result['connections']);
        $this->assertArrayHasKey('password', $result['connections']['pgsql']);
        $this->assertTrue($result['connections']['pgsql']['password']);

        /*
         * Rewrite an integer
         */
        $contents = $writer->toContent($contents, ['aNumber' => 69]);
        $result = eval('?>'.$contents);

        $this->assertArrayHasKey('aNumber', $result);
        $this->assertEquals(69, $result['aNumber']);
    }

}