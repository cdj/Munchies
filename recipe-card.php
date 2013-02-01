<?php require_once('Connections/munchies1.php'); ?>
<?php
$colname_Recipe = "-1";
if (isset($_GET['num'])) {
  $colname_Recipe = (get_magic_quotes_gpc()) ? $_GET['num'] : addslashes($_GET['num']);
}
mysql_select_db($database_munchies1, $munchies1);
$query_Recipe = sprintf("SELECT name, picfile, direc FROM recipes WHERE num = %s", $colname_Recipe);
$Recipe = mysql_query($query_Recipe, $munchies1) or die(mysql_error());
$row_Recipe = mysql_fetch_assoc($Recipe);
$totalRows_Recipe = mysql_num_rows($Recipe);

$colname_Ingredients = "-1";
if (isset($_GET['num'])) {
  $colname_Ingredients = (get_magic_quotes_gpc()) ? $_GET['num'] : addslashes($_GET['num']);
}
mysql_select_db($database_munchies1, $munchies1);
$query_Ingredients = sprintf("SELECT item FROM ingredients WHERE recipe = %s", $colname_Ingredients);
$Ingredients = mysql_query($query_Ingredients, $munchies1) or die(mysql_error());
$row_Ingredients = mysql_fetch_assoc($Ingredients);
$totalRows_Ingredients = mysql_num_rows($Ingredients);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $row_Recipe['name']; ?></title>
<link href="Styles/recipe-card.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="card">
  <?php if((!is_null($row_Recipe['picfile']))&&($row_Recipe['picfile']!="")){ ?><img src="media/recipes/<?php echo $row_Recipe['picfile']; ?>" alt="<?php echo $row_Recipe['name']; ?>" /><?php } ?>
  <h1><?php echo $row_Recipe['name']; ?></h1>
  <h2>Ingredients:</h2>
  <ul>
    <?php do { ?>
      <li><?php echo $row_Ingredients['item']; ?></li>
      <?php } while ($row_Ingredients = mysql_fetch_assoc($Ingredients)); ?></ul>
  <h2>Directions:</h2>
  <p><?php echo nl2br($row_Recipe['direc']); ?></p>
</div>
</body>
</html>
<?php
mysql_free_result($Recipe);

mysql_free_result($Ingredients);
?>
