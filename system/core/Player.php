<?php
class Player
{
	protected $DB;
	protected $messages = array();
	
	public function __construct()
	{
		$this->DB = load_database();
	}
	
	protected function log($message)
	{
		$this->messages[] = $message;
	}
	
	public function deletePlayers($pid)
	{
		if(is_array($pid))
		{
			$pid = implode(", ", $pid);
		}
		
		// Build Data Table Array
		$return = true;
		$DataTables = array('army','awards','kills','kits','mapinfo','maps','player','player_history','unlocks','vehicles','weapons');
		foreach ($DataTables as $DataTable) 
		{
			// Check Table Exists
			$this->DB->query("SHOW TABLES LIKE '" . $DataTable . "'");
			if ($this->DB->num_rows() == 1) 
			{
				// Table Exists, lets clear it
				$query = "DELETE FROM `" . $DataTable . "` ";
				if ($DataTable == 'kills') 
				{
					$query .= "WHERE ((attacker IN ({$pid})) OR (victim IN ({$pid})));";
				} 
				else 
				{
					$query .= "WHERE id IN ({$pid});";
				}

				$result = $this->DB->query($query)->result();
				if ($result) 
				{
					$this->log("Player(s) removed from Table (" . $DataTable . ").");
				} 
				else 
				{
					$return = false;
					$this->log("<font color='red'>ERROR:</font> Player(s)  *NOT* removed from Table (" . $DataTable . ")!");
				}
			}
		}
		
		return $return;
	}
}