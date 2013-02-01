<?php require_once('Connections/munchies1.php'); ?>
<?php
mysql_select_db($database_munchies1, $munchies1);
$query_air = "SELECT airdates.epnum, airdates.airtime, epguide.sname FROM airdates INNER JOIN epguide ON airdates.epnum=epguide.epnum WHERE (airdates.airtime >= NOW()) ORDER BY airdates.airtime ASC LIMIT 1";
$air = mysql_query($query_air, $munchies1) or die(mysql_error());
$row_air = mysql_fetch_assoc($air);
$totalRows_air = mysql_num_rows($air);

if($totalRows_air <= 0)
{
$query_air2 = "SELECT airtime FROM airdates WHERE (airtime < NOW()) ORDER BY airtime DESC LIMIT 1";
$air2 = mysql_query($query_air2, $munchies1) or die(mysql_error());
$row_air2 = mysql_fetch_assoc($air2);
$totalRows_air2 = mysql_num_rows($air2);
$nextAir = date("n/j/Y",strtotime($row_air2['airtime']." +2 weeks"));
}else{
$nextAir = date("n/j/Y",strtotime($row_air['airtime']));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>I've Got Munchies - Next Episode <?php echo $nextAir; ?></title>
<link href="Styles/front.css" rel="stylesheet" type="text/css" />
<!--[if lt IE 7.]>
<script defer type="text/javascript" src="scripts/pngfix.js"></script>
<![endif]-->
<script language="JavaScript" type="text/javascript">
<!--
function imgSwap(oImg)
{
   var strOver  = "_on"    // image to be used with mouse over
   var strOff = "_off"     // normal image
   var strImg = oImg.src
   if (strImg.indexOf(strOver) != -1) {
      oImg.src = strImg.replace(strOver,strOff)
   }
   else {
      oImg.src = strImg.replace(strOff,strOver)
   }
}

function descon(obj)
{
   var obj = document.getElementById(obj);
   obj.style.visibility = 'visible';
   obj.style.display = 'block';
}
function descoff(obj)
{
   var obj = document.getElementById(obj);
   obj.style.visibility = 'hidden';
   obj.style.display = 'none';
}
//-->
</script>
<link href="Styles/general.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.desc {
	visibility: hidden;
	display: none;
}
-->
</style>
</head>

