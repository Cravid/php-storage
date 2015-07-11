<?php

namespace Cravid\Storage;

interface ResultInterface extends \ArrayAccess
{
    /**
     * Returns the number of elements.
     *
     * @return int
     */
    public function count();

    /**
     * Returns the specific iterator.
     *
     * @return \Traversable
     */
    public function getIterator();
}