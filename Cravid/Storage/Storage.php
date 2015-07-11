<?php

namespace Cravid\Storage;

class Storage
{
    use \Psr\Log\LoggerAwareTrait;
    
    /**
     * @var \Cravid\Storage\Driver\Resolver
     */
    private $resolver = null;

    /**
     * Sets the driver resolver object.
     *
     * @var DriverResolver $resolver The DriverResolver object.
     */
    public function setResolver(DriverResolver $resolver)
    {
        
    }
}