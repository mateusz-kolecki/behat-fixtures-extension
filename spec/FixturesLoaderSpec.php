<?php

/*
 * This file is part of the Behat Fixtures Extension.
 *
 * Copyright (c) 2018 Mateusz Kołecki <kolecki.mateusz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\MKolecki\Behat\FixturesExtension;

use Exception;
use MKolecki\Behat\FixturesExtension\FixturesFactory;
use MKolecki\Behat\FixturesExtension\Isolator;
use MKolecki\Behat\FixturesExtension\Parser\ParsingException;
use MKolecki\Behat\FixturesExtension\ParserInterface;
use PhpSpec\ObjectBehavior;

/**
 * @author Mateusz Kołecki <kolecki.mateusz@gmail.com>
 */
class FixturesLoaderSpec extends ObjectBehavior
{
    function let(
        Isolator $isolator,
        ParserInterface $parserOne,
        ParserInterface $parserTwo
    ) {
        $this->beConstructedWith(
            new FixturesFactory('/'),
            $isolator,
            array($parserOne, $parserTwo)
        );

        $dataFoo = array(
            'foo' => array(
                'bar' => 1234,
                'baz' => 'bleh',
            )
        );

        $dataBar = array(
            'bar' => array(
                'foo' => 4321,
                'zab' => 'helb',
            )
        );

        $isolator->fileGetContents('some/path.foo')->willReturn(':foo:');
        $isolator->fileGetContents('some/path.bar')->willReturn(':bar:');

        $parserOne->parse(':foo:')->willReturn($dataFoo);
        $parserOne->getFileExtensions()->willReturn(array('foo'));

        $parserTwo->parse(':bar:')->willReturn($dataBar);
        $parserTwo->getFileExtensions()->willReturn(array('bar'));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('MKolecki\Behat\FixturesExtension\FixturesLoader');
    }

    function it_will_return_fixtures()
    {
        $this->load(array('some/path.foo'))
            ->shouldReturnAnInstanceOf('MKolecki\\Behat\\FixturesExtension\\Fixtures');
    }

    function it_will_return_fixtures_using_matching_parser()
    {
        $this->load(array('some/path.foo'))
            ->get('foo/bar')->shouldBe(1234);

        $this->load(array('some/path.bar'))
            ->get('bar/foo')->shouldBe(4321);
    }

    function it_will_load_all_files_with_matching_parsers_and_merge_results()
    {
        $paths = array(
            'some/path.foo',
            'some/path.bar',
        );

        $fixtures = $this->load($paths);

        $fixtures->get('foo/bar')->shouldBe(1234);
        $fixtures->get('bar/foo')->shouldBe(4321);
    }

    function it_will_merge_results_and_add_prefix_when_path_have_assoc_key()
    {
        $fixtures = $this->load(array(
            'foo-prefix' => 'some/path.foo',
            'bar-prefix' => 'some/path.bar',
        ));

        $fixtures->get('foo-prefix/foo/bar')->shouldBe(1234);
        $fixtures->get('bar-prefix/bar/foo')->shouldBe(4321);
    }

    function it_will_throw_exception_when_cannot_find_parser()
    {
        $expectedMessage = "Could not find parser for 'zzz' file type when processing path: 'some/path.zzz'";
        $expectedException = new \InvalidArgumentException($expectedMessage);

        $this->shouldThrow($expectedException)
            ->duringLoad(array('some/path.zzz'));
    }

    function it_will_throw_parser_exception_when_parsing_fails(ParserInterface $parserOne) {
        $parserOne->parse(':foo:')->willThrow(new ParsingException('Error message'));

        $this->shouldThrow(new ParsingException('Error message'))
            ->duringLoad(array('some/path.foo'));
    }

    function it_will_throw_exception_on_eror_while_reading_file(Isolator $isolator) {
        $isolator->fileGetContents('some/path.foo')->willThrow(new Exception('Error message'));

        $this->shouldThrow(new Exception('Error message'))
            ->duringLoad(array('some/path.foo'));
    }
}
