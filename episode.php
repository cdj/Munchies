<?php require_once('Connections/munchies1.php'); ?>
<?php
$colname_Episode = "-1";
if (isset($_GET['num'])) {
  $colname_Episode = (get_magic_quotes_gpc()) ? $_GET['num'] : addslashes($_GET['num']);
}
mysql_select_db($database_munchies1, $munchies1);
$query_Episode = sprintf("SELECT epnum, sname, `desc` FROM epguide WHERE epnum = %s LIMIT 1", $colname_Episode);
$Episode = mysql_query($query_Episode, $munchies1) or die(mysql_error());
$row_Episode = mysql_fetch_assoc($Episode);
$totalRows_Episode = mysql_num_rows($Episode);

$colname_Recipe = "-1";
if (isset($_GET['num'])) {
  $colname_Recipe = (get_magic_quotes_gpc()) ? $_GET['num'] : addslashes($_GET['num']);
}
mysql_select_db($database_munchies1, $munchies1);
$query_Recipe = sprintf("SELECT * FROM recipes WHERE ep = %s", $colname_Recipe);
$Recipe = mysql_query($query_Recipe, $munchies1) or die(mysql_error());
$row_Recipe = mysql_fetch_assoc($Recipe);
$totalRows_Recipe = mysql_num_rows($Recipe);

$colname_Music = "-1";
if (isset($_GET['num'])) {
  $colname_Music = (get_magic_quotes_gpc()) ? $_GET['num'] : addslashes($_GET['num']);
}
mysql_select_db($database_munchies1, $munchies1);
$query_Music = sprintf("SELECT artist, song, url FROM music WHERE epnum = %s", $colname_Music);
$Music = mysql_query($query_Music, $munchies1) or die(mysql_error());
$row_Music = mysql_fetch_assoc($Music);
$totalRows_Music = mysql_num_rows($Music);

mysql_select_db($database_munchies1, $munchies1);
$query_Air = "SELECT DATE_FORMAT(airtime, \"%c/%e/%Y\") AS airdate FROM airdates WHERE epnum = ".$colname_Episode." ORDER BY airtime ASC";
$Air = mysql_query($query_Air, $munchies1) or die(mysql_error());
$row_Air = mysql_fetch_assoc($Air);
$totalRows_Air = mysql_num_rows($Air);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Show Notes: Episode <?php echo $row_Episode['epnum']; ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.ep {
	background-color: #C6FA29;
	border: 2px solid #009900;
	margin-top: 25px;
	margin-left: 10px;
	padding: 5px;
}
.epinfo {
	background-color: #009900;
	padding: 5px;
	float: left;
	text-align: right;
	clear: left;
	color: #C6FA29;
	overflow: visible;
	position: static;
}
.group {
	margin-top: 15px;
	margin-left: 15px;
}
.ep img {
	float: right;
	border: 2px solid #009900;
	position: relative;
	clear: right;
	top: -7px;
	right: -7px;
}
.ep a {
	color: #006600;
}
.rec {
	margin-right: 15px;
	margin-left: 15px;
	padding-left: 15px;
}
.rec ul {
	list-style-type: none;
	margin-top: 0px;
	margin-bottom: 0px;
	text-indent: -10px;
}
.rec li {
	margin-top: 0px;
	margin-bottom: 0px;
}
h1 {
	font-size: x-large;
	font-weight: normal;
	margin-top: 0px;
	margin-bottom: 10px;
}
h2 {
	font-size: large;
	font-weight: normal;
	margin-top: 10px;
	margin-bottom: 5px;
}
-->
</style>
<!--[if lt IE 7.]>
<script defer type="text/javascript" src="scripts/pngfix.js"></script>
<![endif]-->
<SCRIPT LANGUAGE="JavaScript">
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=1,resizable=1,width=575,height=350');");
}
</SCRIPT>
</head>

<body>
<div align="center">
<div id="stuff">
<div id="main">
<div class="group">
<?php if($totalRows_Episode>0){ ?>
	<div class="epinfo">
	#<?php echo $row_Episode['epnum']; ?><br />
	<?php echo $row_Air['airdate']; ?></div>
	<div class="ep"><h1><?php echo $row_Episode['sname']; ?></h1>
        <p><?php echo nl2br($row_Episode['desc']); ?></p>
        <?php if($totalRows_Air>1){ ?><p>Air Dates: <?php echo $row_Air['airdate']; while($row_Air = mysql_fetch_assoc($Air)){ echo ", ".$row_Air['airdate']; } mysql_free_result($Air); ?></p>
		<?php } if($totalRows_Recipe>0){ ?>
		<strong>Cooked</strong>:
		<?php do { ?><p class="rec">
  			<?php if((!is_null($row_Recipe['picfile']))&&($row_Recipe['picfile']!="")){ ?><img src="media/recipes/<?php echo $row_Recipe['picfile']; ?>" alt="<?php echo $row_Recipe['name']; ?>" /><?php } ?>
  			<h2><?php echo $row_Recipe['name']; ?></h2>
  			Ingredients:
  			<ul>
				<?php
					mysql_select_db($database_munchies1, $munchies1);
					$query_Ingredients = sprintf("SELECT item FROM ingredients WHERE recipe = %s", $row_Recipe['num']);
					$Ingredients = mysql_query($query_Ingredients, $munchies1) or die(mysql_error());
					$row_Ingredients = mysql_fetch_assoc($Ingredients);
					$totalRows_Ingredients = mysql_num_rows($Ingredients); do { ?><li><?php echo $row_Ingredients['item']; ?></li>
			<?php } while ($row_Ingredients = mysql_fetch_assoc($Ingredients));
			mysql_free_result($Ingredients); ?></ul>
  			Directions:
  			<br /><?php echo nl2br($row_Recipe['direc']); ?>
		</p>
		<?php } while ($row_Recipe = mysql_fetch_assoc($Recipe));
		mysql_free_result($Recipe); }
				if($totalRows_Music>0){ ?>
		<p><strong>Music</strong>:
			<ul><?php do { ?>
				<li><?php if((!is_null($row_Music['url']))&&($row_Music['url']!="")){ ?><a href="<?php echo $row_Music['url']; ?>"><?php } echo $row_Music['artist']; ?> - &quot;<?php echo $row_Music['song']; ?>&quot;<?php if((!is_null($row_Music['url']))&&($row_Music['url']!="")){ ?></a><?php } ?></li>
			<?php } while ($row_Music = mysql_fetch_assoc($Music));
			mysql_free_result($Music); ?></ul>
		</p><?php } ?>
    </div>
<?php } ?>
</div>
</div>
<div id="sidebar">
	<a href="/"><img src="media/graphics/minifridge.gif" alt="home" width="100" height="188" border="0" /></a>
</div>
<div id="footer">
<?php readfile('footer.php'); ?>
</div>
</div>
</div>
</body>
</html>
<?php
mysql_free_result($Episode);
?>