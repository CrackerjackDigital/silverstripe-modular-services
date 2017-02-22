<?php
namespace Modular\Extensions\Service;

use Modular\Exceptions\Service as Exception;
use Modular\Interfaces\Queueable;
use Modular\Model;

/**
 * Given a model enqueue's it for later processing.
 *
 * @package Modular\Extensions\Service
 */
class Enqueue extends ServiceRequest {
	/**
	 * Add the model to a queue in a 'Queued' status. The model should have a Modular\Fields\QueueStatus field or
	 * otherwise implement the 'Queueable' interface.
	 *
	 * @param Model|Queueable $data a model to enqueue for later
	 * @param array           $options
	 * @param null            $requester
	 * @return mixed
	 * @throws \Modular\Exceptions\Exception
	 * @throws \ValidationException
	 */
	protected function service($data, $options = [], $requester = null) {
		if (!$data->hasMethod('setQueueStatus')) {
			$this->debug_fail(new Exception("model doesn't implement setQueueStatus method"));
		}
		$data->updateQueueStatus(Queueable::StatusQueued);
		$data->write();
		
		return $data;
		
	}
}