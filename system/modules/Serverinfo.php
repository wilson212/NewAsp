<?php
class Serverinfo
{
	public function Init() 
	{
		// Get array
		$this->DB = load_database();
		$result = $this->DB->query("SELECT * FROM servers ORDER BY ip ASC;")->fetch_array();
		if($result == false) $result = array();
	
		// Setup the template
		$Template = load_class('Template');
		$Template->set('servers', $result);
		$Template->render('serverinfo');
	}
}
?>