<?php

namespace App\Logger;

use App\Models\Log;

class Logger
{
    private static $session = '';

    public function __construct()
    {

    }

    private static function randomString($length = 10)
    {
        $string = '';
        while (($len = strlen($string)) < $length) {
            $size = $length - $len;
            $bytes = random_bytes($size);
            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }

    public static function newSession()
    {
        self::$session = self::randomString();
    }

    public static function log(LogData $logData)
    {
        if (self::$session == '') {
            self::newSession();
        }
        $log = new Log();
        $log->action = $logData->action;
        $log->ip = $_SERVER['REMOTE_ADDR'];
        $log->session = self::$session;
        if ($logData->userId) {
            $log->user_id = $logData->userId;
        }
        if ($logData->suttaId) {
            $log->sutta_id = $logData->suttaId;
        }
        if ($logData->termId) {
            $log->term_id = $logData->termId;
        }
        if ($logData->contentId) {
            $log->content_id = $logData->contentId;
        }
        if ($logData->chunkId) {
            $log->chunk_id = $logData->chunkId;
        }
        if ($logData->storage) {
            $log->storage = json_encode($logData->storage, JSON_PRETTY_PRINT);
        }
        if ($logData->before) {
            $log->before = json_encode($logData->before, JSON_PRETTY_PRINT);
        }
        if ($logData->after) {
            $log->after = json_encode($logData->after, JSON_PRETTY_PRINT);
        }
        $log->save();
    }
}
