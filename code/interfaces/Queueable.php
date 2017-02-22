<?php
namespace Modular\Interfaces;

interface Queueable {
	const StatusQueued     = 'Queued';
	const StatusScheduled  = 'Scheduled';
	const StatusProcessing = 'Processing';
	const StatusCompleted  = 'Completed';
	const StatusFailed     = 'Failed';
	const StatusPaused     = 'Paused';
	const StatusHeld       = 'Held';
	const StatusCancelled  = 'Cancelled';
	
	public function getQueueStatus();
	
	/**
	 * @param string $status    one of the StatusABC constants
	 * @param array  $extraData will be updated on the Queueable before writing, e.g. could be 'SentDate' for an email.
	 * @return mixed
	 */
	public function updateQueueStatus($status, $extraData = []);
}