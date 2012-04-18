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
        elseif($_POST['action'] == 'configure')
        {
            $this->Configure();
        }
        else
        {
            // Setup the template
            $Template = load_class('Template');
            $Template->set('servers', $result);
            $Template->render('serverinfo');
        }
    }
    
    public function Configure()
    {
        // Get our post data
        $port = mysql_real_escape_string($_POST['port']);
        $password = mysql_real_escape_string($_POST['password']);
        $id = mysql_real_escape_string($_POST['id']);
        
        // Load database and query
        $this->DB = load_database();
        $result = $this->DB->query("UPDATE `servers` SET `rcon_port` = '$port', `rcon_password` = '$password' WHERE `id`=$id;")->result();
        if(!$result)
        {
            echo json_encode( array('success' => false, 'message' => 'Error updating Rcon data in the database. Please refresh the page and try again.') );
        }
        else
        {
            echo json_encode( array('success' => true, 'message' => 'Rcon data saved Successfully!') );
        }
    }
    
    public function Process($result)
    {
        // Load the Rcon Class
        $Rcon = load_class('Rcon');
        $data = array();
        foreach($result as $server)
        {
            $result = $Rcon->connect($server['ip'], $server['rcon_port'], $server['rcon_password']);
            if($result == 0)
            {
                $status = '<font color="red">Offline</font>';
            }
            else
            {
                $status = '<font color="green">Online</font>';
            }
            
            // Close the connection
            $Rcon->close();
            $data[$server['id']] = $status;
        }
        
        echo json_encode( array('data' => $data) );
    }
}
?>