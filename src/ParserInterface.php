<?php

/*
 * This file is part of the Behat Fixtures Extension.
 *
 * Copyright (c) 2018 Mateusz Kołecki <kolecki.mateusz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MKolecki\Behat\FixturesExtension;

use MKolecki\Behat\FixturesExtension\Parser\ParsingException;

/**
 * @author Mateusz Kołecki <kolecki.mateusz@gmail.com>
 */
interface ParserInterface
{
    /**
     * Parse content and return array representation of data.
     *
     * @param string $content content to parse
     * @return array array representing data
     * @throws ParsingException
     */
    public function parse($content);

    /**
     * Get array of files extension that this parser can handle.
     *
     * @return array array containing file extension (without dot, eg: array('yml', 'yaml'))
     */
    public function getFileExtensions();
}
