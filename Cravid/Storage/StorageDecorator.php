<?php

namespace Cravid\Storage;

abstract class StorageDecorator
{
    /**
     * @var \Cravid\Storage\Storage
     */
    protected $storage = null;


    /**
     * @param \Cravid\Storage\Storage $storage
     */
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }
}