<?php

class scbCron
{
	public $hook;
	public $schedule;
	public $callback_args;

	/* $args:
		string $action OR callback $callback
		string $schedule OR number $interval
		array $callback_args (optional)
	 */
	function __construct($file, $args, $debug = false)
	{
		$this->set_args($args);

		register_activation_hook($file, array($this, 'activate'));
		register_deactivation_hook($file, array($this, 'deactivate'));

		if ( $debug )
			add_action('admin_footer', array(get_class(), 'debug'));
	}

	private function set_args($args)
	{
		extract($args);

		// Set hook
		if ( $callback )
		{
			$this->hook = self::callback_to_string($callback);

			add_action($this->hook, $callback);
		}
		elseif ( $action )
			$this->hook = $action;
		else
			trigger_error('$action OR $callback not set', E_USER_WARNING);

		// Set schedule
		if ( $interval )
		{
			$this->schedule = $interval . 'secs';
			$this->interval = $interval;
			add_filter('cron_schedules', array($this, 'add_timing'));
		}
		elseif ( $schedule )
			$this->schedule = $schedule;
		else
			trigger_error('$schedule OR $interval not set', E_USER_WARNING);

		$this->callback_args = (array) $callback_args;
	}

	//	$args: string $schedule OR number $interval
	function reschedule($args)
	{
		extract($args);

		if ( $schedule && $this->schedule != $schedule )
		{
			$this->schedule = $schedule;
			$this->reset();
		} 
		elseif ( $interval && $this->interval != $interval )
		{
			$this->schedule = $interval . 'secs';
			$this->interval = $interval;
			add_filter('cron_schedules', array($this, 'add_timing'));
			$this->reset();
		}
	}

	function add_timing($schedules)
	{
		if ( isset($schedules[$this->schedule]) )
			return $schedules;

		$schedules[$this->schedule] = array(
			'interval' => $this->interval,
			'display' => $this->interval . ' seconds'
		);

		return $schedules;
	}

	function reset()
	{
		$this->deactivate();
		$this->activate();
	}

	function activate()
	{
		wp_schedule_event(time(), $this->schedule, $this->hook, $this->callback_args);
	}

	function deactivate()
	{
		wp_clear_scheduled_hook($this->hook);
	}

	function debug()
	{
		echo "<pre>";
		var_dump(get_option('cron'));
		echo "</pre>";
	}

	static function callback_to_string($callback)
	{
		if ( !is_array($callback) )
			$str = $callback;
		elseif ( !is_string($callback[0]) )
			$str = get_class($callback[0]) . '_' . $callback[1];
		else
			$str = $callback[0] . '::' . $callback[1];

		$str .= '_hook';

		return $str;
	}
}

