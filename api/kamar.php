<?php
/**
 * SATU DATU SANGGUL - Kamar API
 * Endpoint for checking room availability
 *
 * Method: GET
 *
 * Response:
 * {
 *   "kelas_vip": { "terisi": 5, "kosong": 2, "total": 7 },
 *   "kelas_1": { "terisi": 15, "kosong": 3, "total": 18 },
 *   "kelas_2": { "terisi": 25, "kosong": 5, "total": 30 },
 *   "kelas_3": { "terisi": 40, "kosong": 10, "total": 50 }
 * }
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
$kamarData = null;

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

        // Query to get room availability
        // Adjust query based on your SIMRS database structure
        $query = "
            SELECT
                kelas_ranap,
                COUNT(*) as total,
                SUM(CASE WHEN status_pasien = 'RAWAT' THEN 1 ELSE 0 END) as terisi,
                SUM(CASE WHEN status_pasien IS NULL OR status_pasien = 'KOSONG' THEN 1 ELSE 0 END) as kosong
            FROM kamar_inap
            GROUP BY kelas_ranap
        ";

        $stmt = $pdo->query($query);
        $results = $stmt->fetchAll();

        $kamarData = [];

        foreach ($results as $row) {
            $kelasKey = 'kelas_' . strtolower($row['kelas_ranap']);
            $kamarData[$kelasKey] = [
                'terisi' => (int)$row['terisi'],
                'kosong' => (int)$row['kosong'],
                'total' => (int)$row['total']
            ];
        }

    } catch (PDOException $e) {
        // If database connection fails, use mock data
        error_log('Database connection failed: ' . $e->getMessage());
        $kamarData = null;
    }
}

// If database not available, return mock data
if ($kamarData === null) {
    $kamarData = [
        'kelas_vip' => [
            'terisi' => 3,
            'kosong' => 2,
            'total' => 5
        ],
        'kelas_1' => [
            'terisi' => 12,
            'kosong' => 3,
            'total' => 15
        ],
        'kelas_2' => [
            'terisi' => 22,
            'kosong' => 8,
            'total' => 30
        ],
        'kelas_3' => [
            'terisi' => 45,
            'kosong' => 15,
            'total' => 60
        ],
        'vvip' => [
            'terisi' => 1,
            'kosong' => 1,
            'total' => 2
        ]
    ];
}

echo json_encode($kamarData);
