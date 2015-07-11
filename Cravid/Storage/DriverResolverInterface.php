<?php

namespace Cravid\Storage;

interface DriverResolverInterface
{
    /**
     * Returns the available standard drivers.
     *
     * @return string[]
     */
    public function getAvailableDrivers();

    /**
     * Resolves to a driver object.
     *
     * @param string $driverClass The driver class.
     * @param array  $params      An array of parameters that is passed to the driver constructor (optional).
     *
     * @return \Cravid\Storage\Driver\DriverInterface
     */
    public function resolve($driverClass, array $params = array());
}