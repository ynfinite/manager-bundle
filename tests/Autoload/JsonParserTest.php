<?php

/*
 * This file is part of Contao.
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao\ManagerBundle\Test\Autoload;

use Contao\ManagerBundle\Autoload\ConfigInterface;
use Contao\ManagerBundle\Autoload\JsonParser;
use Symfony\Component\Finder\SplFileInfo;

class JsonParserTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceOf()
    {
        $parser = new JsonParser();

        $this->assertInstanceOf('Contao\ManagerBundle\Autoload\JsonParser', $parser);
        $this->assertInstanceOf('Contao\ManagerBundle\Autoload\ParserInterface', $parser);
    }

    public function testDefaultAutoload()
    {
        $parser = new JsonParser();
        $file = new SplFileInfo(
            __DIR__ . '/../Fixtures/Autoload/JsonParser/regular/autoload.json',
            'relativePath',
            'relativePathName'
        );

        /** @var ConfigInterface[] $configs */
        $configs = $parser->parse($file);

        $this->assertCount(1, $configs);
        $this->assertInstanceOf('Contao\ManagerBundle\Autoload\ConfigInterface', $configs[0]);

        $this->assertEquals('Contao\CoreBundle\ContaoCoreBundle', $configs[0]->getClass());
        $this->assertEquals('ContaoCoreBundle', $configs[0]->getName());
        $this->assertEquals([], $configs[0]->getReplace());
        $this->assertEquals(['all'], $configs[0]->getEnvironments());
        $this->assertEquals([], $configs[0]->getLoadAfter());
    }

    public function testNoKeysDefinedAutoload()
    {
        $parser = new JsonParser();
        $file   = new SplFileInfo(
            __DIR__ . '/../Fixtures/Autoload/JsonParser/no-keys-defined/autoload.json',
            'relativePath',
            'relativePathName'
        );

        /** @var ConfigInterface[] $configs */
        $configs = $parser->parse($file);

        $this->assertCount(1, $configs);
        $this->assertInstanceOf('Contao\ManagerBundle\Autoload\ConfigInterface', $configs[0]);

        $this->assertEquals('Contao\CoreBundle\ContaoCoreBundle', $configs[0]->getClass());
        $this->assertEquals('ContaoCoreBundle', $configs[0]->getName());
        $this->assertEquals([], $configs[0]->getReplace());
        $this->assertEquals(['all'], $configs[0]->getEnvironments());
        $this->assertEquals([], $configs[0]->getLoadAfter());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testInvalidJsonWillThrowException()
    {
        $parser = new JsonParser();
        $file   = new SplFileInfo(
            __DIR__ . '/../Fixtures/Autoload/JsonParser/invalid/autoload.json',
            'relativePath',
            'relativePathName'
        );

        $parser->parse($file);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testNoBundlesKeyInJsonWillThrowException()
    {
        $parser = new JsonParser();
        $file   = new SplFileInfo(
            __DIR__ . '/../Fixtures/Autoload/JsonParser/no-bundles-key/autoload.json',
            'relativePath',
            'relativePathName'
        );

        $parser->parse($file);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWillThrowExceptionIfFileNotExists()
    {
        $parser = new JsonParser();
        $file   = new SplFileInfo('iDoNotExist', 'relativePath', 'relativePathName');

        $parser->parse($file);
    }
}
