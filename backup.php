<?php
include("includes/header.php");

if(isset($_GET['q']) and $_GET['q']=='download'){
	if(file_exists('backup/dump.sql')){
		unlink('backup/dump.sql');
	}
	include_once(dirname(__FILE__) . '/mysqldump-php-master/src/Ifsnop/Mysqldump/Mysqldump.php');
    $dump = new Ifsnop\Mysqldump\Mysqldump('mysql:host='.$host.';dbname='.$db, $user, $pass);
    $dump->start('backup/dump.sql');
	if(file_exists('backup/dump.sql')){
		$file = 'backup/dump.sql';
		header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));

        ob_clean();
        flush();
        readfile($file);
		unlink('backup/dump.sql');
        exit;
	}
}
?>
	<div class="area">
		<div class="panel-head">Get Backup</div>
		<div class="panel"><h3>Click to download backup</h3>
		<div class="rl">
		<a href="backup.php?q=download"><div class="rl1"><img src="images/backup2.png" height="150px" align="absmiddle" /><div align="center" style="margin-top:10px;">Download Backup</div></div></a>
		</div>
		</div>
		</div>
<?php 
	require('includes/footer.php');
?>