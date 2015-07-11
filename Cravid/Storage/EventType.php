<?php

namespace Cravid\Storage;

class EventType
{
	/**
     * @var string
     */
    const BEFORE = 'storage.before';
    const AFTER = 'storage.after';
    const BEFORE_DRIVER = 'storage.before_driver';
    const AFTER_DRIVER = 'storage.after_driver';
    const EXCEPTION = 'storage.exception';
}