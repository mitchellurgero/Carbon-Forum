<?php
require(dirname(__FILE__) . '/common.php');
require(dirname(__FILE__) . '/language/' . ForumLanguage . '/notifications.php');
Auth(1);
$NotificationsArray = $DB->query('SELECT n.ID as NID, n.Type, n.IsRead, p.ID, p.TopicID, p.IsTopic, p.UserID, p.UserName, p.Subject, p.Content, p.PostTime, p.IsDel FROM ' . $Prefix . 'notifications n LEFT JOIN ' . $Prefix . 'posts p on p.ID=n.PostID Where n.UserID = ? ORDER BY n.Time DESC LIMIT 200', array(
	$CurUserID
));
$ReplyArray         = array();
$MentionArray       = array();
if ($NotificationsArray) {
	foreach ($NotificationsArray as $Value) {
		switch ($Value['Type']) {
			case 1:
				$ReplyArray[] = $Value;
				break;
			case 2:
				$MentionArray[] = $Value;
				break;
			default:
				break;
		}
	}
}
unset($NotificationsArray);
//Clear unread marks
UpdateUserInfo(array(
	'NewMessage' => 0
));
$CurUserInfo['NewMessage'] = 0;
$DB->CloseConnection();
// 页面变量
$PageTitle   = $Lang['Notifications'];
$ContentFile = $TemplatePath . 'notifications.php';
include($TemplatePath . 'layout.php');