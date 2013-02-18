<?php
include 'g3_user_event_config.php';

class Action
{
	public $name;
	public $time;
	function Action($name, $time)
	{
		$this->name = $name;
		$this->time = (int)$time;
	}
	public function __toString()
	{
		return "($this->name, $this->time)";
	}
}
class Interval
{
	public $from;
	public $to;
	public $milliseconds;
	function Interval($from, $to)
	{
		$this->from = $from;
		$this->to = $to;
		$this->milliseconds = $to->time - $from->time;
	}
	public function __toString()
	{
		return "$this->from, $this->to, $this->milliseconds";
	}
}

$handle = fopen($USER_EVENT_LOG_FILE, "r");
if ($handle)
{
	$lastAction = array();
	$data = array();

	fseek($handle, 0 - $USER_EVENT_LOG_TAIL_SIZE, SEEK_END);
	fgets($handle); // get to next line
	while (!feof($handle))
	{
		$line = fgets($handle, 1024);
		if (preg_match("/username\s*=\s*([^\|]+?)\s*\|\s*desktopid\s*=\s*([^\|]+?)\s*\|\s*userIP\s*=\s*([^\|]+?)\s*\|\s*actionname\s*=\s*([^\|]+?)\s*\|\s*actiontime\s*=\s*(\d+)/", $line, $matches))
		{
			$username = $matches[1];
			$desktopid = $matches[2];
			$userip = $matches[3];
			$actionname = $matches[4];
			$actiontime = $matches[5];
			$key = "$username,$desktopid,$userip";
			$action = new Action($actionname, $actiontime);

			if (isset($lastAction[$key]))
			{
				$interval = new Interval($lastAction[$key], $action);
				if (isset($data[$key]))
				{
					$data[$key][] = $interval;
				}
				else
				{
					$data[$key] = array($interval);
				}
			}
			$lastAction[$key] = $action;
		}
	}
	fclose($handle);

	header('Content-type: text/json');
	$first = 1;
	echo "[";
	foreach (array_keys($data) as $key)
	{
		if (!$first)
		{
			echo ",";
		}
		$first = 0;
		echo json_encode($data[$key]);
	}
	echo "]";
}
?>
