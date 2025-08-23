<?php

use Maatwebsite\Excel\Excel;

return [
    'exports' => [
        'chunk_size' => 1000,
        'temp_path' => sys_get_temp_dir(),
        'csv' => [
            'delimiter' => ',',
            'enclosure' => '"',
            'escape_character' => '\\',
            'contiguous' => false,
            'input_encoding' => 'UTF-8',
            'output_encoding' => 'UTF-8',
        ],
        'properties' => [
            'creator' => 'Apotek POS System',
            'lastModifiedBy' => 'Apotek POS System',
            'title' => 'Laporan Apotek POS',
            'description' => 'Laporan yang dibuat oleh sistem Apotek POS',
            'subject' => 'Laporan',
            'keywords' => 'laporan,apotek,pos',
            'category' => 'Laporan',
            'manager' => 'Manager',
            'company' => 'Apotek POS',
        ],
    ],

    'imports' => [
        'read_only' => true,
        'heading_type' => 'slug',
        'csv' => [
            'delimiter' => ',',
            'enclosure' => '"',
            'escape_character' => '\\',
            'contiguous' => false,
            'input_encoding' => 'UTF-8',
            'output_encoding' => 'UTF-8',
        ],
    ],

    'extension_detector' => [
        'xlsx' => Excel::XLSX,
        'xlsm' => Excel::XLSX,
        'xltx' => Excel::XLSX,
        'xltm' => Excel::XLSX,
        'xls' => Excel::XLS,
        'xlt' => Excel::XLS,
        'ods' => Excel::ODS,
        'ots' => Excel::ODS,
        'slk' => Excel::SLK,
        'xml' => Excel::XML,
        'gnumeric' => Excel::GNUMERIC,
        'htm' => Excel::HTML,
        'html' => Excel::HTML,
        'csv' => Excel::CSV,
        'tsv' => Excel::TSV,
        'pdf' => Excel::MPDF,
    ],

    'value_binder' => [
        'default' => Maatwebsite\Excel\DefaultValueBinder::class,
    ],

    'cache' => [
        'driver' => 'memory',
        'batch' => [
            'memory_limit' => 60000,
        ],
        'illuminate' => [
            'store' => null,
        ],
    ],

    'transactions' => [
        'handler' => 'db',
    ],

    'temporary_files' => [
        'local_path' => storage_path('app/laravel-excel'),
        'remote_disk' => null,
        'cleanup_remote_file' => true,
    ],
];
