<?php

namespace MKolecki\Behat\FixturesExtension;

use Exception;
use InvalidArgumentException;

final class FixturesLoader
{
    /**
     * @var FixturesFactory
     */
    private $fixturesFactory;
    /**
     * @var array
     */
    private $parsers;
    /**
     * @var Isolator
     */
    private $isolator;

    public function __construct(
        FixturesFactory $fixturesFactory,
        Isolator $isolator,
        array $parsers = array()
    ) {
        $this->fixturesFactory = $fixturesFactory;
        $this->isolator = $isolator;

        foreach ($parsers as $parser) {
            $this->addParser($parser);
        }
    }

    /**
     * @param ParserInterface $parser
     */
    private function addParser(ParserInterface $parser)
    {
        foreach ($parser->getFileExtensions() as $ext) {
            $this->parsers[$ext] = $parser;
        }
    }

    /**
     * @param $path
     *
     * @return ParserInterface
     * @throws InvalidArgumentException
     */
    private function getParserFor($path)
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if (!array_key_exists($ext, $this->parsers)) {
            throw new InvalidArgumentException("Could not find parser for '{$ext}' file type when processing path: '{$path}'");
        }

        return $this->parsers[$ext];
    }

    /**
     * @param array $paths
     *
     * @return Fixtures
     *
     * @throws Parser\ParsingException
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function load(array $paths)
    {
        $fixturesData = array();

        foreach ($paths as $key => $file) {
            $parser = $this->getParserFor($file);
            $content = $this->isolator->fileGetContents($file);

            $fileData = $parser->parse($content);

            if (!is_int($key)) {
                $fixturesData[$key] = $fileData;
            } else {
                $fixturesData = array_merge($fixturesData, $fileData);
            }
        }

        return $this->fixturesFactory->createFixture($fixturesData);
    }
}
