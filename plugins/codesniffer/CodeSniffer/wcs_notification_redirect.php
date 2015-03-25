<?php
/**
 * notification_redirect.php
 *
 * PHP version 5
 * 
 * @author Laurent Abbal <laurent@abbal.com>
 * @link   http://www.webcodesniffer.net
 */

require 'wcs_notification.php';
$new_notification = fopen('wcs_notification.php', 'w');
$new_content = '<?php $notification = array(\'check_date\'=>\''.date('Ymd').'\',\'date\'=>\''.$notification['date'].'\',\'status\'=>\'0\',\'link\'=>\''.$notification['link'].'\',\'message\'=>\''.$notification['message'].'\'); ?>';
fputs($new_notification, $new_content);
fclose($new_notification);
$redirect = $notification['link'];
header("Location: " . $redirect); 
exit;
?>