
import host
import string
from time import time
from bf2 import g_debug


# Import Config Data
from bf2.BF2StatisticsConfig import http_backend_addr, http_backend_port, pm_backend_pid_manager, pm_local_pid_txt_file
# Use MiniClient
from bf2.stats.miniclient import miniclient, http_get

# ingame scoremanager link
ingameScores = (
	'deaths',
	'kills',
	'TKs',
	'score',
	'skillScore',
	'rplScore',
	'cmdScore',
	'fracScore',
	'rank',
	'firstPlace',
	'secondPlace',
	'thirdPlace',
	'bulletsFired',
	'bulletsGivingDamage',
	'bulletsFiredAndClear',
	'bulletsGivingDamageAndClear'
)

class PlayerScore:
	def __init__(self, index):
		self.__dict__['index'] = index
		self.reset()

	def reset(self):
		# these scores are only tracked in script
		self.__dict__['heals'] = 0
		self.__dict__['ammos'] = 0
		self.__dict__['repairs'] = 0
		self.__dict__['damageAssists'] = 0
		self.__dict__['passengerAssists'] = 0
		self.__dict__['driverAssists'] = 0
		self.__dict__['targetAssists'] = 0
		self.__dict__['driverSpecials'] = 0
		self.__dict__['revives'] = 0
		self.__dict__['teamDamages'] = 0
		self.__dict__['teamVehicleDamages'] = 0
		self.__dict__['cpCaptures'] = 0
		self.__dict__['cpDefends'] = 0
		self.__dict__['cpAssists'] = 0
		self.__dict__['suicides'] = 0
		self.__dict__['cpNeutralizes'] = 0
		self.__dict__['cpNeutralizeAssists'] = 0

	def __getattr__(self, name):
		if name in self.__dict__: return self.__dict__[name]
		elif name == 'dkRatio':
			kills = host.pmgr_getScore(self.index, 'kills')
			if kills == 0:
				# div by zero is undefined -> 0:0 = 1 1:0 = 2 1:1 = 1
				return 1.0 * host.pmgr_getScore(self.index, 'deaths') + 1 
			else:
				return 1.0 * host.pmgr_getScore(self.index, 'deaths') / kills
		elif name in ingameScores: 
			return host.pmgr_getScore(self.index, name)
		else:
			raise AttributeError, name

	def __setattr__(self, name, value):
		if name in self.__dict__: 
			self.__dict__[name] = value 
			return None
		elif name in ingameScores: 
			return host.pmgr_setScore(self.index, name, value)
		else:
			raise AttributeError, name

		

class Player:
	def __init__(self, index):
		print "Player Manager module initialized"
		self.index = index
		self.score = PlayerScore(index)
		self.profileid = 0
		self.GlobalUpdateTime = 0
				

	def isValid(self): return host.pmgr_isIndexValid(self.index)
	def isRemote(self): return host.pmgr_p_get("remote", self.index)
	def isAIPlayer(self): return host.pmgr_p_get("ai", self.index)
	def isAlive(self): return host.pmgr_p_get("alive", self.index)
	def isManDown(self): return host.pmgr_p_get("mandown", self.index)
	def isConnected(self): return host.pmgr_p_get("connected", self.index)


