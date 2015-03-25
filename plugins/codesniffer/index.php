<?php
/**
 * WebCodeSniffer 0.2
 *
 * PHP version 5
 *
 * @author Laurent Abbal <laurent@abbal.com>
 * @link   http://www.webcodesniffer.net
 */

 // Notifications
require 'CodeSniffer/wcs_notification.php'; 
if (date('Ymd') != $notification['check_date']) {

    $context = stream_context_create(array('http' => array('timeout' => 1)));
    $content = @file_get_contents('http://www.webcodesniffer.net/notifications/update.txt', 0, $context);

    
    if (!empty($content)) {
        $content_array = explode('#', $content);    
                
        if ($content_array[0] != $notification['date']) {
            $new_notification = fopen('CodeSniffer/wcs_notification.php', "w");
            $new_content = '<?php $notification = array(\'check_date\'=>\'' . date('Ymd') . '\',\'date\'=>\'' . $content_array[0] . '\',\'status\'=>\'1\',\'link\'=>\'' . $content_array[1] . '\',\'message\'=>\'' . $content_array[2] . '\'); ?>';
            fputs($new_notification, $new_content);
            fclose($new_notification);    
            $redirect = basename(__FILE__);
            header("Location: " . $redirect); 
            exit;
        }
    }
    
    $new_notification = fopen('CodeSniffer/wcs_notification.php', "w");
    $new_content = '<?php $notification = array(\'check_date\'=>\'' . date('Ymd') . '\',\'date\'=>\'' . $notification['date'] . '\',\'status\'=>\'' . $notification['status'] . '\',\'link\'=>\'' . $notification['link'] . '\',\'message\'=>\'' . $notification['message'] . '\'); ?>';
    fputs($new_notification, $new_content);
    fclose($new_notification);
};
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>WebCodeSniffer</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<link rel="shortcut icon" href="CodeSniffer/wcs_images/favicon.ico" />
<link rel="stylesheet" href="CodeSniffer/wcs_styles.css" type="text/css" />
<body>

<div class='top_left'><a href='http://www.webcodesniffer.net' target='_blank' class='link'>www.webcodesniffer.net</a></div>
<div class='top_center'>
<div class='title'><img src='CodeSniffer/wcs_images/wcs.png' width='120' height='54' alt='Web CodeSniffer' />0.2<span class="infobulle"><a class="info" href="#">?<span>WebCodeSniffer is a web version of PHP_CodeSniffer. No PEAR, no CLI needed. PHP_CodeSniffer is a PHP5 script that tokenises PHP, JavaScript and CSS files to detect violations of a defined set of coding standards. It is an essential development tool that ensures your code remains clean and consistent. It can also help prevent some common semantic errors made by developers.</span></a></span></div>
</div>
<div class='top_right'>
<?php
// Notification
if ($notification['status'] == 1) {
    ?>
    <div class="infobulle_notification_on"><a class="info" href="CodeSniffer/wcs_notification_redirect.php" target="_blank">!<span><?php echo $notification['message']; ?></span></a></div>
    <?php
} else {
    ?>
    <div class="infobulle_notification_off"><a class="info" href="CodeSniffer/wcs_notification_redirect.php" target="_blank">!<span><?php echo $notification['message']; ?></span></a></div>
    <?php
}
?>
</div>

<br style='clear:both;' />

<?php
if (isset($_POST['dir'])) {
    if ($_POST['dir'] == 'previous') {
        $dir = dirname($_POST['path']);    
    } elseif ($_POST['dir'] == 'current') {
        $dir = $_POST['path'];    
    } elseif ($_POST['dir'] == 'next') {    
        $dir = $_POST['path'] . '/' . $_POST['dir_name'];
    }
} else {
    $dir = dirname(getcwd());
}

echo '<div class="infopath">' . str_replace('\\', '/', $dir) . '</div>';

