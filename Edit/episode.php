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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL1 = sprintf("INSERT INTO epguide (epnum, sname, `desc`, filename, podcast) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['epnum'], "int"),
                       GetSQLValueString(htmlentities($_POST['sname'], ENT_QUOTES, "ISO-8859-1", false), "text"),
                       GetSQLValueString(htmlentities($_POST['desc'], ENT_QUOTES, "ISO-8859-1", false), "text"),
                       GetSQLValueString($_POST['filename'], "text"),
                       GetSQLValueString($_POST['podcast'], "int"));
  mysql_select_db($database_munchies1, $munchies1);
  $Result1 = mysql_query($insertSQL1, $munchies1) or die(mysql_error());
  
  $insertSQL2 = sprintf("INSERT INTO airdates (epnum, airtime) VALUES (%s, %s)",
                       GetSQLValueString($_POST['epnum'], "text"),
                       GetSQLValueString($_POST['airdate']." 23:00:00", "date"));
  mysql_select_db($database_munchies1, $munchies1);
  $Result2 = mysql_query($insertSQL2, $munchies1) or die(mysql_error());
  
  $insertGoTo = "episode.php";
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE epguide SET epnum=%s, sname=%s, `desc`=%s, filename=%s, podcast=%s WHERE num=%s",
                       GetSQLValueString($_POST['epnum'], "int"),
                       GetSQLValueString(htmlentities($_POST['sname'], ENT_QUOTES, "ISO-8859-1", false), "text"),
                       GetSQLValueString(htmlentities($_POST['desc'], ENT_QUOTES, "ISO-8859-1", false), "text"),
                       GetSQLValueString($_POST['filename'], "text"),
                       GetSQLValueString($_POST['podcast'], "int"),
                       GetSQLValueString($_POST['num'], "int"));

  mysql_select_db($database_munchies1, $munchies1);
  $Result1 = mysql_query($updateSQL, $munchies1) or die(mysql_error());
  $insertGoTo = "episode.php";
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3")) {
  $insertSQL = sprintf("INSERT INTO airdates (epnum, airtime) VALUES (%s, %s)",
                       GetSQLValueString($_POST['epnum'], "text"),
                       GetSQLValueString($_POST['airdate']." 23:00:00", "date"));
  mysql_select_db($database_munchies1, $munchies1);
  $Result1 = mysql_query($insertSQL, $munchies1) or die(mysql_error());
  
  $insertGoTo = "episode.php?edit=".$_POST['epnum'];
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_GET['deleteairdate'])) && ($_GET['deleteairdate'] != "")) {
  $deleteSQL = sprintf("DELETE FROM airdates WHERE num=%s",
                       GetSQLValueString($_GET['deleteairdate'], "int"));

  mysql_select_db($database_munchies1, $munchies1);
  $Result1 = mysql_query($deleteSQL, $munchies1) or die(mysql_error());
  $deleteGoTo = "episode.php?edit=".$_GET["edit"];
  header(sprintf("Location: %s", $deleteGoTo));
}

mysql_select_db($database_munchies1, $munchies1);
$query_Episodes = "SELECT num, epnum, sname, podcast FROM epguide ORDER BY `num` DESC";
$Episodes = mysql_query($query_Episodes, $munchies1) or die(mysql_error());
$row_Episodes = mysql_fetch_assoc($Episodes);
$totalRows_Episodes = mysql_num_rows($Episodes);

$colname_Edit = "-1";
if (isset($_GET['edit'])) {
  $colname_Edit = (get_magic_quotes_gpc()) ? $_GET['edit'] : addslashes($_GET['edit']);
}
mysql_select_db($database_munchies1, $munchies1);
$query_Edit = sprintf("SELECT * FROM epguide WHERE num = %s", $colname_Edit);
$Edit = mysql_query($query_Edit, $munchies1) or die(mysql_error());
$row_Edit = mysql_fetch_assoc($Edit);
$totalRows_Edit = mysql_num_rows($Edit);
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
<title>Episode Manager</title>
</head>

