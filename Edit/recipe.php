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
  mysql_select_db($database_munchies1, $munchies1);
  $query_delpic = sprintf("SELECT * FROM recipes WHERE num = %s",$_GET["delete"]);
  $delpic = mysql_query($query_delpic, $munchies1) or die(mysql_error());
  $row_delpic = mysql_fetch_assoc($delpic);
  mysql_free_result($delpic);
  if((!is_null($row_delpic["picfile"]))||($row_delpic["picfile"]!="")) unlink("../media/recipes/".$row_delpic["picfile"]);
  $deleteSQL = sprintf("DELETE FROM recipes WHERE num=%s",
                       GetSQLValueString($_GET['delete'], "int"));
  $deleteingredSQL = sprintf("DELETE FROM ingredients WHERE recipe=%s",
                       GetSQLValueString($_GET['delete'], "int"));

  mysql_select_db($database_munchies1, $munchies1);
  $Result1 = mysql_query($deleteSQL, $munchies1) or die(mysql_error());
  $Result2 = mysql_query($deleteingredSQL, $munchies1) or die(mysql_error());
  $deleteGoTo = "recipe.php";
  header(sprintf("Location: %s", $deleteGoTo));
}

function createpic($name,$filename,$new_w,$new_h){ 
    $system=explode(".",$filename); 
    if (preg_match("/jpg|jpeg/",$system[count($system)-1])){ 
        $src_img=imagecreatefromjpeg($name);
    } 
    if (preg_match("/png/",$system[count($system)-1])){ 
        $src_img=imagecreatefrompng($name);
    }
	if (preg_match("/gif/",$system[count($system)-1])){ 
        $src_img=imagecreatefromgif($name);
    }
	
	$old_x=imagesx($src_img); 
    $old_y=imagesy($src_img); 
	$thumb_h=$old_y;
	$thumb_w=$old_x;
    if ($old_x > $new_w) { 
        $thumb_w=$new_w;
        $thumb_h=$old_y*$new_w/$old_x;
		if ($thumb_h>$new_h) {
			$thumb_h=$new_h;
			$thumb_w=$old_x*$new_h/$old_y;
		}
    } elseif ($old_y > $new_h) {
		$thumb_h=$new_h;
        $thumb_w=$old_x*$new_h/$old_y;
	}
	$dst_img=imagecreatetruecolor($thumb_w,$thumb_h); 
    imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); 
    if (preg_match("/png/",$system[count($system)-1])){ 
        imagepng($dst_img,$filename); 
    }
	if (preg_match("/gif/",$system[count($system)-1])){ 
        imagegif($dst_img,$filename); 
    }
	if (preg_match("/jpg|jpeg/",$system[count($system)-1])){
        imagejpeg($dst_img,$filename); 
    }

    imagedestroy($dst_img); 
	imagedestroy($src_img);
} 

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if(isset($_GET["deletepic"])){
  mysql_select_db($database_munchies1, $munchies1);
  $query_delpic = sprintf("SELECT * FROM recipes WHERE num = %s",$_GET["deletepic"]);
  $delpic = mysql_query($query_delpic, $munchies1) or die(mysql_error());
  $row_delpic = mysql_fetch_assoc($delpic);
  mysql_free_result($delpic);
  unlink("../media/recipes/".$row_delpic["picfile"]);
  $updateSQL = sprintf("UPDATE recipes SET picfile=NULL WHERE num=%s",$_GET["deletepic"]);
  mysql_select_db($database_munchies1, $munchies1);
  $Result1 = mysql_query($updateSQL, $munchies1) or die(mysql_error());
  $insertGoTo = "recipe.php?edit=".$_GET["deletepic"];
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  if(isset($_FILES['Fname'])){
  $source_file = $_FILES['Fname']['tmp_name'];
  $p = $_POST['ep']."-".$_FILES['Fname']['name'];
  }else{
  $p = $_POST['picfile'];
  }
  $updateSQL = sprintf("UPDATE recipes SET name=%s, ep=%s, direc=%s, picfile=%s WHERE num=%s",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['ep'], "int"),
                       GetSQLValueString($_POST['direc'], "text"),
                       GetSQLValueString($p, "text"),
                       GetSQLValueString($_POST['num'], "int"));

  mysql_select_db($database_munchies1, $munchies1);
  $Result1 = mysql_query($updateSQL, $munchies1) or die(mysql_error());
  if((isset($_FILES['Fname']))&&($_FILES['Fname']['name']!="")){
  	createpic($source_file,"../media/recipes/".$p,150,125);
  }

  $insertGoTo = "recipe.php";
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3")) {
  $insertSQL = sprintf("INSERT INTO recipes (name, ep, direc, picfile) VALUES (%s, %s, %s, NULL)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['ep'], "int"),
                       GetSQLValueString($_POST['direc'], "text"));

  mysql_select_db($database_munchies1, $munchies1);
  $Result1 = mysql_query($insertSQL, $munchies1) or die(mysql_error());

  mysql_select_db($database_munchies1, $munchies1);
  $query_new = sprintf("SELECT num FROM recipes WHERE name=%s",GetSQLValueString($_POST['name'], "text"));
  $new = mysql_query($query_new, $munchies1) or die(mysql_error());
  $row_new = mysql_fetch_assoc($new);
  mysql_free_result($new);

  $insertGoTo = "recipe.php?edit=".$row_new['num'];
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  $insertSQL = sprintf("INSERT INTO ingredients (recipe, item) VALUES (%s, %s)",
                       GetSQLValueString($_POST['recipe'], "int"),
                       GetSQLValueString($_POST['item'], "text"));

  mysql_select_db($database_munchies1, $munchies1);
  $Result1 = mysql_query($insertSQL, $munchies1) or die(mysql_error());

  $insertGoTo = "recipe.php?edit=".$_GET["edit"];
  header(sprintf("Location: %s", $insertGoTo));
}



