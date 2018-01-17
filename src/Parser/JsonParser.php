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

/**
 * @author Mateusz Kołecki <kolecki.mateusz@gmail.com>
 */
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
