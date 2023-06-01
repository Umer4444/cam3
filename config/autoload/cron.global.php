<?php

return array(
    'cronModule' => [
        'phpPath'    => 'php',
        'scriptPath' => getcwd().'/public/',
        'jobs' => [
            [
                'command'  => 'index.php cron:schedule-alert',
                'schedule' => '* * * * *'
            ]
        ],
        // timeout in seconds for the process, defaults to 600 seconds
        'timeout' => 850
    ]
);