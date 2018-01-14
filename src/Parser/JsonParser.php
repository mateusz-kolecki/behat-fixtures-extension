<?php
namespace MKolecki\Behat\FixturesExtension\Parser;

use MKolecki\Behat\FixturesExtension\ParserInterface;

class JsonParser implements ParserInterface
{
    /**
     * @param $content
     * @return mixed
     * @throws ParsingException
     */
    public function parse($content)
    {
        $result = json_decode($content, true, 512);
        $error = json_last_error();

        if ($error !== JSON_ERROR_NONE) {
            throw new ParsingException(json_last_error_msg());
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getFileExtensions()
    {
        return array('json');
    }

}
