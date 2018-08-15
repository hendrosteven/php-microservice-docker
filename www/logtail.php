<?php
// just a simple basic auth

$valid_passwords = array ("popbox" => "popbox");
$valid_users = array_keys($valid_passwords);

$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];

$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);

if (!$validated) {
  header('WWW-Authenticate: Basic realm="My Realm"');
  header('HTTP/1.0 401 Unauthorized');
  die ("Not authorized");
}

/*
 * 
 * Just fill in the varibles bellow, open in a web browser and tail away
 */
$logFile = "logs/". date("Y-m-d") . ".log"; // local path to log file
$interval = 1000; //how often it checks the log file for changes, min 100
$textColor = ""; //use CSS color
echo $logFile;


// Don't have to change anything bellow
if(!$textColor) $textColor = "white";
if($interval < 100)  $interval = 100; 
if(isset($_GET['getLog'])){
	echo file_get_contents($logFile);
}else{
?>
<html>
	<title>Log</title>
	<style>
		@import url(http://fonts.googleapis.com/css?family=Ubuntu);
		body{
			background-color: black;
			color: <?php echo $textColor; ?>;
			font-family: 'Ubuntu', sans-serif;
			font-size: 16px;
			line-height: 20px;	
		}
		h4{
			font-size: 18px;
			line-height: 22px;
			color: #353535;
		}
		#log {
			position: relative;
			top: -34px;
		}
		#scrollLock{
			width:2px;
			height: 2px;
			overflow:visible;
		}
	</style>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js" type="text/javascript"></script>
	<script>
		setInterval(readLogFile, <?php echo $interval; ?>);
		window.onload = readLogFile; 
		var pathname = window.location.pathname;
		var scrollLock = true;
		
		$(document).ready(function(){
			$('.disableScrollLock').click(function(){
				$("html,body").clearQueue()
				$(".disableScrollLock").hide();
				$(".enableScrollLock").show();
				scrollLock = false;
			});
			$('.enableScrollLock').click(function(){
				$("html,body").clearQueue()
				$(".enableScrollLock").hide();
				$(".disableScrollLock").show();
				scrollLock = true;
			});
		});
		function readLogFile(){
			$.get(pathname, { getLog : "true" }, function(data) {
				data = data.replace(new RegExp("\n", "g"), "<br />");
		        $("#log").html(data);
		        
		        if(scrollLock == true) { $('html,body').animate({scrollTop: $("#scrollLock").offset().top}, <?php echo $interval; ?>) };
		    });
		}
	</script>
	<body>
		<h4><?php echo $logFile; ?></h4>
		<div id="log">
			
		</div>
		<div id="scrollLock"> <input class="disableScrollLock" type="button" value="Disable Scroll Lock" /> <input class="enableScrollLock" style="display: none;" type="button" value="Enable Scroll Lock" /></div>
	</body>
</html>
<?php  } ?>