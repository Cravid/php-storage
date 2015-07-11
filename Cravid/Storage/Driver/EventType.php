<?php

namespace Cravid\Storage\Driver;

class EventType
{
	/**
	 * @var string
	 */
	const BEFORE = 'storage.driver.before';
	const AFTER = 'storage.driver.after';
	const EXCEPTION = 'storage.driver.exception';
	const CREATE = 'storage.driver.create';
	const READ = 'storage.driver.read';
	const UPDATE = 'storage.driver.update';
	const DELETE = 'storage.driver.delete';
}