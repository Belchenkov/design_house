<?php

Route::get('/', function() {
    return response()->json([
        'message' => 'DesignHouse API...'
    ], 200);
});
