<?php

namespace GithubExplorer;

class GithubExplorer {

	public $events;

	public function __construct() {

	}

	public function initWithEvents($events) {
		$this->events = $events;
	}

	public function repos() {
		$out = array();
		if (is_array($this->events) && !empty($this->events)) {
			foreach ($this->events as $event) {
				if (is_array($event) && isset($event['repo']) && (isset($event['repo']['id']))) {
					$out[$event['repo']['id']] = $event['repo'];
				}
			}
		}
		return $out;
	}

}