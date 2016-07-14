<?php
//Replace this in viewuser.php
if($r['display_pic'])
{
print "<img src='{$r['display_pic']}' width='150' height='150' alt='User Display Pic' title='User Display Pic' />";
}
else
{
print "This user has no display pic!";
}



//With this



$getDisplayPic = $db->query("SELECT `id` FROM `displaypics` WHERE (`user` = ".$r['userid'].")");
if($db->num_rows($getDisplayPic) > 0) {
	$dpC = $db->fetch_row($getDisplayPic);
	echo '<img src="displaypic.php?view='.$dpC['id'].'" alt="User Display Pic" />';
} else {
	echo 'No display pic';
}