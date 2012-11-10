<?php
function badgewidgethack_convert_email_to_openbadges_id($email) {
	$postdata = http_build_query(
	    array(
	        'email' => $email
	    )
	);

	$opts = array('http' =>
	    array(
	        'method'  => 'POST',
	        'header'  => 'Content-type: application/x-www-form-urlencoded',
	        'content' => $postdata
	    )
	);

	$context  = stream_context_create($opts);
	$emailjson = file_get_contents('http://beta.openbadges.org/displayer/convert/email', false, $context);
	$emaildata = json_decode($emailjson);
	return $emaildata->userId;
}

function badgewidget_return_groups_given_badge_id($userid) {
	$url = "http://beta.openbadges.org/displayer/" . $userid . "/groups.json";
	$groupjson = file_get_contents($url);
	$groupdata = json_decode($groupjson,true);
	return $groupdata;
}

?>

<html>
<body>
<img src="./bwh.png" align="right" />

<?php
$userid = badgewidgethack_convert_email_to_openbadges_id($_POST['email']);
$data = badgewidget_return_groups_given_badge_id($userid);

if ($limit = count($data[groups])) {?>
	
	<form action="badgewidget.php" method="post">

	Next, choose the group of badges you want to display in your widget.
	<br />
	
	<?php
	echo "<p><select name='group'><option>Select A Group</option>";

	$i = 0;
	while ($i < $limit) {
	                $group = $data[groups][$i][groupId];
	                $groupname = $data[groups][$i][name];
	                echo "<option value='" . $group . "." . $groupname . "'>" . $groupname . "</option>";
	                $i = $i + 1;
	}
	echo "</select></p>";
	echo "<input type='hidden' name='user' value='" . $userid . "'>";?>

	<p>And then <input type="submit" value="Continue >>>"></p>
	</form>

<?php
} else{
	echo "<p>You have no public groups in your backpack. Try making one public and adding a badge to it.</p>";
}
?>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-30946847-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>