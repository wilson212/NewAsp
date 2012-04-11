<?php
class Player
{
	// Current Player ID
	protected $pid = 0;
	protected $data = array();

	
	public function setPlayerId($pid)
	{
		$this->pid = $pid;
		$this->data = array();
		return $this;
	}
	
	public function getKitData()
	{
		// Make sure we have a PID
		if($this->pid == 0) return FALSE;
	}
	
	public function getWeaponData()
	{
		// Make sure we have a PID
		if($this->pid == 0) return FALSE;
	}
	
	public function getVehicleData()
	{
		// Make sure we have a PID
		if($this->pid == 0) return FALSE;
	}
	
	public function getArmyData()
	{
		// Make sure we have a PID
		if($this->pid == 0) return FALSE;
	}
	
	public function getFavoriteOpponent()
	{
		// Make sure we have a PID
		if($this->pid == 0) return FALSE;
	}
	
	public function getFavoriteVictim()
	{
		// Make sure we have a PID
		if($this->pid == 0) return FALSE;
	}
	
	public function getFavoriteKit()
	{
		// Make sure we have a PID
		if($this->pid == 0) return FALSE;
	}
	
	public function getFavoriteWeapon()
	{
		// Make sure we have a PID
		if($this->pid == 0) return FALSE;
	}
	
	public function getFavoriteVehicle()
	{
		// Make sure we have a PID
		if($this->pid == 0) return FALSE;
	}
	
	public function getFavoriteArmy()
	{
		// Make sure we have a PID
		if($this->pid == 0) return FALSE;
	}
	
	public function getFavoriteOpponent()
	{
		// Make sure we have a PID
		if($this->pid == 0) return FALSE;
	}
	
	public function getFavoriteVictim()
	{
		// Make sure we have a PID
		if($this->pid == 0) return FALSE;
	}
}