<?php
return array(
    //the directory of the reports
    'reportDir' => 'reports',

    //extensions to report types
    //any file extension not in this array will be ignored
    'default_file_extension_mapping' => array(
        'sql'=>'Mysql',
        'js'=>'Mongo',
    ),

    //this enables different types of output formats, Only CSV is implemented
    'report_formats' => array(
        'csv'=>'CSV',
        'xlsx'=>'Download Excel 2007',
        'text'=>'Text',
        'json'=>'JSON',
        'sql'=>'SQL INSERT command',
    ),

    //this defines the database environments
    //the keys are the environment names (e.g. "dev", "production")
    //the values are arrays that contain connection info
    'environments' => array(
        'main'=>array(
            // Supports mysql database
            'mysql'=>array(
                'dsn'=>'mysql:host=127.0.0.1;port=3307;dbname=reporting',
                'user'=>'root',
                'pass'=>'root',
                ),

            // Supports MongoDB, Not implemented
            'mongo'=>array(
                'host'=>'localhost',
                'port'=>'27017'
            ),
        ),
    ),
);
?>
