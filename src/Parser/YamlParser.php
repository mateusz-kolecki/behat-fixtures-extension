<?php

/*
 * This file is part of the Behat Fixtures Extension.
 *
 * Copyright (c) 2018 Mateusz Kołecki <kolecki.mateusz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MKolecki\Behat\FixturesExtension\Parser;

use MKolecki\Behat\FixturesExtension\ParserInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

/**
 * @author Mateusz Kołecki <kolecki.mateusz@gmail.com>
 */
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
