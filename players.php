<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>&quot;Stuffed&quot; Comedy Players</title>
<link href="Styles/general.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.player{
	margin:0px;
	padding:12px;
	list-style-type:none;
	cursor:pointer;
	background-image: url(media/graphics/bcap.gif);
	height: 76px;
	width: 76px;
	margin-top: 5px;
	line-height: 76px;
	vertical-align: middle;
	background-repeat: no-repeat;
	overflow: hidden;
	color: #000000;
	text-decoration: none;
}
.player a{
	color: #000000;
	text-decoration: none;
}
#contentContainer {
	margin: 5px;
	padding: 5px;
}
.bottle {
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
	float: right;
}
-->
</style>

</head>

<body>
<div align="center">
<div id="stuff">
<div id="main">
<div align="center"><img src="media/graphics/players.gif" alt="&quot;Stuffed&quot; Comedy Players" width="500" height="193" /></div>
    <div id="contentContainer">
<?php 
  switch ($_GET['player']) {
    case "sharon":
      readfile('players/sharon.php');
      break;
    case "percy":
      readfile('players/percy.php');
      break;
    case "jenndodd":
      readfile('players/jenndodd.php');
      break;
    case "ian":
      readfile('players/ian.php');
      break;
    case "jennyledel":
      readfile('players/jennyledel.php');
      break;
    case "guests":
      readfile('players/guests.php');
      break;
	default:
      readfile('players/main.php');
  }
 ?>
    </div>
</div>
<div id="sidebar">
	<a href="/"><img src="media/graphics/minifridge.gif" alt="home" width="100" height="188" border="0" /></a>
    <a href="players.php?player=sharon"><div class="player">Sharon Jamilkowski</div></a>
    <a href="players.php?player=percy"><div class="player">Percy Lambert</div></a>
    <a href="players.php?player=ian"><div class="player">Ian Fishman</div></a>
    <a href="players.php?player=jenndodd"><div class="player">Jenn Dodd</div></a>
    <a href="players.php?player=jennyledel"><div class="player">Jenny Ledel</div></a>
    <a href="players.php?player=guests"><div class="player">Guests</div></a>
</div>
<?php readfile('footer.php'); ?>
</div>
</div>
</body>
</html>