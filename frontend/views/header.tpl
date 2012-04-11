<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<!-- Apple iOS and Android stuff (do not remove) -->
	<meta name="apple-mobile-web-app-capable" content="no" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />

	<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no,maximum-scale=1" />

	<!-- Required Stylesheets -->
	<link rel="stylesheet" type="text/css" href="frontend/css/reset.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="frontend/css/text.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="frontend/css/fonts/ptsans/stylesheet.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="frontend/css/fluid.css" media="screen" />

	<link rel="stylesheet" type="text/css" href="frontend/css/mws.style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="frontend/css/icons/16x16.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="frontend/css/icons/24x24.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="frontend/css/icons/32x32.css" media="screen" />

	<!-- Demo and Plugin Stylesheets -->
	<link rel="stylesheet" type="text/css" href="frontend/css/demo.css" media="screen" />

	<link rel="stylesheet" type="text/css" href="frontend/plugins/colorpicker/colorpicker.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="frontend/plugins/chosen/chosen.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="frontend/plugins/tipsy/tipsy.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="frontend/plugins/jgrowl/jquery.jgrowl.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="frontend/plugins/fileinput/css/fileinput.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="frontend/plugins/spinner/ui.spinner.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="frontend/css/jui/jquery.ui.css" media="screen" />

	<!-- Theme Stylesheet -->
	<link rel="stylesheet" type="text/css" href="frontend/css/mws.theme.css" media="screen" />

	<!-- JavaScript Plugins -->
	<script type="text/javascript" src="frontend/js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="frontend/js/jquery.mousewheel-min.js"></script>
	<script type="text/javascript" src="frontend/js/jquery.form.js"></script>

	<!-- jQuery-UI Dependent Scripts -->
	<script type="text/javascript" src="frontend/js/jquery-ui.js"></script>
	<script type="text/javascript" src="frontend/plugins/spinner/ui.spinner.min.js"></script>
	<script type="text/javascript" src="frontend/js/jquery.ui.touch-punch.min.js"></script>

	<!-- Plugin Scripts -->
	<script type="text/javascript" src="frontend/plugins/duallistbox/jquery.dualListBox-1.3.min.js"></script>
	<script type="text/javascript" src="frontend/plugins/jgrowl/jquery.jgrowl-min.js"></script>
	<script type="text/javascript" src="frontend/plugins/fileinput/js/jQuery.fileinput.js"></script>
	<script type="text/javascript" src="frontend/plugins/datatables/jquery.dataTables-min.js"></script>
	<script type="text/javascript" src="frontend/plugins/chosen/chosen.jquery.min.js"></script>

	<!--[if lt IE 9]>
	<script type="text/javascript" src="frontend/plugins/flot/excanvas.min.js"></script>
	<![endif]-->
	<script type="text/javascript" src="frontend/plugins/flot/jquery.flot.min.js"></script>
	<script type="text/javascript" src="frontend/plugins/flot/jquery.flot.pie.min.js"></script>
	<script type="text/javascript" src="frontend/plugins/flot/jquery.flot.stack.min.js"></script>
	<script type="text/javascript" src="frontend/plugins/flot/jquery.flot.resize.min.js"></script>
	<script type="text/javascript" src="frontend/plugins/colorpicker/colorpicker-min.js"></script>
	<script type="text/javascript" src="frontend/plugins/tipsy/jquery.tipsy-min.js"></script>
	<script type="text/javascript" src="frontend/plugins/placeholder/jquery.placeholder-min.js"></script>
	<script type="text/javascript" src="frontend/plugins/validate/jquery.validate-min.js"></script>

	<!-- Core Script -->
	<script type="text/javascript" src="frontend/js/mws.js"></script>

	<!-- Themer Script (Remove if not needed) -->
	<script type="text/javascript" src="frontend/js/themer.js"></script>
	
	{PAGE_JS_FILE}

	<title>Private Stats Admin</title>

</head>

