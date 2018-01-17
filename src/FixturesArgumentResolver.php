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

use Behat\Behat\Context\Argument\ArgumentResolver;
use ReflectionClass;
use ReflectionParameter;

/**
 * @author Mateusz Kołecki <kolecki.mateusz@gmail.com>
 */
class FixturesArgumentResolver implements ArgumentResolver
{
    /**
     * @var array
     */
    private $config;
    /**
     * @var FixturesLoader
     */
    private $loader;

    private $fixtures = null;

    public function __construct(FixturesLoader $loader, array $config)
    {
        $this->config = $config;
        $this->loader = $loader;
    }

    /**
     * @return Fixtures|null
     */
    public function getFixtures()
    {
        if (!$this->fixtures) {
            $this->fixtures = $this->loader->load($this->config['fixtures']);
        }
        return $this->fixtures;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveArguments(ReflectionClass $classReflection, array $arguments)
    {
        $parameters = $this->getConstructorParameters($classReflection);

        foreach ($parameters as $i => $parameter) {
            $parameterClassName = $this->getClassName($parameter);

            if (null !== $parameterClassName && $this->isFixture($parameterClassName)) {
                $arguments[$i] = $this->getFixtures();
            }
        }

        return $arguments;
    }

    /**
     * @param string $className
     *
     * @return boolean
     */
    private function isFixture($className)
    {
        $fixturesClassName = __NAMESPACE__ . '\\Fixtures';
        return $className === $fixturesClassName || is_subclass_of($className, $fixturesClassName);
    }

    /**
     * @param ReflectionClass $classReflection
     *
     * @return ReflectionParameter[]
     */
    private function getConstructorParameters(ReflectionClass $classReflection)
    {
        return $classReflection->getConstructor() ? $classReflection->getConstructor()->getParameters() : array();
    }

    /**
     * @param ReflectionParameter $parameter
     *
     * @return string|null
     */
    private function getClassName(ReflectionParameter $parameter)
    {
        return $parameter->getClass() ? $parameter->getClass()->getName() : null;
    }
}
