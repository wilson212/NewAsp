<?php
class Player
{
    protected $DB;
    protected $messages = array();
    
    public function __construct()
    {
        $this->DB = load_database();
    }
    
/*
| ---------------------------------------------------------------
| Method: log()
| ---------------------------------------------------------------
*/ 
    protected function log($message)
    {
        $this->messages[] = $message;
    }
  
/*
| ---------------------------------------------------------------
| Method: deletePlayer()
| ---------------------------------------------------------------
*/   
    public function deletePlayers($pid)
    {
        if(is_array($pid))
        {
            $pid = implode(", ", $pid);
        }
        
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

/*
| ---------------------------------------------------------------
| Method: validateRank()
| ---------------------------------------------------------------
*/    
    public function validateRank($pid, $force = false)
    {
        // Declare this!
        static $rankdata = false;
        
        // Get our player
        $query = "SELECT `id`, `score`, `rank` FROM `player` WHERE `id`=". mysql_real_escape_string($pid);
        $this->DB->query($query);
        if($this->DB->num_rows())
        {
            // Setup our player variables
            $row = $this->DB->fetch_row();
            $expRank = 0;
            $pid = (int)$row['id'];
            $score = (int)$row['score'];
            $rank  = (int)$row['rank'];
            
            // Inlcude our rank data
            include_once( SYSTEM_PATH . DS . 'data'. DS . 'ranks.php' );
            if($rankdata == false) $rankdata = $ranks;
            
            // Figure out which rank we are suppossed to be by points
            foreach($rankdata as $key => $value)
            {
                // Keep going till we are no longer in the correct point range
                if($value['points'] != -1 && ($value['points'] < $score))
                {
                    $expRank = $key;
                }
            }
            
            // No SGM or the corps here
            if($expRank == 11) $expRank--;
            
            // Dont promote MSG to 1SG or MGYSGT to SGM if there are the lower rank isnt in the has_rank list!
            if(($rank == 7 && $expRank == 8) ||($rank == 9 && $expRank == 10))
            {
                if(is_array($rankdata[$expRank]['has_rank']))
                {
                    if(!in_array($rank)) $expRank--;
                }
                elseif($rankdata[$expRank]['has_rank'] != $rank)
                {
                    $expRank--;
                }
            }

            // Only update if Rank is less than expected or we are forcing our hand here
            if($rank < $expRank || ($force == true && $rank != $expRank && $rank != 21))
            {
                // If rank requires medals, then we have to check if the player has them
                if(!empty($rankdata[$expRank]['has_awards']))
                {
                    $good = true;
                    $query = "SELECT * FROM `awards` WHERE `id` = ". mysql_real_escape_string($pid);
                    $this->DB->query($query);
                    if($this->DB->num_rows())
                    {
                        // Build a list of player awards
                        $awards = $this->DB->fetch_array();
                        foreach($awards as $value)
                        {
                            $player_awards[$value['awd']] = $value['level'];
                        }
                        
                        // Another ;)
                        foreach($rankdata[$expRank]['has_awards'] as $award => $level)
                        {
                            // Check if the award is in the list of players earned awards
                            if(array_key_exists($award, $player_awards))
                            {
                                $lvl = $player_awards[$award];
                                
                                // Check to see if the level of the earned award is geater or equalvalue of the required award
                                if($lvl >= $level)
                                {
                                    // The award is good, move to the next award in the loop
                                    continue;
                                }
                                else
                                {
                                    // return FALSE because the level is too low
                                    $good = false;
                                }
                            }
                            else
                            {
                                // Return FALSE because the user doesnt have the award
                                $good = false;
                            }
                        }
                    }
                    else
                    {
                        $good = false;
                    }
                    
                    // If we cant have this rank, then go back one more
                    if($good == false)
                    {
                        $expRank = $expRank -1;
                    }
                }
                
                // Update Database
                $query = "UPDATE `player` SET `rank` = ".$expRank." WHERE `id` = ". $pid;
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
*/  
    public function checkAwards($pid)
    {
    
    }
}