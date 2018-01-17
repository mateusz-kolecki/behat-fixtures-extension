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

/**
 * This class is intended to isolate from global methods
 * which helps in testing.
 *
 * @author Mateusz Kołecki <kolecki.mateusz@gmail.com>
 */
class Isolator
{
    /**
     * Wrapper for file_get_contents() function.

     * @param string $path
     *
     * @return string
     * @throws \Exception exception is thrown when file_get_contents return null
     */
    public function fileGetContents($path)
    {
        $content = file_get_contents($path);

        if ($content === false) {
            throw new \Exception("Error while trying to read file: '{$path}'");
        }

        return $content;
    }
}
