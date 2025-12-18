<?php
    // Get the data from the POST request
    $level = $_POST['level'];
    $message = $_POST['message'];
    $timestamp = $_POST['timestamp'];
    
    // Format the log entry
    $logEntry = "[$timestamp] [$level]: $message" . PHP_EOL;
    
    // Append to a file named 'app_log.txt'
    file_put_contents('app_log.txt', $logEntry, FILE_APPEND);
?>
