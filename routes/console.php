<?php

use Illuminate\Support\Facades\Schedule;

// prune telescope entries older than 168 hours (7 days)
Schedule::command('telescope:prune', ['--hours=168'])
    ->description('Deletes all Telescope entries older than 7 days.')
    ->daily();
