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
        ignore_user_abort(true);

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
                    'type' => 'error',
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
                        'type' => 'error',                        
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
        $count = count($files);
        
        // If we have more then just a few files to process, lets not make the admin wait
        if($count > 10 && !isset($_POST['fprocess']))
        {
            // Open a new request, so we dont have to wait for who knows how long when there is tons of logs!
            $fh = @fsockopen($_SERVER['HTTP_HOST'], 80);
            if($fh)
            {
                $data = "&action=import&fprocess=1&type=". urlencode($_POST['type']); 
                
                // Post the headers and snapshot data
                fwrite($fh, "POST /ASP/index.php?task=importlogs&test HTTP/1.1\r\n");
                fwrite($fh, "HOST: ". $_SERVER['HTTP_HOST'] ."\r\n");
                fwrite($fh, "Cookie: " . session_name() . "=" . session_id() . "; path=/\r\n");
                fwrite($fh, "Content-Type: application/x-www-form-urlencoded\r\n");
                fwrite($fh, "Content-Length: " . strlen($data) . "\r\n\r\n");
                fwrite($fh, $data . "\r\n");
                
                // Give the script a second not to close
                sleep(2);
                fclose($fh);

                // Success
                echo json_encode( 
                    array(
                        'success' => true,
                        'type' => 'info',
                        'message' => "Processing ". $count ." Snapshot logs. Estimated time to completion:  ". format_time($count * 25) // 25 seconds each
                        .". Time may vary depending on system speed. You can check the stats_debug.log (system/logs/stats_debug.log) frequently to get import status."
                    )
                );
            }
            else
            {
                // Failed
                echo json_encode( 
                    array(
                        'success' => false, 
                        'type' => 'error',
                        'message' => "Failed to open connection to localhost!"
                    )
                );
            }
        }
        else
        {
        
            // Open the stats debug file and log processing start
            ErrorLog("----- Importing ". $count ." Logs -----", -1);
            
            // Process each file
            $start_time = microtime(true);
            foreach($files as $file)
            {
                // Get our file parts
                $file = explode("|", $file);
                
                // Get snapshot data
                $data = file_get_contents($file[0] . $file[1]);
                
                // Make sure we have an "EOF"
                if (strpos($data, '\EOF\1') === false) 
                {
                    // Incomplete snapshot, skip it
                    continue;
                }

                // Make sure we know this is an import of existing log data
                if (strpos($file[1], '\import\\') === false) $data .= '\import\1';
                
                // post the data
                $fh = @fsockopen($_SERVER['HTTP_HOST'], 80);
                if($fh)
                {
                    // Post the headers and snapshot data
                    fwrite($fh, "POST /ASP/bf2statistics.php HTTP/1.1\r\n");
                    fwrite($fh, "HOST: localhost\r\n");
                    fwrite($fh, "User-Agent: GameSpyHTTP/1.0\r\n");
                    fwrite($fh, "Content-Type: application/x-www-form-urlencoded\r\n");
                    fwrite($fh, "Content-Length: " . strlen($data) . "\r\n\r\n");
                    fwrite($fh, $data . "\r\n");
                    
                    // Read the buffer
                    $response = fread($fh, 2048);
                    if(strpos($response, "$\tOK\t$") !== false)
                    {
                        $total++;
                    }
                    fclose($fh);
                }
            }
            
            // Open the stats debug file and log processing start
            ErrorLog("----- Import Logs Complete. Imported ". $total ." logs in ". format_time( round(microtime(true) - $start_time) ) ." -----", -1);

            // Success
            echo json_encode( array('success' => true, 'type' => 'success', 'message' => "All System Snapshots Processed! Total logs imported: ". $total) );
        }
    }
}
?>