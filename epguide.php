<?php require_once('Connections/munchies1.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_Episodes = 10;
$pageNum_Episodes = 0;
if (isset($_GET['pageNum_Episodes'])) {
  $pageNum_Episodes = $_GET['pageNum_Episodes'];
}
$startRow_Episodes = $pageNum_Episodes * $maxRows_Episodes;

mysql_select_db($database_munchies1, $munchies1);
$query_Episodes = "SELECT epguide.epnum, epguide.sname, epguide.desc, DATE_FORMAT(airdates.airtime, \"%c/%e/%Y\") AS airdate FROM epguide INNER JOIN airdates USING(epnum) GROUP BY epnum ORDER BY epnum DESC";
$query_limit_Episodes = sprintf("%s LIMIT %d, %d", $query_Episodes, $startRow_Episodes, $maxRows_Episodes);
$Episodes = mysql_query($query_limit_Episodes, $munchies1) or die(mysql_error());
$row_Episodes = mysql_fetch_assoc($Episodes);

if (isset($_GET['totalRows_Episodes'])) {
  $totalRows_Episodes = $_GET['totalRows_Episodes'];
} else {
  $all_Episodes = mysql_query($query_Episodes);
  $totalRows_Episodes = mysql_num_rows($all_Episodes);
}
$totalPages_Episodes = ceil($totalRows_Episodes/$maxRows_Episodes)-1;

$queryString_Episodes = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Episodes") == false && 
        stristr($param, "totalRows_Episodes") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Episodes = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Episodes = sprintf("&totalRows_Episodes=%d%s", $totalRows_Episodes, $queryString_Episodes);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Episode Guide</title>
<link href="Styles/general.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.ham {
	background-image: url(media/graphics/ham-pick.gif);
	height: 100px;
	width: 100px;
	margin-top: 10px;
	line-height: 100px;
	vertical-align: middle;
	background-repeat: no-repeat;
	overflow: hidden;
	color: #006600;
}
.hamside {
	background-image: url(media/graphics/ham-pick-side.gif);
	height: 29px;
	width: 100px;
	line-height: 29px;
	vertical-align: middle;
	background-repeat: no-repeat;
	overflow: hidden;
	position: static;
	margin-right: 10px;
	margin-left: 10px;
	float: left;
	color: #003300;
}
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
#main2 {
	background-image: url(media/graphics/jar.gif);
	background-repeat: no-repeat;
	background-position: right top;
	background-attachment: fixed;
	width: 660px;
}
.ep a {
	color: #006600;
}
.ham a {
	color: #006600;
}
.hamside a {
	color: #003300;
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
</script>
</head>

<body>
<div align="center">
<div id="stuff">
<div id="main">
<div align="center"><img src="media/graphics/epguide.gif" alt="Episode Guide" width="500" height="110" /></div>
<div id="main2">
<?php if ($totalRows_Episodes > 0) { // Show if recordset not empty ?>
  <?php do {
mysql_select_db($database_munchies1, $munchies1);
$query_Cooked = "SELECT num, name, picfile FROM recipes WHERE ep = ".$row_Episodes['epnum'];
$Cooked = mysql_query($query_Cooked, $munchies1) or die(mysql_error());
$row_Cooked = mysql_fetch_assoc($Cooked);
$totalRows_Cooked = mysql_num_rows($Cooked);
?>
    <div class="group">
	<div class="epinfo">
	#<?php echo $row_Episodes['epnum']; ?><br />
	<?php echo $row_Episodes['airdate']; ?></div>
	<div class="ep">
      <?php if(($row_Cooked['picfile'])&&($row_Cooked['picfile']!="")){ ?>
      <img src="media/recipes/<?php echo $row_Cooked['picfile']; ?>" /><?php } ?><h2><a href="episode.php?num=<?php echo $row_Episodes['epnum']; ?>"><?php echo $row_Episodes['sname']; ?></a></h2>
        <p><?php echo nl2br($row_Episodes['desc']); ?></p>
      <?php if ($totalRows_Cooked > 0) { // Show if recordset not empty ?>
        Cooked:
        <?php $x=0; do { if($x>0){ echo ", "; } echo "<a href=\"javascript:popUp('recipe-card.php?num=".$row_Cooked['num']."')\">".$row_Cooked['name']."</a>"; $x++; } while ($row_Cooked = mysql_fetch_assoc($Cooked)); ?>
        <?php } // Show if recordset not empty ?>
    </div>
	</div>
    <?php mysql_free_result($Cooked); } while ($row_Episodes = mysql_fetch_assoc($Episodes)); ?>
  <?php } else { echo "No Episodes!"; } ?>
</div>
<div align="center" style="height:29px; margin-top:15px; overflow:hidden;">
<div style="width:490px">
<?php if ($pageNum_Episodes > 0) { // Show if not first page ?>
<a href="<?php printf("%s?pageNum_Episodes=%d%s", $currentPage, 0, $queryString_Episodes); ?>"><div class="hamside">Newest</div></a>
<?php } else { ?><div class="hamside"></div><?php } ?>
<?php if ($pageNum_Episodes > 0) { // Show if not first page ?>
<a href="<?php printf("%s?pageNum_Episodes=%d%s", $currentPage, max(0, $pageNum_Episodes - 1), $queryString_Episodes); ?>"><div class="hamside">Newer</div></a>
<?php } else { ?><div class="hamside"></div><?php } ?>
<?php if ($pageNum_Episodes < $totalPages_Episodes) { // Show if not last page ?>
<a href="<?php printf("%s?pageNum_Episodes=%d%s", $currentPage, min($totalPages_Episodes, $pageNum_Episodes + 1), $queryString_Episodes); ?>"><div class="hamside">Older</div></a>
<?php } else { ?><div class="hamside"></div><?php } ?>
<?php if ($pageNum_Episodes < $totalPages_Episodes) { // Show if not last page ?>
<a href="<?php printf("%s?pageNum_Episodes=%d%s", $currentPage, $totalPages_Episodes, $queryString_Episodes); ?>"><div class="hamside">Oldest</div></a>
<?php } else { ?><div class="hamside"></div><?php } ?>
</div>
</div>
</div>
<div id="sidebar">
<a href="/"><img src="media/graphics/minifridge.gif" alt="home" width="100" height="188" border="0" /></a>
<?php if ($pageNum_Episodes > 0) { // Show if not first page ?>
<a href="<?php printf("%s?pageNum_Episodes=%d%s", $currentPage, 0, $queryString_Episodes); ?>"><div class="ham">Newest</div></a>
<?php } else { ?><div class="ham"></div><?php } ?>
<?php if ($pageNum_Episodes > 0) { // Show if not first page ?>
<a href="<?php printf("%s?pageNum_Episodes=%d%s", $currentPage, max(0, $pageNum_Episodes - 1), $queryString_Episodes); ?>"><div class="ham">Newer</div></a>
<?php } else { ?><div class="ham"></div><?php } ?>
<?php if ($pageNum_Episodes < $totalPages_Episodes) { // Show if not last page ?>
<a href="<?php printf("%s?pageNum_Episodes=%d%s", $currentPage, min($totalPages_Episodes, $pageNum_Episodes + 1), $queryString_Episodes); ?>"><div class="ham">Older</div></a>
<?php } else { ?><div class="ham"></div><?php } ?>
<?php if ($pageNum_Episodes < $totalPages_Episodes) { // Show if not last page ?>
<a href="<?php printf("%s?pageNum_Episodes=%d%s", $currentPage, $totalPages_Episodes, $queryString_Episodes); ?>"><div class="ham">Oldest</div></a>
<?php } else { ?><div class="ham"></div><?php } ?>
</div>

<?php readfile('footer.php'); ?>
</div>
</div>
</body>
</html>
<?php
mysql_free_result($Episodes);
?>