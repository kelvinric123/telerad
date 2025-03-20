<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Orthanc Server Configuration
    |--------------------------------------------------------------------------
    |
    | These configuration options are used to connect to the Orthanc DICOM server.
    |
    */

    'url' => env('ORTHANC_URL', 'http://localhost:8042'),
    'username' => env('ORTHANC_USERNAME', 'orthanc'),
    'password' => env('ORTHANC_PASSWORD', 'orthanc'),
    
    /*
    |--------------------------------------------------------------------------
    | OHIF Viewer Configuration
    |--------------------------------------------------------------------------
    |
    | These configuration options are used to connect to the OHIF DICOM viewer.
    |
    */

    'ohif_url' => env('OHIF_URL', 'http://localhost:3000'),
    
    /*
    |--------------------------------------------------------------------------
    | Auto Sync Configuration
    |--------------------------------------------------------------------------
    |
    | These configuration options control the synchronization between Orthanc
    | and the local database.
    |
    */

    'auto_sync' => env('ORTHANC_AUTO_SYNC', false),
    'sync_interval' => env('ORTHANC_SYNC_INTERVAL', 60), // In minutes
]; 