mysql_select_db($database_munchies1, $munchies1);
$query_Recipes = "SELECT num, name, ep, picfile FROM recipes ORDER BY ep DESC";
$Recipes = mysql_query($query_Recipes, $munchies1) or die(mysql_error());
$row_Recipes = mysql_fetch_assoc($Recipes);
$totalRows_Recipes = mysql_num_rows($Recipes);

$colname_recedit = "-1";
if (isset($_GET['edit'])) {
  $colname_recedit = (get_magic_quotes_gpc()) ? $_GET['edit'] : addslashes($_GET['edit']);
}
mysql_select_db($database_munchies1, $munchies1);
$query_recedit = sprintf("SELECT * FROM recipes WHERE num = %s", $colname_recedit);
$recedit = mysql_query($query_recedit, $munchies1) or die(mysql_error());
$row_recedit = mysql_fetch_assoc($recedit);
$totalRows_recedit = mysql_num_rows($recedit);

$colname_ingred = "-1";
if (isset($_GET['edit'])) {
  $colname_ingred = (get_magic_quotes_gpc()) ? $_GET['edit'] : addslashes($_GET['edit']);
}
mysql_select_db($database_munchies1, $munchies1);
$query_ingred = sprintf("SELECT num, item FROM ingredients WHERE recipe = %s", $colname_ingred);
$ingred = mysql_query($query_ingred, $munchies1) or die(mysql_error());
$row_ingred = mysql_fetch_assoc($ingred);
$totalRows_ingred = mysql_num_rows($ingred);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<SCRIPT LANGUAGE="Javascript">
<!---
function decision(message, url){
if(confirm(message)) location.href = url;
}
// --->
</SCRIPT>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Recipe Manager</title>
</head>

