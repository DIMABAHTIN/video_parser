<?php
/**
 * Created by PhpStorm.
 * User: espe
 * Date: 01.08.2016
 * Time: 0:41
 */

// function of logging
function write_log ($log, $prefix='') {
    if(DEBUG == 1) {
        if ($prefix != '') {
            $file = "logs//" . $prefix . '_' . date('d-m-Y') . '.log';
        } else {
            $file = "logs//" . date('d-m-Y') . '.log';
        }

        file_put_contents($file, date('H:i:s') . ' ' . $log . "\r\n", FILE_APPEND);
    }
}
