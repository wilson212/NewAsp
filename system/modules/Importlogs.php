<?php
class Importlogs
{
	public function Init() 
	{
		// Check for post data
		if($_POST['action'] == 'import')
		{
			$this->ProcessTest();
		}
		else
		{
			// Setup the template
			$Template = load_class('Template');
			$Template->render('importlogs');
		}
	}
	
	public function ProcessTest()
	{
		// Make Sure Script doesn't timeout
		set_time_limit(0);

		// Find Log Files
		$regex = '/([0-9]{4})([0-9]{2})([0-9]{2})_([0-9]{4})/';
		$files = array();

		// Get the file names of all incomplete snapshots
		$dir = @opendir( SYSTEM_PATH . DS . 'snapshots' . DS . 'temp' . DS );
		if(!$dir)
		{
			echo json_encode( 
				array(
					'success' => false, 
					'message' => 'Unable to open snapshot directory ('. SYSTEM_PATH . DS . 'snapshots' . DS . 'temp' . DS .')'
				)
			);
			die();
		}
		
		// Read all snapshot log file names
		while(($file = readdir($dir)) !== false)
		{
			if(strpos($file, '.txt'))
			{
				if( preg_match($regex, $file, $sort) )
				{
					$files[] = SYSTEM_PATH . DS . 'snapshots' . DS . 'temp' . DS . "|" . $file;
				}
			}
		}
		@closedir($dir);
		
		// Aslo add processed files if the user select it
		if($_POST['type'] == 'all')
		{
			$dir = @opendir( SYSTEM_PATH . DS . 'snapshots' . DS . 'processed' . DS );
			if(!$dir)
			{
				echo json_encode( 
					array(
						'success' => false, 
						'message' => 'Unable to open snapshot directory ('. SYSTEM_PATH . DS . 'snapshots' . DS . 'processed' . DS .')'
					)
				);
				die();
			}
			
			// Read all snapshot log file names
			while(($file = readdir($dir)) !== false)
			{
				if(strpos($file, '.txt'))
				{
					if( preg_match($regex, $file, $sort) )
					{
						$files[] = SYSTEM_PATH . DS . 'snapshots' . DS . 'processed' . DS . "|" . $file;
					}
				}
			}
			@closedir($dir);
		}
		
		// Sort Files
		sort($files, SORT_STRING);

		// Re-post existing log data to bf2statistics
		$total = 0;
		foreach($files as $file)
		{
			// Get our file parts
			$file = explode("|", $file);
			
			// Get snapshot data
			$data = file_get_contents($file[0] . $file[1]);
			
			// Make sure we have an "EOF"
			if (strpos($data, '\EOF\1') === false) 
			{
				// Older SNAPSHOT.  Insert EOF to ensure bf2statiscs.php processes this...
				$data .= '\EOF\1';
			}

			// Make sure we know this is an import of existing log data
			if (strpos($file[1], "importdata") === false) $data .= '\import\1';
			
			// post the data
			$fh = @fsockopen($_SERVER['HTTP_HOST'], 80);
            if($fh)
            {
                fwrite($fh, "POST /ASP/bf2statistics.php HTTP/1.1\r\n");
                fwrite($fh, "HOST: localhost\r\n");
                fwrite($fh, "User-Agent: GameSpyHTTP/1.0\r\n");
                fwrite($fh, "Content-Type: application/x-www-form-urlencoded\r\n");
                fwrite($fh, "Content-Length: " . strlen($data) . "\r\n\r\n");
                fwrite($fh, $data . "\r\n");
                fclose($fh);
                
                // Remove the old unprocesed file
                unlink($file[0] . $file[1]);
                $total++;
            }
		}

		// Success
		echo json_encode( array('success' => true, 'message' => $total) );
	}
}
?>