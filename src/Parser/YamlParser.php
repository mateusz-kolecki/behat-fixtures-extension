<?php
namespace MKolecki\Behat\FixturesExtension\Parser;

use MKolecki\Behat\FixturesExtension\ParserInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

class YamlParser implements ParserInterface
{
    /**
     * @param string $content
     * @return mixed
     * @throws ParsingException
     */
    public function parse($content)
    {
        $parser = new Parser();
        try {
            return $parser->parse($content);
        }catch (ParseException $e) {
            throw new ParsingException($e->getMessage(), 0, $e);
        }
    }

    /**
     * @return array
     */
    public function getFileExtensions()
    {
        return array(
            'yaml',
            'yml',
        );
    }
}
