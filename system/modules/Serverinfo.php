<?php
class Serverinfo
{
	public function Init() 
	{
		// Get array
		$this->DB = load_database();
		$result = $this->DB->query("SELECT * FROM servers ORDER BY ip ASC;")->fetch_array();
		if($result == false) $result = array();
		
		// Check for post data
		if($_POST['action'] == 'status')
		{
			$this->Process($result);
		}
		else
		{
			// Setup the template
			$Template = load_class('Template');
			$Template->set('servers', $result);
			$Template->render('serverinfo');
		}
	}
	
	public function Process($result)
	{
		// Load the Rcon Class
		$Rcon = load_class('Rcon');
		$data = array();
		foreach($result as $server)
		{
			if($server['rcon_password'] != NULL)
			{
				$result = $Rcon->connect($server['ip'], $server['rcon_port'], $server['rcon_password']);
				if($result == 1)
				{
					$status = '<font color="green">Online</font>';
				}
				else
				{
					$status = '<font color="red">Offline</font>';
				}
			}
			else
			{
				$status = '<font color="red">Rcon Password Not Set!</font>';
			}
			$data[$server['id']] = $status;
		}
		
		echo json_encode( array('data' => $data) );
		
	}
}
?>