<body>
<a href="index.php">&lt;- Back to main site editor</a>
<?php if(!isset($_GET["edit"]) || ($_GET["edit"] == "")){ ?>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <table border="1" cellspacing="0">
    <tr valign="baseline">
      <td nowrap align="right">Episode number:</td>
      <td><input name="epnum" type="text" value="<?php echo $row_Episodes['epnum']+1; ?>" size="6" maxlength="4"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">First Air date:</td>
      <td><input name="airdate" type="text" value="<?php echo date("Y-m-d",strtotime("Monday")); ?>" size="12" maxlength="10"> YYYY-MM-DD</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Name:</td>
      <td><input type="text" name="sname" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Description:</td>
      <td><textarea name="desc" cols="32"></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Podcast URL:</td>
      <td><input type="text" name="filename" value="http://" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Display Podcast:</td>
      <td><select name="podcast">
        <option value="0" selected="selected">No</option>
        <option value="1">Yes</option>
      </select>      </td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="right" nowrap><div align="center">
        <input type="submit" value="Add Episode">
      </div></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<br />
<?php }else{ ?>
    <form method="post" name="form2" action="<?php echo $editFormAction; ?>">
	<table border="1" cellspacing="0">
    <tr valign="baseline">
      <td nowrap align="right">Episode number:</td>
      <td><input name="epnum" type="text" value="<?php echo $row_Edit['epnum']; ?>" size="6" maxlength="4"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Name:</td>
      <td><input type="text" name="sname" value="<?php echo $row_Edit['sname']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Description:</td>
      <td><textarea name="desc" cols="32"><?php echo $row_Edit['desc']; ?></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Podcast URL:</td>
      <td><input type="text" name="filename" value="<?php echo $row_Edit['filename']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Display Podcast:</td>
      <td><select name="podcast">
        <option value="0" <?php if (!(strcmp(0, $row_Edit['podcast']))) {echo "SELECTED";} ?>>No</option>
        <option value="1" <?php if (!(strcmp(1, $row_Edit['podcast']))) {echo "SELECTED";} ?>>Yes</option>
      </select>      </td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="right" nowrap><div align="center">
        <input type="submit" value="Edit Episode">
      </div></td>
    </tr>
  </table>
    <input type="hidden" name="num" value="<?php echo $row_Edit['num']; ?>">
    <input type="hidden" name="MM_update" value="form2">
  </form>
  <form method="post" name="form3" action="<?php echo $editFormAction; ?>">
  <table border="1">
    <tr>
      <td>Airdate</td>
      <td></td>
    </tr>
<?php
mysql_select_db($database_munchies1, $munchies1);
$query_Airdates = "SELECT num, DATE_FORMAT(airtime, \"%Y-%m-%d\") AS airdate FROM airdates WHERE epnum=".$row_Edit['epnum']." ORDER BY airtime ASC";
$Airdates = mysql_query($query_Airdates, $munchies1) or die(mysql_error());
$totalRows_Airdates = mysql_num_rows($Airdates);
while($row_Airdates = mysql_fetch_assoc($Airdates)) {
?>
    <tr>
      <td><?php echo $row_Airdates['airdate']; ?></td>
      <td><a href="javascript:decision('Are you sure you want to delete this airdate?','episode.php?deleteairdate=<?php echo $row_Airdates['num']; ?>&amp;edit=<?php echo $_GET["edit"]; ?>')">Delete</a></td>
    </tr>
<?php }
if($totalRows_Airdates > 0)
	mysql_free_result($Airdates);
?>
    <tr>
      <td><input name="airdate" type="text" value="YYYY-MM-DD" size="12" maxlength="10"></td>
      <td><input type="submit" value="Add"></td>
    </tr>
  </table>
    <input type="hidden" name="epnum" value="<?php echo $row_Edit['epnum']; ?>">
    <input type="hidden" name="MM_insert" value="form3">
  </form>
	<a href="episode.php">Add Episode</a>
<?php } ?>
<table border="1">
  <tr>
    <td>Number</td>
    <td>Name</td>
    <td>Podcast</td>
	<td>&nbsp;</td>
  </tr>
<?php do { ?>
    <tr>
      <td><?php echo $row_Episodes['epnum']; ?></td>
      <td><?php echo $row_Episodes['sname']; ?></td>
      <td><?php if($row_Episodes['podcast']==0) echo "No"; else echo "Yes"; ?></td>
      <td><a href="episode.php?edit=<?php echo $row_Episodes['num']; ?>">Edit</a></td>
    </tr>
<?php } while ($row_Episodes = mysql_fetch_assoc($Episodes)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($Episodes);
mysql_free_result($Edit);
?>