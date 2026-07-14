<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Jenis Izin
    |--------------------------------------------------------------------------
    */
    'jenis_izin' => [
        'dinas' => 'Dinas',
        'pribadi' => 'Pribadi',
    ],

    'kendaraan_options' => [
        'Kendaraan Dinas',
        'Kendaraan Pribadi',
        'Tanpa Kendaraan',
        'Ojek Online / Taksi',
    ],

    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    | Hanya status berikut yang boleh dipakai di seluruh modul SIK.
    */
    'statuses' => [
        'pending_approval' => 'Pending Approval',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'sedang_keluar' => 'Sedang Keluar',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ],

    'status_badges' => [
        'pending_approval' => 'warning',
        'approved' => 'success',
        'rejected' => 'danger',
        'sedang_keluar' => 'info',
        'completed' => 'secondary',
        'cancelled' => 'dark',
    ],

    'validation_messages' => [
        'completed' => 'Surat Izin Keluar sudah selesai digunakan.',
        'pending_approval' => 'Surat Izin Keluar belum disetujui.',
        'rejected' => 'Surat Izin Keluar ditolak.',
        'cancelled' => 'Surat Izin Keluar telah dibatalkan.',
        'not_found' => 'QR Code tidak valid.',
    ],

    'per_page' => 15,
];
