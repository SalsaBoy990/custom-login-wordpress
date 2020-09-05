<?php

namespace AG\CustomLoginForm\Log;

/**
 * trait for logging
 * @param global $ag_weather_now_log
 */
trait Logger
{

    public function logger(int $debug = 0, int $logging = 1): void
    {
        if ($debug) {
            $info_text = "Entering - " . __FILE__ . ":" . __FUNCTION__ . ":" . __LINE__;
            echo '<div class="notice notice-info is-dismissible">' . $info_text . '</p></div>';
        }
        if ($logging) {
            global $ag_weather_now_log;
            $ag_weather_now_log->logInfo("Entering - " . __FILE__ . ":" . __FUNCTION__ . ":" . __LINE__);
        }
    }

    public function exceptionLogger(int $logging = 1, object $ex = null): void
    {
        if ($logging) {
            global $ag_weather_now_log;
            $ag_weather_now_log->logInfo(
                $ex->getMessage() . " - " . __FILE__ . ":" . __FUNCTION__ . ":" . __LINE__
            );
        }
    }
}
