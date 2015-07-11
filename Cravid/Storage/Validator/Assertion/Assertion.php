<?php

namespace Cravid\Storage\Validator\Assertion;

abstract class Assertion implements AssertionInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke($value)
    {
        return $this->assert($value);
    }
}