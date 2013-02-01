<?php require_once('Connections/munchies1.php'); ?>
<?php
date_default_timezone_set('America/New_York');
mysql_select_db($database_munchies1, $munchies1);
$query_episodes = "SELECT epguide.epnum, epguide.sname, epguide.desc, epguide.filename, UNIX_TIMESTAMP(airdates.airtime) AS airdate FROM epguide INNER JOIN airdates USING(epnum) WHERE epguide.podcast = 1 GROUP BY epnum ORDER BY epnum DESC";
$episodes = mysql_query($query_episodes, $munchies1) or die(mysql_error());
$row_episodes = mysql_fetch_assoc($episodes);
$totalRows_episodes = mysql_num_rows($episodes);
?>
<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0">
<channel>
	<title>I've Got Munchies</title>
	<description>A show for anyone under the influence of anything from liquor to love. Learn to cook simple but delicious food fast. You can even cook while influenced by any element! Plus a little wacky sketch comedy thrown in for shits and giggles...anyone can relate to these sketches!</description>
	<link>http://www.ivegotmunchies.com</link>
	<language>en-us</language>
	<copyright>Copyright <?php echo date("Y"); // Display current year ?></copyright>
	<lastBuildDate><?php echo date("r", $row_episodes['airdate']); ?></lastBuildDate>
	<pubDate><?php echo date("r", $row_episodes['airdate']); ?></pubDate>
	<docs>http://blogs.law.harvard.edu/tech/rss</docs>
	<webMaster>webmaster@ivegotmunchies.com</webMaster>
	<itunes:author>hungryagain@ivegotmunchies.com</itunes:author>
	<itunes:subtitle>A cooking show for anyone under the influence.</itunes:subtitle>
	<itunes:summary>A video podcast for anyone under the influence of anything from liquor to love. Learn to cook simple but delicious food fast. You can even cook while influenced by any element! Plus a little wacky sketch comedy thrown in for shits and giggles...anyone can relate to these sketches!</itunes:summary>
	<itunes:owner>
		<itunes:name>Sharon</itunes:name>
		<itunes:email>sharon@ivegotmunchies.com</itunes:email>
	</itunes:owner>
	<itunes:explicit>Yes</itunes:explicit>
	<itunes:image href="http://www.ivegotmunchies.com/itunes.jpg"/>
<?php do {
?>
	<item>
		<title><?php echo $row_episodes['epnum'].": ".$row_episodes['sname']; ?></title>
		<link><?php echo $row_episodes['filename']; ?></link>
		<description><?php echo $row_episodes['desc']; ?></description>
		<category>Podcasts</category>
		<pubDate><?php echo date("r", $row_episodes['airdate']); ?></pubDate>
		<itunes:explicit>Yes</itunes:explicit>
		<itunes:subtitle><?php echo $row_episodes['desc']; ?></itunes:subtitle>
		<itunes:summary><?php echo $row_episodes['desc']; ?></itunes:summary>
	</item><?php } while ($row_episodes = mysql_fetch_assoc($episodes)); ?>
</channel>
</rss>
<?php
mysql_free_result($episodes);
?>