if (isset($_POST['filetosniff']) AND $_POST['filetosniff'] !='') {
    ?>
    <form action="<?php echo basename(__FILE__); ?>" method="post">
    <input type="hidden" name="path" value="<?php echo $dir; ?>" />    
    <input type="hidden" name="dir" value="current" />
    <input type="image" src="CodeSniffer/wcs_images/back.png" class="submit_back" />
    </form>

    <div class='report_header'>
    <form action="<?php echo basename(__FILE__); ?>" method="post">
    <input type="hidden" name="standard" value="<?php echo $_POST['standard']; ?>" />
    <input type="hidden" name="path" value="<?php echo $_POST['path']; ?>" />
    <input type="hidden" name="filetosniff" value="<?php echo $_POST['filetosniff']; ?>" />
    <input type="hidden" name="dir" value="current" />
    <input type="submit" value="RE-SNIFF" name="resniff" class="submit_sniff" /><br />
    </form>
    </div>
    <?php
    
    $_SERVER['argc'] = 3;
    $standard = '--standard=' . $_POST['standard'];
    $url = $_POST['path'] . '/' . $_POST['filetosniff'];
    $_SERVER['argv'] = array("phpcs.php",$standard,$url);
    
    
    echo '<div class="report"><pre>';
    include 'CodeSniffer/phpcs.php';
    echo '</pre></div>';

    exit;
}

if ($handle = opendir($dir)) {

    if ($dir != dirname(getcwd())) {
        ?>
        <form action="<?php echo basename(__FILE__); ?>" method="post">
        <input type="hidden" name="path" value="<?php echo $dir; ?>" />    
        <input type="hidden" name="dir" value="previous" />
        <input type="image" src="CodeSniffer/wcs_images/back.png" class="submit_back" />
        </form>
        <?php
    }

    $extensionstosniff = array('php','css', 'js');
    $typepicture = array('bmp','gif','png','jpg');
    
    while (false !== ($entry = readdir($handle))) {
        ?>
        <form action="<?php echo basename(__FILE__); ?>" method="post">
        <input type="hidden" name="path" value="<?php echo $dir; ?>" />
        <?php
        if ($entry != "." && $entry != ".." && $entry != "webcodesniffer") {
            if (is_dir($dir."/".$entry) === true) {
                ?>
                <div class='entry_row_dir'>
                <input type="hidden" name="dir" value="next" />
                <input type="submit" name="dir_name" value="<?php echo $entry; ?>" class="submit_folder" />
                </div>
                <?php            
            } else {
                
                if (in_array(pathinfo($dir."/".$entry, PATHINFO_EXTENSION), $extensionstosniff)) {
                    ?>
                    <div class='entry_row_filetosniff'>
                        <div class='entry_name'><?php echo $entry; ?></div>
                        <div class='entry_commandline'>
                            <span class='standard'>Standard:</span><select name='standard'>
                                <option value="PEAR" selected="selected">PEAR</option>
                                <option value="PHPCS">PHPCS</option>
                                <option value="PEAR">PEAR</option>
                                <option value="Squiz">Squiz</option>
                                <option value="Zend">Zend</option>
                                <option value="MySource">MySource</option>
                                <option value="Generic">Generic</option>
                                <option value="PSR1">PSR1</option>
                                <option value="PSR2">PSR2</option>
                                <option value="Joomla">Joomla</option>
                                <option value="Drupal">Drupal</option>
                                <option value="CakePHP">CakePHP</option>
                            </select><input type="submit" value="SNIFF" name="sniff" class="submit_sniff" />
                            <input type="hidden" name="filetosniff" value="<?php echo $entry; ?>" />
                            <input type="hidden" name="dir" value="current" />
                        </div>
                        <br style='clear:both;' />
                    </div>
                    <?php
                } else {
                
                    if (in_array(pathinfo($dir."/".$entry, PATHINFO_EXTENSION), $typepicture)) {
                        ?>
                        <div class='entry_row_file_picture'><div class='entry_name'><?php echo $entry; ?></div><br style='clear:both;' /></div>
                        <?php
                    } else {
                        ?>
                        <div class='entry_row_file_generic'><div class='entry_name'><?php echo $entry; ?></div><br style='clear:both;' /></div>
                        <?php                    
                    }
                }
            }
        }
        ?>
        </form>
        <?php
    }
    closedir($handle);
}
?>

<div class='footer'>WebCodeSniffer by <a href='http://www.easyphp.org' target='_blank'>Laurent Abbal</a> based on <a href='https://github.com/squizlabs/PHP_CodeSniffer' target='_blank'>PHP_CodeSniffer</a> by <a href='https://github.com/gsherwood' target='_blank'>Greg Sherwood</a> for <a href='http://www.squizlabs.com' target='_blank'>Squizlabs</a> | Icons by <a href='http://www.fatcow.com/free-icons' target='_blank'>FatCow</a></div>

</body>
</html>