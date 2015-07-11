<?php

namespace Cravid\Storage\Validator;

class ValidatorResolver
{
    /**
     * @var Assertion\AssertionInterface[]
     */
    protected $assertions = array();


    /**
     * Registers a new assertion.
     * 
     * @param Assertion\AssertionInterface $assertion
     * @param string                       $name
     */
    public function addAssertion(Assertion\AssertionInterface $assertion, $name = null)
    {
        if (null === $name) {
            $name = strtolower((new \ReflectionClass($assertion))->getShortName());
        }

        $this->assertions[$name] = $assertion;
    }

    /**
     * Returns alls registered assertions.
     * 
     * @return Assertion\AssertionInterface[]
     */
    public function getAssertions()
    {
        return $this->assertions;
    }

    /**
     * Uses the given assertion to validate the given value.
     * 
     * @param string $value
     * @param mixed $value
     *
     * @return bool
     */
    public function validate($assertionName, $value)
    {
        if (!isset($this->assertions[$assertionName])) {
            throw new \InvalidArgumentException(sprintf('Passed assertion name [%s] could not be mapped.', $assertionName));
        }

        return $this->assertions[$assertionName]->assert($value);
    }
}