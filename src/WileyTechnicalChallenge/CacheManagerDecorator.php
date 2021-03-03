<?php

declare(strict_types=1);

namespace WileyTechnicalChallenge;

/**
 * Class CacheManagerDecorator
 */
abstract class CacheManagerDecorator
{
    /**
     * @param mixed ...$params
     *
     * @return string
     */
    abstract public function getKeyByParams(...$params): string;
}
