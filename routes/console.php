<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('appointments:send-reminders')
    ->everyThirtyMinutes()
    ->withoutOverlapping()
    ->onOneServer();
