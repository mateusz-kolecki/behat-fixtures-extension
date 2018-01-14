<?php
/**
 * Created by PhpStorm.
 * User: mkolecki
 * Date: 13.01.18
 * Time: 23:02
 */

namespace MKolecki\Behat\FixturesExtension;


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
