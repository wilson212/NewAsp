<?php
class Statsparser
{
	public function parsePlayerInfo($data) 
	{
		$playerdata = array();	
		if(count($data) > 0) 
		{
			// put header and data in arrays
			$i=0;
			foreach($data as $line) 
			{
				if($i==3) {
					$H = explode(chr(9), trim($line));
				}
				if($i==4) {
					$D = explode(chr(9), trim($line));
				}
				$i++;
			}
			
			// merge header and data
			if(count($H)>0) 
			{
				$i=0;
				foreach($H as $part) 
				{
					if($part!="H")
						$playerdata[$part] = $D[$i];
					$i++;
				}
			}
		}
		return $playerdata;
	}

	// returns simple array of unlocks, e.g (11, 22, 55, 222)
	public function parseUnlocks($data) 
	{
		if(count($data)>0) 
		{
			$i=0; $count=0;
			foreach($data as $line) 
			{
				if($i>5 && stristr($line,"s")) 
				{
					$parts = explode(chr(9), str_replace("\n","",$line));
					$unlocks[$count] = $parts[1];
					$count++;
				}
				$i++;
			}
		}
		return $unlocks;
	} 

	public function parseAwards($data) 
	{
		// setup medal array
		$awards = array();
		$i=0;
		$first = true;
		foreach($data as $line) 
		{
			if(substr($line,0,1)=="D") 
			{
				if(!$first) 
				{
					$parts = explode(chr(9),$line);
					
					$awards[$i]["id"] = $parts[1];
					$awards[$i]["level"] = $parts[2];		
					$awards[$i]["when"] = $parts[3];		
					$awards[$i]["first"] = $parts[4];				
					
					$i++;
				} 
				else 
				{
					$first = false;
				}
			}
		}
		return $awards; 
	}
}