<body>
<div align="center">
<div id="content">
	<div id="logo">
	  <a href="http://www.youtube.com/profile?user=IveGotMunchies"><img src="media/icons/cookie_off.gif" alt="YouTube Videos" name="cookie" width="82" height="55" id="cookie" onmouseover="imgSwap(this);descon('dyoutube')" onmouseout="imgSwap(this);descoff('dyoutube')" /></a>
	  <a href="epguide.php"><img src="media/icons/epguide_off.gif" alt="Episode Guide" name="jar" width="33" height="52" id="jar" onmouseover="imgSwap(this);descon('depguide')" onmouseout="imgSwap(this);descoff('depguide')" /></a>
	  <a href="http://www.myspace.com/ivegotmunchies"><img src="media/icons/chinese_off.gif" alt="MySpace" name="chinese" width="56" height="70" id="chinese" onmouseover="imgSwap(this);descon('dmyspace')" onmouseout="imgSwap(this);descoff('dmyspace')" /></a>
	  <a href="http://www.cafepress.com/ivegotmunchies"><img src="media/icons/gear_off.gif" alt="Gear" name="shirt" width="54" height="120" id="shirt" onmouseover="imgSwap(this);descon('dgear')" onmouseout="imgSwap(this);descoff('dgear')" /></a>
	  <a href="random.php"><img src="media/icons/random_off.gif" alt="Random" name="butter" width="43" height="37" id="butter" onmouseover="imgSwap(this);descon('drandom')" onmouseout="imgSwap(this);descoff('drandom')" /></a>
	  <a href="facts.php"><img src="media/icons/facts_off.gif" alt="Facts" name="cheese" width="59" height="57" id="cheese" onmouseover="imgSwap(this);descon('dfacts')" onmouseout="imgSwap(this);descoff('dfacts')" /></a>
	  <a href="players.php"><img id="beer" src="media/icons/bios_off.gif" alt="Stuffed Comedy Players" name="beer" width="55" height="69" onmouseover="imgSwap(this);descon('dplayers')" onmouseout="imgSwap(this);descoff('dplayers')" /></a>
	  <a href="recipes.php"><img src="media/icons/recipes_off.gif" alt="Recipes" name="book" width="99" height="60" id="book" onmouseover="imgSwap(this);descon('drecipes')" onmouseout="imgSwap(this);descoff('drecipes')" /></a>
	  <img src="media/logo.gif" width="340" height="471" alt="the fridge" />	</div>
	<div id="right">
	<div id="note">
	  <p>Next Episode...</p>
	  <?php if($totalRows_air > 0){ ?><p><a href="episode.php?num=<?php echo $row_air['epnum']; ?>"><?php echo $row_air['sname']; ?></a></p><?php } ?>
	  <p>11pm <?php echo $nextAir; ?> on <a href="http://www.mnn.org/" target="_blank">MNN</a> ch.67</p><div>see the <a href="http://www.mnn.org/stream/ch67.asx">stream</a> or <a href="facts.php">podcast</a></div>
	</div>
	<img src="media/icons/note.gif" id="noteimg" alt="Next Episode" width="173" height="150" />
	<div id="dyoutube" class="desc">
		<table border="0"><tr valign="middle"><td><img src="media/icons/cookie.gif" alt="YouTube" width="82" height="55" /></td>
		<td><h2>YouTube</h2></td></tr></table>
		Watch our sketches at YouTube
	</div>
	<div id="dmyspace" class="desc">
		<table border="0"><tr valign="middle"><td><img src="media/icons/chinese.gif" alt="MySpace" width="56" height="70" /></td>
		<td><h2>MySpace</h2></td></tr></table>
		Check out our MySpace profile and become our friend
	</div>
	<div id="dgear" class="desc">
		<table border="0"><tr valign="middle"><td><img src="media/icons/gear.gif" alt="Munchies Gear" width="54" height="120" /></td>
		<td><h2>Munchies Gear</h2></td></tr></table>
		Get your "I've Got Munchies" apparel and accessories today
	</div>
	<div id="depguide" class="desc">
		<table border="0"><tr valign="middle"><td><img src="media/icons/epguide.gif" alt="Episode Guide" width="33" height="52" /></td>
		<td><h2>Episode Guide</h2></td></tr></table>
		What happened? When?
	</div>
	<div id="drandom" class="desc">
		<table border="0"><tr valign="middle"><td><img src="media/icons/random.gif" alt="Random" width="43" height="37" /></td>
		<td><h2>Random</h2></td></tr></table>
		Everything else worth looking at!
	</div>
	<div id="dfacts" class="desc">
		<table border="0"><tr valign="middle"><td><img src="media/icons/facts.gif" alt="Facts" width="59" height="57" /></td>
		<td><h2>Facts</h2></td></tr></table>
		Be in the Know! That's how is goes!
	</div>
	<div id="dplayers" class="desc">
		<table border="0"><tr valign="middle"><td><img src="media/icons/bios.gif" alt="Stuffed Comedy Players" width="55" height="69" /></td>
		<td><h2>&quot;Stuffed&quot; Comedy Players</h2></td></tr></table>
		People in da show!
	</div>
	<div id="drecipes" class="desc">
		<table border="0"><tr valign="middle"><td><img src="media/icons/recipes.gif" alt="Recipes" width="99" height="60" /></td>
		<td><h2>Recipes</h2></td></tr></table>
		how ta make da good stuff!
	</div>
	</div>
</div>
<?php readfile('footer.php'); ?>
</div>
</body>
</html>
<?php
mysql_free_result($air);
if($totalRows_air <= 0)
{
mysql_free_result($air2);
}
?>