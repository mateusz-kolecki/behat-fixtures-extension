<?php

namespace MKolecki\Behat\FixturesExtension;

/**
 * This class is intended to isolate from global methods
 * which helps in testing.
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