# Added by Chump - for bf2statistics stats
# ------------------------------------------------------------------------------
# omero 2006-03-31
# ------------------------------------------------------------------------------
# using pm_local_pid_txt_file imported from BF2StatisticsConfig module
# for reading Nick/pID mappings.
# ------------------------------------------------------------------------------
# omero, 2006-04-13
# ------------------------------------------------------------------------------
# todo:
#
# try:
# 	<get highest pid from db> via some gethighestpid.aspx
#		<parse response from backend>
#		<assign new pid>
#	exept:
#		<raise failure exception>
#
# the above method will have the advantage of not relying on a text file.
# must provide a pid_base_value if db's player table is empty.
# ------------------------------------------------------------------------------
	def getProfileId(self):
		pid = self.profileid
		if not host.pmgr_p_get("ai", self.index) and pid == 0:	# Don't bother doing this if player is 'bot
			pid = int(host.pmgr_p_get("profileid", self.index))
			self.profileid = pid
		
		if pid == 0:
			if pm_backend_pid_manager == 1:	# Try Backend playerID first - idea by ArmEagle
				if g_debug: print "Retrieving Profile ID (%s) via HTTP/1.1 miniclient" % str(host.pmgr_p_get("name", self.index))
				
				# URL for retrieving player ID via internal miniclient
				player_nick = string.replace(str(host.pmgr_p_get("name", self.index)), ' ', '%20')
				player_isai = str(host.pmgr_p_get("ai", self.index))
				asp_playerid = '/ASP/getplayerid.aspx?nick=' + player_nick + '&ai=' + player_isai
				if g_debug: print "URI: %s" % (asp_playerid)
				
				# Fetch Data
				data = http_get( http_backend_addr, http_backend_port, asp_playerid )
				
				if data and data[0] == 'O':
					if g_debug: print "Received PID data is VALID, length %d" % int(len(data))
					datalines = data.splitlines()
					pidval = datalines[2].split('\t')
					pid = int(pidval[1])
				else:
					print "Received PID data is INVALID, length %d" % int(len(data))
			
			# Use PID.TXT to find PlayerID
			if pid == 0:	# Backend check is disabled or failed
				if g_debug: print "Retrieving Profile ID (%s) via PID file" % str(host.pmgr_p_get("name", self.index))
				try:
					fh = file(pm_local_pid_txt_file, 'rb')
					line = fh.readlines()
					fh.close()
					count = 0
					while count < len(line):
						if line[count].rstrip() == host.pmgr_p_get("name", self.index):
							pid = int(line[count + 1].rstrip())
							break
						count += 2
					# create a new PID - idea by FiberLetalis
					if pid == 0 and pm_backend_pid_manager == 0:
						if g_debug: print "New player, creating Profile ID..."
						new_pid = int(line[count - 1].rstrip()) + 1
						fh = file(pm_local_pid_txt_file, 'ab')
						fh.write('\r\n' + host.pmgr_p_get("name", self.index) + '\r\n' + str(new_pid))
						fh.close()
						pid = new_pid
					elif pid == 0 and pm_backend_pid_manager == 2:
						if g_debug: print "Retrieving Profile ID (%s) via HTTP/1.1 miniclient" % str(host.pmgr_p_get("name", self.index))
				
						# URL for retrieving player ID via internal miniclient
						player_nick = string.replace(str(host.pmgr_p_get("name", self.index)), ' ', '%20')
						asp_playerid = '/ASP/getplayerid.aspx?nick=' + player_nick
						if g_debug: print "URI: %s" % (asp_playerid)
						
						# Fetch Data
						data = http_get( http_backend_addr, http_backend_port, asp_playerid )
						
						if data and data[0] == 'O':
							if g_debug: print "Received PID data is VALID, length %d" % int(len(data))
							datalines = data.splitlines()
							pidval = datalines[2].split('\t')
							pid = int(pidval[1])
						else:
							print "Received PID data is INVALID, length %d" % int(len(data))
							
				except IOError:
					print 'PID file not found!'
			
			self.profileid = pid
			return pid
		else:
			return pid

	def isFlagHolder(self): return host.pmgr_p_get("fholder", self.index)

	def getTeam(self): return host.pmgr_p_get("team", self.index)
	def setTeam(self, t): return host.pmgr_p_set("team", self.index, t)
	def getPing(self): return host.pmgr_p_get("ping", self.index)

	def getSuicide(self): return host.pmgr_p_get("suicide", self.index)
	def setSuicide(self, t): return host.pmgr_p_set("suicide", self.index, t)
	
	def getTimeToSpawn(self): return host.pmgr_p_get("tts", self.index)
	def setTimeToSpawn(self, t): return host.pmgr_p_set("tts", self.index, t)

	def getSquadId(self): return host.pmgr_p_get("sqid", self.index)
	def isSquadLeader(self): return host.pmgr_p_get("isql", self.index)
	def isCommander(self): return host.pmgr_p_get("commander", self.index)

	def getName(self): return host.pmgr_p_get("name", self.index)
	def setName(self, name): return host.pmgr_p_set("name", self.index, name)

	def getSpawnGroup(self): return host.pmgr_p_get("sgr", self.index)
	def setSpawnGroup(self, t): return host.pmgr_p_set("sgr", self.index, t)
	
	def getKit(self): return host.pmgr_p_get("kit", self.index)
	def getVehicle(self): return host.pmgr_p_get("vehicle", self.index)
	def getDefaultVehicle(self): return host.pmgr_p_get("defaultvehicle", self.index)
	def getPrimaryWeapon(self): return host.pmgr_p_get("weapon", self.index, 0)

	def getAddress(self): return host.pmgr_p_get("addr", self.index)
	
	def setIsInsideCP(self, val): return host.pmgr_p_set("isInsideCP", self.index, val)
	def getIsInsideCP(self): return host.pmgr_p_get("isInsideCP", self.index)
	
	# Functions used to stop STATS "update storm"
	def setGlobalUpdateTime(self): 
		self.GlobalUpdateTime = time()
		return self.GlobalUpdateTime
	def getGlobalUpdateTime(self): return (time() - self.GlobalUpdateTime)
	
class PlayerManager:
	def __init__(self):
		print "PlayerManager created"
		self._pcache = {}
		
	def getNumberOfPlayers(self):
		return host.pmgr_getNumberOfPlayers()
	
	def getCommander(self, team):
		return self.getPlayerByIndex(host.pmgr_getCommander(team))

	def getPlayers(self):
		indices = host.pmgr_getPlayers()
		players = []
		# NOTE: this uses getPlayerByIndex so we return cached player objects
		# whenever we can
		for i in indices: players.append(self.getPlayerByIndex(i))
		return players

	def getPlayerByIndex(self, index):
		# dep: this uses a cache so that all references to a certain player
		# index will always yield the same object, which is useful because you
		# can then test them for object equality
		valid = host.pmgr_isIndexValid(index)
		if not valid: 
			if self._pcache.has_key(index):
				print "Removed player index %d from player cache" % index
				del self._pcache[index]
			return None
		if not self._pcache.has_key(index):
			self._pcache[index] = Player(index)
		return self._pcache[index]
		
	def getNextPlayer(self, index):
		startIndex = index
		p = None
		index = index + 1
		while p == None and index != startIndex:
			p = self.getPlayerByIndex(index)
			index = index + 1
			if index > 255: index = 0
			if index > 63: index = 255
		
		if not p:
			return self.getPlayerByIndex(startIndex)
		else:
			return p

	def getNumberOfPlayersInTeam(self, team):
		players = self.getPlayers()
		inTeam = 0
		for p in players:
			if p.getTeam() == team:
				inTeam += 1
		
		return inTeam
		
	def getNumberOfAlivePlayersInTeam(self, team):
		players = self.getPlayers()
		inTeam = 0
		for p in players:
			if p.getTeam() == team and p.isAlive():
				inTeam += 1
		
		return inTeam
		
	# allows temporary disabling of the onPlayerScore event.
	def enableScoreEvents(self):
		return host.pmgr_enableScoreEvents(1)
	def disableScoreEvents(self):
		return host.pmgr_enableScoreEvents(0)