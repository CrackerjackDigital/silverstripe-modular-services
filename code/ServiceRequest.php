<?php
namespace Modular\Extensions\Service;

use Member;
use Modular\Interfaces\Service;
use Modular\Traits\debugging;
use Modular\Traits\trackable;

/**
 * Base class for extensions which implement service requests. Services will call the request method by extension
 * and if the requested service name matches this extensions service name (generally the class name), then 'service'
 * method will be called on the extension to actually do something.
 *
 * @package Modular\Extensions\Service
 */
abstract class ServiceRequest extends \DataExtension implements Service {
	use trackable;
	use debugging;
	
	/**
	 * Method which actually does something, will only be called if the 'request' method matches the requested
	 * service to the service request extension.
	 *
	 * @param mixed $data e.g. a model, array etc that the service should operate on
	 * @param mixed $options
	 * @param null  $requester
	 * @return mixed
	 */
	abstract protected function service($data, $options, $requester = null);
	
	protected function requestMatch() {
		return get_class($this);
	}
	
	/**
	 * Check the service name matches this extensions service name and if so calls the service method on this extension.
	 *
	 * @param string $serviceName
	 * @param mixed  $data
	 * @param null   $options
	 * @param null   $requester
	 * @return mixed|string
	 */
	public function request($serviceName, $data, $options = null, $requester = null) {
		$this->trackable_start(__METHOD__, "Called with service name '$serviceName'");
		
		$result = '';
		if (fnmatch($serviceName, $this->requestMatch())) {
			$result = $this->service($data, $options, $requester);
		}
		$this->trackable_end($result);
		
		return $result;
		
	}
}