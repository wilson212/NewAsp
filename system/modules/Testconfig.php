<?php
class Testconfig
{
	public function Init() 
	{
		// Check for post data
		if($_POST['action'] == 'test_config')
		{
			$this->ProcessTest();
		}
		else
		{
			// Setup the template
			$Template = load_class('Template');
			$Template->render('testconfig');
		}
	}
	
	public function ProcessTest()
	{
		// Load config options
		$Config = load_class('Config');
		
		// Determine if our save is a success
		echo json_encode( array('success' => $Config->save()) );
	}
}
?>