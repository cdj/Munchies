<?php require_once('../Connections/admin1.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

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
  $deleteSQL = sprintf("DELETE FROM music WHERE num=%s",
                       GetSQLValueString($_GET['delete'], "int"));

  mysql_select_db($database_munchies1, $munchies1);
  $Result1 = mysql_query($deleteSQL, $munchies1) or die(mysql_error());

  $deleteGoTo = "music.php";
  header(sprintf("Location: %s", $deleteGoTo));
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO music (epnum, artist, song, url) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['epnum'], "int"),
                       GetSQLValueString($_POST['artist'], "text"),
                       GetSQLValueString($_POST['song'], "text"),
                       GetSQLValueString($_POST['url'], "text"));

  mysql_select_db($database_munchies1, $munchies1);
  $Result1 = mysql_query($insertSQL, $munchies1) or die(mysql_error());

  $insertGoTo = "music.php";
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE music SET epnum=%s, artist=%s, song=%s, url=%s WHERE num=%s",
                       GetSQLValueString($_POST['epnum'], "int"),
                       GetSQLValueString($_POST['artist'], "text"),
                       GetSQLValueString($_POST['song'], "text"),
                       GetSQLValueString($_POST['url'], "text"),
                       GetSQLValueString($_POST['num'], "int"));

  mysql_select_db($database_munchies1, $munchies1);
  $Result1 = mysql_query($updateSQL, $munchies1) or die(mysql_error());

  $updateGoTo = "music.php";
  header(sprintf("Location: %s", $updateGoTo));
}

$maxRows_Music = 50;
$pageNum_Music = 0;
if (isset($_GET['pageNum_Music'])) {
  $pageNum_Music = $_GET['pageNum_Music'];
}
$startRow_Music = $pageNum_Music * $maxRows_Music;

mysql_select_db($database_munchies1, $munchies1);
$query_Music = "SELECT num, epnum, artist, song, url FROM music ORDER BY epnum DESC";
$query_limit_Music = sprintf("%s LIMIT %d, %d", $query_Music, $startRow_Music, $maxRows_Music);
$Music = mysql_query($query_limit_Music, $munchies1) or die(mysql_error());
$row_Music = mysql_fetch_assoc($Music);

if (isset($_GET['totalRows_Music'])) {
  $totalRows_Music = $_GET['totalRows_Music'];
} else {
  $all_Music = mysql_query($query_Music);
  $totalRows_Music = mysql_num_rows($all_Music);
}
$totalPages_Music = ceil($totalRows_Music/$maxRows_Music)-1;

$colname_Edit = "-1";
if (isset($_GET['edit'])) {
  $colname_Edit = (get_magic_quotes_gpc()) ? $_GET['edit'] : addslashes($_GET['edit']);
}
mysql_select_db($database_munchies1, $munchies1);
$query_Edit = sprintf("SELECT num, epnum, artist, song, url FROM music WHERE num = %s", $colname_Edit);
$Edit = mysql_query($query_Edit, $munchies1) or die(mysql_error());
$row_Edit = mysql_fetch_assoc($Edit);
$totalRows_Edit = mysql_num_rows($Edit);

$queryString_Music = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Music") == false && 
        stristr($param, "totalRows_Music") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Music = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Music = sprintf("&totalRows_Music=%d%s", $totalRows_Music, $queryString_Music);
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
<title>Music Editor</title>
</head>

<body>
<a href="index.php">&lt;- Back to main site editor</a><br />
<?php if((!isset($_GET['delete']))&&(!isset($_GET['edit']))){ ?>
<strong>Add New Song</strong>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <table>
    <tr valign="baseline">
      <td nowrap align="right">Used in Episode:</td>
      <td><input name="epnum" type="text" value="" size="6" maxlength="6"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Artist:</td>
      <td><input name="artist" type="text" value="" size="32" maxlength="40"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Song:</td>
      <td><input type="text" name="song" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">URL (http://...):</td>
      <td><input type="text" name="url" value="" size="40"></td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap><input type="submit" value="Add Song"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<?php } if(isset($_GET['edit'])){ ?>
<strong>Edit Song</strong>
<form method="post" name="form2" action="<?php echo $editFormAction; ?>">
  <table>
    <tr valign="baseline">
      <td nowrap align="right">Used in Episode:</td>
      <td><input name="epnum" type="text" value="<?php echo $row_Edit['epnum']; ?>" size="6" maxlength="6"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Artist:</td>
      <td><input name="artist" type="text" value="<?php echo $row_Edit['artist']; ?>" size="32" maxlength="40"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Song:</td>
      <td><input type="text" name="song" value="<?php echo $row_Edit['song']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">URL (http://...):</td>
      <td><input type="text" name="url" value="<?php echo $row_Edit['url']; ?>" size="40"></td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap><input type="submit" value="Edit Song"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form2">
  <input type="hidden" name="num" value="<?php echo $row_Edit['num']; ?>">
</form>
<?php } ?>
<table border="1">
  <tr>
    <td>Episode</td>
    <td>Artist</td>
    <td>Song</td>
    <td>URL</td>
	<td></td>
	<td></td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_Music['epnum']; ?></td>
      <td><?php echo $row_Music['artist']; ?></td>
      <td><?php echo $row_Music['song']; ?></td>
      <td><?php if((!is_null($row_Music['url']))||($row_Music['url']!="")){ ?><a href="<?php echo $row_Music['url']; ?>">URL</a><?php }else{ echo "None"; } ?></td>
	  <td><a href="music.php?edit=<?php echo $row_Music['num']; ?>">Edit</a></td>
	  <td><a href="javascript:decision('Are you sure you want to delete this?','music.php?delete=<?php echo $row_Music['num']; ?>')">Delete</a></td>
    </tr>
    <?php } while ($row_Music = mysql_fetch_assoc($Music)); ?>
</table>
<table width="300" border="0">
  <tr>
    <td width="75" align="center"><?php if ($pageNum_Music > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_Music=%d%s", $currentPage, 0, $queryString_Music); ?>">First</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="75" align="center"><?php if ($pageNum_Music > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_Music=%d%s", $currentPage, max(0, $pageNum_Music - 1), $queryString_Music); ?>">Previous</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="75" align="center"><?php if ($pageNum_Music < $totalPages_Music) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_Music=%d%s", $currentPage, min($totalPages_Music, $pageNum_Music + 1), $queryString_Music); ?>">Next</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="75" align="center"><?php if ($pageNum_Music < $totalPages_Music) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_Music=%d%s", $currentPage, $totalPages_Music, $queryString_Music); ?>">Last</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Music);
mysql_free_result($Edit);
?>