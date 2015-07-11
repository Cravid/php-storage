<?php

namespace Cravid\Storage;

class DriverResolver implements DriverResolverInterface
{
    /**
     * @var string
     */
    const DEFAULT_NAMESPACE = __NAMESPACE__ .  '\\Driver';

    /**
     * @var string[]
     */
    protected $availableDrivers = array();


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->fetchAvailableDrivers();
    }

    /**
     * Loads the available driver classes.
     */
    protected function fetchAvailableDrivers()
    {
        foreach (new \DirectoryIterator($this->getDriverFolder()) as $fileInfo)
        {
            if ($fileInfo->isDot()) {
                continue;
            }

            $driverName = str_replace('.php', '', $fileInfo->getBasename());
            $driverClass = '\\' . __NAMESPACE__ . '\\' . $driverName;

            $reflection = new \ReflectionClass($driverClass);
            if ($reflection->implementsInterface($interfaceName) && !$reflection->isAbstract() && !$reflection->isInterface()) {
                $this->addAvailableDriver(strtolower($driverClass), $driverClass);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getAvailableDrivers()
    {
        return $this->availableDrivers;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve($driverClass, array $params = array())
    {
        if (isset($this->availableDrivers[$driverClass])) {
            $driverClass = $this->availableDrivers[$driverClass];
        }

        return new $driverClass($params);
    }
}