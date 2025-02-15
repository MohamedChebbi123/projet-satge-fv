<?php
require 'vendor/autoload.php';
use Cron\CronExpression;

$cron = new CronExpression('0 9 * * *'); 

if ($cron->isDue()) {
    file_put_contents("cron_log.txt", "Cron job executed at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);

    $output = shell_exec("php mail.php 2>&1");
    file_put_contents("cron_log.txt", "Output: " . $output . "\n", FILE_APPEND);
}

echo "Cron check executed at " . date("Y-m-d H:i:s") . "\n";
?>
