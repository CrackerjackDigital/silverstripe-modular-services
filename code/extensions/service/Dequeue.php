<?php
namespace Modular\Extensions\Service;

use Member;
use Modular\Fields\QueueStatus;
use Modular\Fields\SentDate;
use Modular\Interfaces\Queueable;
use Modular\Model;
use Modular\Models\Notification;

/**
 * Take next item(s) from the service queue and do something with them.
 *
 * @package Modular\Extensions\Service
 */
class Dequeue extends ServiceRequest {
	/**
	 * Remove a model from the queue (or update it to a status other than 'Queued') and return it.
	 *
	 * @param string|Queueable|Model $data of model to be dequeued and further processed
	 * @param null                   $options
	 * @param \Member|null           $requester
	 * @return \Modular\Interfaces\Queueable|\Modular\Model
	 * @throws \ValidationException
	 */
	protected function service($data, $options = null, $requester = null) {
		$this->trackable_start(__METHOD__);
		
		/** @var Model|Queueable $model */
		$model = $data::get()
		              ->filter(
               [
                   QueueStatus::single_field_name() => QueueStatus::StatusQueued,
               ]
           )->sort('Created asc')->first();
		
		if ($model) {
			$model->updateQueueStatus(QueueStatus::StatusProcessing);
			$model->write();
			$this->trackable_end("Returning model of class '$data' with ID '$model->ID'");
		} else {
			$this->trackable_end("No models of class '$data' in a Queued state");
		}
		return $model;
	}

}