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
 * @author Mateusz Kołecki <kolecki.mateusz@gmail.com>
 */
class FixturesFactory
{
    /**
     * @var string
     */
    private $delimiter;

    /**
     * @param string $delimiter
     */
    public function __construct($delimiter)
    {
        $this->delimiter = $delimiter;
    }

    /**
     * @param array $data
     *
     * @return Fixtures
     */
    public function createFixture($data)
    {
        return new Fixtures($data, $this->delimiter);
    }

}
