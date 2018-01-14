<?php

namespace MKolecki\Behat\FixturesExtension;

use MKolecki\Behat\FixturesExtension\Parser\ParsingException;

interface ParserInterface
{
    /**
     * @param string $content
     * @return mixed
     * @throws ParsingException
     */
    public function parse($content);

    /**
     * @return array
     */
    public function getFileExtensions();
}