<body>
<a href="index.php">&lt;- Back to main site editor</a>
<?php if(isset($_GET["edit"])){ ?>
<p><strong>Edit
  </strong>
<table>
    <tr valign="baseline">
      <td nowrap align="right" valign="top">Ingredients:</td>
      <td>
          <?php if($row_ingred['num']!="") { do { ?>
          <?php echo $row_ingred['item']; ?> - <a href="ingred.php?edit=<?php echo $row_ingred['num']; ?>">Edit</a> - <a href="javascript:decision('Are you sure you want to delete this ingredient?','ingred.php?delete=<?php echo $row_ingred['num']; ?>&amp;rec=<?php echo $_GET["edit"]; ?>')">Delete</a><br />
          <?php } while ($row_ingred = mysql_fetch_assoc($ingred)); mysql_free_result($ingred); } ?>
		  <form method="post" name="form2" action="<?php echo $editFormAction; ?>">
		   <input type="text" name="item" value="" size="32"><input type="submit" value="Add Ingredient">
		   <input type="hidden" name="recipe" value="<?php echo $_GET["edit"]; ?>">
		   <input type="hidden" name="MM_insert" value="form2">
		  </form>
      </td>
    </tr>
	<form method="post" name="form1" action="<?php echo $editFormAction; ?>" enctype="multipart/form-data">
	<tr valign="baseline">
      <td nowrap align="right">Name:</td>
      <td><input type="text" name="name" value="<?php echo $row_recedit['name']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Episode:</td>
      <td><input name="ep" type="text" value="<?php echo $row_recedit['ep']; ?>" size="6" maxlength="4"></td>
    </tr>
    
    <tr valign="baseline">
      <td nowrap align="right" valign="top">Directions:</td>
      <td><textarea name="direc" cols="50" rows="5"><?php echo $row_recedit['direc']; ?></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Picture:</td>
      <td><?php if((is_null($row_recedit['picfile']))||($row_recedit['picfile']=="")){ ?><input type="file" name="Fname"><?php } else { ?><img src="../media/recipes/<?php echo $row_recedit['picfile']; ?>" /><input type="hidden" name="picfile" value="<?php echo $row_recedit['picfile']; ?>"><a href="javascript:decision('Are you sure you want to delete this picture?','recipe.php?deletepic=<?php echo $_GET["edit"]; ?>')">Delete</a><?php } ?></td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap><input type="submit" value="Edit Recipe"></td>
    </tr>
	<input type="hidden" name="MM_update" value="form1">
	<input type="hidden" name="num" value="<?php echo $row_recedit['num']; ?>">
	</form>
</table>
</p><a href="recipe.php">Add New Recipe</a><?php mysql_free_result($recedit); } else { ?>
<p><strong>Add
</strong>
<form method="post" name="form3" action="<?php echo $editFormAction; ?>">
  <table>
    <tr valign="baseline">
      <td nowrap align="right">Name:</td>
      <td><input type="text" name="name" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Episode:</td>
      <td><input name="ep" type="text" value="" size="6" maxlength="4"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right" valign="top">Directions:</td>
      <td><textarea name="direc" cols="50" rows="5"></textarea></td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap><input type="submit" value="Add Recipe"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form3">
</form>
</p><?php } ?>
<table border="1">
  <tr>
    <td>Name</td>
    <td>Episode</td>
    <td>Picture</td>
	<td>&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_Recipes['name']; ?></td>
      <td><?php echo $row_Recipes['ep']; ?></td>
      <td><?php if((is_null($row_Recipes['picfile']))||($row_Recipes['picfile']=="")) echo "No"; else echo "Yes"; ?></td>
	  <td><a href="recipe.php?edit=<?php echo $row_Recipes['num']; ?>">Edit</a> - <a href="javascript:decision('Are you sure you want to delete <?php echo $row_Recipes['name']; ?>?','recipe.php?delete=<?php echo $row_Recipes['num']; ?>')">Delete</a></td>
    </tr>
    <?php } while ($row_Recipes = mysql_fetch_assoc($Recipes)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($Recipes);
?>