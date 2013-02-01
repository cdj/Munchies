<?php require_once('Connections/munchies1.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_Recipes = 20;
$pageNum_Recipes = 0;
if (isset($_GET['pageNum_Recipes'])) {
  $pageNum_Recipes = $_GET['pageNum_Recipes'];
}
$startRow_Recipes = $pageNum_Recipes * $maxRows_Recipes;

$sort="ep DESC";
if($_GET["sort"]=="name") $sort="name ASC";

mysql_select_db($database_munchies1, $munchies1);
$query_Recipes = "SELECT num, name, ep FROM recipes ORDER BY ".$sort;
$query_limit_Recipes = sprintf("%s LIMIT %d, %d", $query_Recipes, $startRow_Recipes, $maxRows_Recipes);
$Recipes = mysql_query($query_limit_Recipes, $munchies1) or die(mysql_error());
$row_Recipes = mysql_fetch_assoc($Recipes);

if (isset($_GET['totalRows_Recipes'])) {
  $totalRows_Recipes = $_GET['totalRows_Recipes'];
} else {
  $all_Recipes = mysql_query($query_Recipes);
  $totalRows_Recipes = mysql_num_rows($all_Recipes);
}
$totalPages_Recipes = ceil($totalRows_Recipes/$maxRows_Recipes)-1;

$queryString_Recipes = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recipes") == false && 
        stristr($param, "totalRows_Recipes") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recipes = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recipes = sprintf("&totalRows_Recipes=%d%s", $totalRows_Recipes, $queryString_Recipes);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Recipes</title>
<link href="Styles/general.css" rel="stylesheet" type="text/css" />
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
<style type="text/css">
<!--
.rlist {
	margin: 20px;
	border: 4px solid #000000;
	background-color: #FFFFB7;
	color: #8C0000;
	width: 600px;
}
.rlisth {
	color: #FFFFB7;
	background-color: #8C0000;
}
.rlist a {
	color: #8C0000;
}
.rlist .rlisth a {
	color: #FFFFB7;
}
.rlist td {
	border: 1px dotted #8C0000;
}
-->
</style>
</head>

<body>

<div align="center">
<div id="stuff">
<div id="main">
<div align="center"><img src="media/graphics/book-flat.gif" alt="Episode Guide" width="500" height="121" /></div>
<table align="center" class="rlist">
  <tr class="rlisth">
    <td><?php if($_GET["sort"]!="name"){ ?><a href="recipes.php?sort=name">Recipe</a><?php } else { echo "Recipe"; } ?></td>
    <td width="65">
      <div align="center">
        <?php if(isset($_GET["sort"])){ ?>
        <a href="recipes.php">Episode</a>
        <?php } else { echo "Episode"; } ?>
        </div></td>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="javascript:popUp('recipe-card.php?num=<?php echo $row_Recipes['num']; ?>')"><?php echo $row_Recipes['name']; ?></a></td>
      <td width="65"><div align="right"><?php echo $row_Recipes['ep']; ?></div></td>
    </tr>
    <?php } while ($row_Recipes = mysql_fetch_assoc($Recipes)); ?>
</table>
<div align="center" style="height:29px; margin-top:15px; overflow:hidden;">
<div style="width:490px">
<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" align="center"><?php if ($pageNum_Recipes > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_Recipes=%d%s", $currentPage, 0, $queryString_Recipes); ?>">First</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="31%" align="center"><?php if ($pageNum_Recipes > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_Recipes=%d%s", $currentPage, max(0, $pageNum_Recipes - 1), $queryString_Recipes); ?>">Previous</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_Recipes < $totalPages_Recipes) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_Recipes=%d%s", $currentPage, min($totalPages_Recipes, $pageNum_Recipes + 1), $queryString_Recipes); ?>">Next</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_Recipes < $totalPages_Recipes) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_Recipes=%d%s", $currentPage, $totalPages_Recipes, $queryString_Recipes); ?>">Last</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table>
</div>
</div>
</div>
<div id="sidebar">
<a href="/"><img src="media/graphics/minifridge.gif" alt="home" width="100" height="188" border="0" /></a>
</div>

<?php readfile('footer.php'); ?>
</div>
</div>


</body>
</html>
<?php
mysql_free_result($Recipes);
?>
