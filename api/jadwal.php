<?php
/**
 * SATU DATU SANGGUL - Jadwal Dokter API
 * Endpoint for doctor schedule information
 *
 * Method: GET
 *
 * Response:
 * [
 *   {
 *     "nama": "dr. Andi",
 *     "spesialis": "Penyakit Dalam",
 *     "jadwal": "Senin-Jumat 08:00-14:00"
 *   },
 *   ...
 * ]
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed. Use GET.'
    ]);
    exit();
}

// Load environment variables
$envFile = __DIR__ . '/../.env';
$env = [];

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $env[trim($name)] = trim($value);
    }
}

// Database configuration
$dbHost = isset($env['DB_HOST']) ? $env['DB_HOST'] : 'localhost';
$dbName = isset($env['DB_NAME']) ? $env['DB_NAME'] : 'simrs';
$dbUser = isset($env['DB_USER']) ? $env['DB_USER'] : 'root';
$dbPass = isset($env['DB_PASS']) ? $env['DB_PASS'] : '';

$useDatabase = isset($env['USE_DATABASE']) && $env['USE_DATABASE'] === 'true';

// Try to fetch from database
$jadwalData = null;

if ($useDatabase) {
    try {
        $pdo = new PDO(
            "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4",
            $dbUser,
            $dbPass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );

        // Query to get doctor schedules
        // Adjust query based on your SIMRS database structure
        $query = "
            SELECT
                d.nama_dokter as nama,
                d.spesialis,
                j.hari,
                j.jam_mulai,
                j.jam_selesai,
                j.nama_poli
            FROM jadwal_dokter j
            JOIN dokter d ON j.kd_dokter = d.kd_dokter
            WHERE j.status = 'AKTIF'
            ORDER BY FIELD(j.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), j.jam_mulai
        ";

        $stmt = $pdo->query($query);
        $results = $stmt->fetchAll();

        $jadwalData = [];

        foreach ($results as $row) {
            $jadwal = sprintf('%s %s:00-%s:00', $row['hari'], $row['jam_mulai'], $row['jam_selesai']);

            $jadwalData[] = [
                'nama' => $row['nama'],
                'spesialis' => $row['spesialis'],
                'jadwal' => $jadwal,
                'poli' => $row['nama_poli']
            ];
        }

    } catch (PDOException $e) {
        // If database connection fails, use mock data
        error_log('Database connection failed: ' . $e->getMessage());
        $jadwalData = null;
    }
}

// If database not available, return mock data
if ($jadwalData === null) {
    $jadwalData = [
        [
            'nama' => 'dr. H. Ahmad Fauzi, Sp.PD',
            'spesialis' => 'Penyakit Dalam',
            'jadwal' => 'Senin-Kamis 08:00-14:00',
            'poli' => 'Poli Penyakit Dalam'
        ],
        [
            'nama' => 'dr. Siti Aminah, Sp.A',
            'spesialis' => 'Anak',
            'jadwal' => 'Senin-Rabu-Jumat 09:00-12:00',
            'poli' => 'Poli Anak'
        ],
        [
            'nama' => 'dr. Budi Santoso, Sp.B',
            'spesialis' => 'Bedah',
            'jadwal' => 'Selasa-Kamis-Sabtu 08:00-12:00',
            'poli' => 'Poli Bedah'
        ],
        [
            'nama' => 'dr. Ratna Dewi, Sp.OG',
            'spesialis' => 'Kandungan',
            'jadwal' => 'Senin-Selasa-Rabu-Kamis-Jumat 07:00-13:00',
            'poli' => 'Poli Kandungan'
        ],
        [
            'nama' => 'dr. Hendra Wijaya, Sp.S',
            'spesialis' => 'Saraf',
            'jadwal' => 'Senin-Jumat 10:00-14:00',
            'poli' => 'Poli Saraf'
        ],
        [
            'nama' => 'dr. Maya Kartika, Sp.JP',
            'spesialis' => 'Jantung',
            'jadwal' => 'Selasa-Kamis 08:00-12:00',
            'poli' => 'Poli Jantung'
        ],
        [
            'nama' => 'dr. Rudi Hartono, Sp.THT',
            'spesialis' => 'THT',
            'jadwal' => 'Senin-Rabu-Jumat 13:00-16:00',
            'poli' => 'Poli THT'
        ],
        [
            'nama' => 'dr. Linda Pratiwi, Sp.M',
            'spesialis' => 'Mata',
            'jadwal' => 'Senin-Selasa-Kamis-Jumat 08:00-12:00',
            'poli' => 'Poli Mata'
        ],
        [
            'nama' => 'dr. Denny Kurniawan, Sp.OT',
            'spesialis' => 'Ortopedi',
            'jadwal' => 'Senin-Rabu-Jumat 09:00-13:00',
            'poli' => 'Poli Ortopedi'
        ],
        [
            'nama' => 'dr. Ani Wulandari, Sp.KK',
            'spesialis' => 'Kulit & Kelamin',
            'jadwal' => 'Selasa-Kamis 10:00-14:00',
            'poli' => 'Poli Kulit'
        ],
        [
            'nama' => 'dr. Heru Prasetyo, Sp.P',
            'spesialis' => 'Paru',
            'jadwal' => 'Senin-Rabu-Kamis 08:00-12:00',
            'poli' => 'Poli Paru'
        ],
        [
            'nama' => 'dr. Yuliana Sari, Sp.PD',
            'spesialis' => 'Penyakit Dalam',
            'jadwal' => 'Selasa-Jumat 08:00-14:00',
            'poli' => 'Poli Penyakit Dalam'
        ]
    ];
}

echo json_encode($jadwalData);