<body>

	<!-- Themer (Remove if not needed) -->  
	<div id="mws-themer">
        <div id="mws-themer-content">
        	<div id="mws-themer-ribbon"></div>
            <div id="mws-themer-toggle"></div>
        	<div id="mws-theme-presets-container" class="mws-themer-section">
	        	<label for="mws-theme-presets">Color Presets</label>
            </div>
            <div class="mws-themer-separator"></div>
        	<div id="mws-theme-pattern-container" class="mws-themer-section">
	        	<label for="mws-theme-patterns">Background</label>
            </div>
            <div class="mws-themer-separator"></div>
            <div class="mws-themer-section">
                <ul>
                    <li class="clearfix"><span>Base Color</span> <div id="mws-base-cp" class="mws-cp-trigger"></div></li>
                    <li class="clearfix"><span>Highlight Color</span> <div id="mws-highlight-cp" class="mws-cp-trigger"></div></li>
                    <li class="clearfix"><span>Text Color</span> <div id="mws-text-cp" class="mws-cp-trigger"></div></li>
                    <li class="clearfix"><span>Text Glow Color</span> <div id="mws-textglow-cp" class="mws-cp-trigger"></div></li>
                    <li class="clearfix"><span>Text Glow Opacity</span> <div id="mws-textglow-op"></div></li>
                </ul>
            </div>
            <div class="mws-themer-separator"></div>
            <div class="mws-themer-section">
	            <button class="mws-button red small" id="mws-themer-getcss">Get CSS</button>
            </div>
        </div>
        <div id="mws-themer-css-dialog">
        	<form class="mws-form">
            	<div class="mws-form-row">
		        	<div class="mws-form-item">
                    	<textarea cols="auto" rows="auto" readonly="readonly"></textarea>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Themer End -->

	<!-- Header -->
	<div id="mws-header" class="clearfix">
    
    	<!-- Logo Container -->
    	<div id="mws-logo-container">
        
        	<!-- Logo Wrapper, images put within this wrapper will always be vertically centered -->
        	<div id="mws-logo-wrap">
            	<img id="logo" src="frontend/images/bf2logo.png" alt="BF2 Private Stats Admin" />
			</div>
        </div>
		
		<div id="title">Private Stats Admin</div>
		<div id="dbver">Code Version: <?php echo CODE_VER; ?> || Database Version: 1.4.7 </div>
        
    </div>
    
    <!-- Start Main Wrapper -->
    <div id="mws-wrapper">
    
    	<!-- Necessary markup, do not remove -->
		<div id="mws-sidebar-stitch"></div>
		<div id="mws-sidebar-bg"></div>
        
        <!-- Sidebar Wrapper -->
        <div id="mws-sidebar">
            
            <!-- Main Navigation -->
            <div id="mws-navigation">
            	<ul>
                	<li class="active"><a href="?task=home" class="mws-i-24 i-home">Dashboard</a></li>
					<li>
                    	<a href="#" class="mws-i-24 i-list">Configuration</a>
                        <ul class="closed">
                        	<li><a href="?task=editconfig">Edit Config</a></li>
                        	<li><a href="?task=testconfig">Test Config</a></li>
                        </ul>
                    </li>
					<li>
                    	<a href="#" class="mws-i-24 i-list">Database Ops.</a>
                        <ul class="closed">
                        	<li><a href="?task=installdb">Install DB</a></li>
                        	<li><a href="?task=upgradedb">Upgrade DB</a></li>
							<li><a href="?task=cleardb">Clear DB</a></li>
							<li><a href="?task=backupdb">Backup DB</a></li>
							<li><a href="?task=restoredb">Restore DB</a></li>
                        </ul>
                    </li>
					<li>
                    	<a href="#" class="mws-i-24 i-list">Manage Players</a>
                        <ul class="closed">
                        	<li><a href="?task=editplayers">Edit Players</a></li>
                        	<li><a href="?task=banplayers">Ban Players</a></li>
							<li><a href="?task=unbanplayers">UnBan Players</a></li>
							<li><a href="?task=resetunlocks">Reset Player Unlocks</a></li>
							<li><a href="?task=mergeplayers">Merge Players</a></li>
							<li><a href="?task=deleteplayers">Delete Players</a></li>
                        </ul>
                    </li>
                	<li>
                    	<a href="#" class="mws-i-24 i-list">Server Admin</a>
                        <ul class="closed">
                        	<li><a href="?task=serverinfo">Server Info</a></li>
                        	<li><a href="?task=mapinfo">Map Info</a></li>
							<li><a href="?task=validateranks">Validate Ranks</a></li>
							<li><a href="?task=checkawards">Check Awards</a></li>
							<li><a href="?task=importlogs">Import Logs</a></li>
                        </ul>
                    </li>
                	<li><a href="index.php?action=logout" class="mws-i-24 i-cog">Logout</a></li>
                </ul>
            </div>            
        </div>
        
        <!-- Main Container Start -->
        <div id="mws-container" class="clearfix">
			
			<!-- Inner Container Start -->
            <div class="container">