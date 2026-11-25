<?php

function logMessage($data, string $level = 'INFO'): void {
    $logFile = __DIR__ . '/logs/app.log';

    if(!is_dir(dirname($logFile))){
        mkdir(dirname($logFile), 0755, true);
    }

    $timestamp = date('Y-m-d H:i:s');

    if(is_array($data) || is_object($data)){
        $data = print_r($data, true);
    }

    $message = sprintf(
        "[%s] [%s] %s\n",
        $timestamp,
        strtoupper($level),
        $data
    );

    file_put_contents($logFile, $message, FILE_APPEND);
}