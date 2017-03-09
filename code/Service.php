<?php
namespace Modular\Services;

use Modular\Model;
use Modular\Object;
use Modular\Traits\enabler;

/**
 * A service provides functions which can be shared by multiple controllers, extensions, tasks etc, possibly
 * called on a scheduled basis, e.g by a cron job, rather than real-time request processing.
 *
 * Functions can be implemented on a service by Modular\Service\Request derived extensions,
 * such as to enqueue or dequeue a message or send an email or run an API import task.
 *
 * @package Modular\Services
 */
class Service extends Object {
	use enabler;

	// this can be set on a derived class to an injector Service name, e.g. 'SheepCountingService' to use instead of
	// the called class.
	const ServiceName = '';

	/**
	 * @return $this
	 */
	public static function factory() {
		return \Injector::inst()->get(static::ServiceName ?: get_called_class(), true, func_get_args());
	}

	/**
	 * Pass the request on to extensions of this service, who should check that the incoming serviceName matches their
	 * class name before doing anything.
	 *
	 * @param string      $serviceName
	 * @param mixed|Model $data, generally a model the service is to act upon
	 * @param mixed       $options
	 * @return array
	 */
	public static function request($serviceName, $data, $options = null) {
		return array_filter(
			static::factory()->extend('request', $serviceName, $data, $options)
		);
	}

}
