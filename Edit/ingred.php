<?php require_once('../Connections/admin1.php'); ?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

if ((isset($_GET['delete'])) && ($_GET['delete'] != "")) {
  $deleteSQL = sprintf("DELETE FROM ingredients WHERE num=%s",
                       GetSQLValueString($_GET['delete'], "int"));

  mysql_select_db($database_munchies1, $munchies1);
  $Result1 = mysql_query($deleteSQL, $munchies1) or die(mysql_error());

  $deleteGoTo = "recipe.php?edit=".$_GET["rec"];
  header(sprintf("Location: %s", $deleteGoTo));
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE ingredients SET recipe=%s, item=%s WHERE num=%s",
                       GetSQLValueString($_POST['recipe'], "int"),
                       GetSQLValueString($_POST['item'], "text"),
                       GetSQLValueString($_POST['num'], "int"));

  mysql_select_db($database_munchies1, $munchies1);
  $Result1 = mysql_query($updateSQL, $munchies1) or die(mysql_error());

  $updateGoTo = "recipe.php?edit=".$_POST['recipe'];
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_munchies1, $munchies1);
$query_ingred = "SELECT item, recipe, num FROM ingredients WHERE `num`=".$_GET["edit"];
$ingred = mysql_query($query_ingred, $munchies1) or die(mysql_error());
$row_ingred = mysql_fetch_assoc($ingred);
$totalRows_ingred = mysql_num_rows($ingred);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Edit Ingredients</title>
</head>

<body>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <table>
    <tr valign="baseline">
      <td nowrap align="right">Ingredient:</td>
      <td><input type="text" name="item" value="<?php echo $row_ingred['item']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="right" nowrap><div align="center">
        <input type="submit" value="Edit Item">
      </div></td>
    </tr>
  </table>
  <input type="hidden" name="recipe" value="<?php echo $row_ingred['recipe']; ?>">
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="num" value="<?php echo $row_ingred['num']; ?>">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($ingred);
?>
