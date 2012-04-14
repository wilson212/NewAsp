<?php
class Validateranks
{
	public function Init() 
	{
		// Check for post data
		if($_POST['action'] == 'validate')
		{
			$this->Process();
		}
		else
		{
			// Setup the template
			$Template = load_class('Template');
			$Template->render('validateranks');
		}
	}
	
	public function Process()
	{
		// Load the Player class
		$Player = load_class('Player');
		$DB = load_database();
		
		$query = "SELECT `id` FROM player WHERE `score` > 1";
		$pids = $DB->query($query)->fetch_array();
		
		foreach($pids as $pid)
		{
			$Player->validateRank($pid['id']);
		}
		
		echo json_encode(array('success' => true));
	}
}
?>