<?php

namespace MKolecki\Behat\FixturesExtension;

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

    public function __construct(
        FixturesFactory $fixturesFactory,
        array $parsers = array()
    ) {
        $this->fixturesFactory = $fixturesFactory;
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
            throw new InvalidArgumentException("'Could not find parser for '{$ext}' file type when processing path: '{$path}'");
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
     */
    public function load(array $paths)
    {
        $data = array();

        foreach ($paths as $key => $file) {
            $fileData = $this->getParserFor($file)->parse(file_get_contents($file));

            if (!is_int($key)) {
                $fileData = array($key => $fileData);
            }

            $data = array_merge($data, $fileData);
        }

        return $this->fixturesFactory->createFixture($data);
    }
}
