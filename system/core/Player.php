<?php
class Player
{
    /* Class Variables */
    protected $DB;
    protected $messages = array();
    protected $rankdata = false;
    protected $awardsdata = false;
    
/*
| ---------------------------------------------------------------
| Constructer
| ---------------------------------------------------------------
*/ 
    public function __construct()
    {
        // Init DB connection
        $this->DB = load_database();
        
        // Load Rank data
        if(!$this->rankdata)
        {
            include( SYSTEM_PATH . DS . 'data'. DS . 'ranks.php' );
            $this->rankdata = $ranks;
        }
    }
    
/*
| ---------------------------------------------------------------
| Method: log()
| ---------------------------------------------------------------
|
| This method logs messages from the methods in this class
|
*/ 
    protected function log($message)
    {
        $this->messages[] = $message;
    }
  
/*
| ---------------------------------------------------------------
| Method: deletePlayer()
| ---------------------------------------------------------------
|
| This method is used to delete all player data from all bf2 tables
|
*/   
    public function deletePlayer($pid)
    {
        // Build Data Table Array
        $return = true;
        $DataTables = getDataTables();
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
                    $query .= "WHERE `attacker` = {$pid} OR `victim` = {$pid};";
                } 
                else 
                {
                    $query .= "WHERE `id` = {$pid};";
                }

                $result = $this->DB->query($query)->result();
                if ($result) 
                {
                    $this->log("Player removed from Table (" . $DataTable . ").");
                } 
                else 
                {
                    $return = false;
                    $this->log("Player *NOT* removed from Table (" . $DataTable . ")!");
                }
            }
        }
        
        return $return;
    }

/*
| ---------------------------------------------------------------
| Method: validateRank()
| ---------------------------------------------------------------
|
| This method will validate and correct the given players rank
| based on the values stored in the "system/data/ranks.php"
|
*/    
    public function validateRank($pid)
    {
        // Get our player
        $query = "SELECT `id`, `score`, `rank` FROM `player` WHERE `id`=". mysql_real_escape_string($pid);
        $this->DB->query($query);
        if($this->DB->num_rows())
        {
            // Setup our player variables
            $row   = $this->DB->fetch_row();
            $pid   = (int)$row['id'];
            $score = (int)$row['score'];
            $rank  = (int)$row['rank'];
            
            // Our set rank and expected ranks
            $setRank = 0;
            $expRank = 0;
            
            // Figure out which rank we are suppossed to be by points
            foreach($this->rankdata as $key => $value)
            {
                // Keep going till we are no longer in the correct point range
                if($value['points'] != -1 && ($value['points'] < $score))
                {
                    $expRank = $key;
                }
            }
            
            // SetRank if we are good!
            if($rank == $expRank)
            {
                $setRank = $rank;
            }

            // If the rank isnt as expected, and we are not a 4 start gen... then we need to process ranks
            elseif($rank != $expRank && $rank != 21)
            {
                // Get player awards
                $query = "SELECT * FROM `awards` WHERE `id` = ". mysql_real_escape_string($pid);
                $awards = $this->DB->query($query)->fetch_array();
                if($awards == false) $awards = array();
                
                // Build our player awards list
                $player_awards = array();
                foreach($awards as $value)
                {
                    $player_awards[$value['awd']] = $value['level'];
                }
                
                // Prevent rank skipping unless the player meets ALL prior rank requirements
                for($i = 1; $i <= $expRank; $i++)
                {
                    // First, we must check to see if the set rank is IN the net rank Reqs.
                    $ranklist = $this->rankdata[$i]['has_rank'];
                    if(is_array($ranklist))
                    {
                        if(!in_array($setRank, $ranklist))
                        {
                            // Check if we are higher ranking
                            $higher = true;
                            foreach($ranklist as $r)
                            {
                                if($setRank < $r) $higher = false;
                            }
                            
                            // If we arent higher rank then the list reqs, then we must
                            // skip to the next rank :(
                            if($higher == false) continue;
                        }
                    }
                    elseif( !($setRank >= $ranklist) )
                    {
                        // Break if the user doesnt have the corret (or higher) rank for rankup
                        break;
                    }

                    // If rank requires medals, then we have to check if the player has them
                    if(!empty($this->rankdata[$i]['has_awards']))
                    {
                        // Good marker
                        $good = true;

                        // Make sure the player has each reward required
                        foreach($this->rankdata[$i]['has_awards'] as $award => $level)
                        {
                            // Check if the award is in the list of players earned awards
                            if(array_key_exists($award, $player_awards))
                            {
                                // Check to see if the level of the earned award is geater or equalvalue of the required award
                                if($player_awards[$award] >= $level)
                                {
                                    // The award is good, move to the next award in the loop
                                    continue;
                                }
                                else
                                {
                                    // Award level is too low
                                    $good = false;
                                }
                            }
                            else
                            {
                                // The user doesnt have the award
                                $good = false;
                            }
                        }
                        
                        // If we have the req. medals for this rank
                        if($good == true)
                        {
                            $setRank = $i;
                        }
                    }
                    else
                    {
                        $setRank = $i;
                    }
                }
            }
            
            // Update Database if we arent a 4 star gen, or smoc with a higher rank award
            if(($rank == 11 && $setRank > 11) || ($rank != 21 && $rank != $setRank))
            {
                $query = "UPDATE `player` SET `rank` = ". $setRank ." WHERE `id` = ". $pid;
                if (!$this->DB->query($query)->result()) 
                {
                    return FALSE;
                }
            }
            
            // Return Success
            return TRUE;
        }
        else 
        {
            return FALSE;
        }
    }

/*
| ---------------------------------------------------------------
| Method: checkAwards()
| ---------------------------------------------------------------
|
| This method will validate and correct the given players 'army'
| awards based on the values stored in the "system/data/awards.php"
|
*/  
    public function checkAwards($pid)
    {
    
    }
}