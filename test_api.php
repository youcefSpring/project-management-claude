<?php

require __DIR__ . '/code/vendor/autoload.php';

// Laravel Application Bootstrap
$app = require_once __DIR__ . '/code/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

echo "=== API ENDPOINTS TESTING ===\n\n";

function makeApiRequest($method, $url, $data = [], $token = null) {
    $baseUrl = 'http://localhost:8000';
    $fullUrl = $baseUrl . $url;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fullUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $headers = [
        'Content-Type: application/json',
        'Accept: application/json',
        'X-Requested-With: XMLHttpRequest'
    ];

    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    } elseif ($method === 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    } elseif ($method === 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    return [
        'status' => $httpCode,
        'body' => $response,
        'error' => $error
    ];
}

// Test 1: Health Check
echo "1. TESTING HEALTH CHECK ENDPOINT\n";
echo "=================================\n";
$healthCheck = makeApiRequest('GET', '/api/health');
echo "Status: " . $healthCheck['status'] . "\n";
echo "Response: " . $healthCheck['body'] . "\n\n";

// Test 2: Authentication Endpoints
echo "2. TESTING AUTHENTICATION\n";
echo "==========================\n";

// Test login with invalid credentials
echo "Testing login with invalid credentials:\n";
$invalidLogin = makeApiRequest('POST', '/api/login', [
    'email' => 'invalid@example.com',
    'password' => 'wrongpassword'
]);
echo "Status: " . $invalidLogin['status'] . "\n";
echo "Response: " . $invalidLogin['body'] . "\n\n";

// Test login with valid admin credentials
echo "Testing login with valid admin credentials:\n";
$adminLogin = makeApiRequest('POST', '/api/login', [
    'email' => 'admin@demo.com',
    'password' => 'password'
]);
echo "Status: " . $adminLogin['status'] . "\n";
echo "Response: " . $adminLogin['body'] . "\n";

$adminToken = null;
if ($adminLogin['status'] == 200) {
    $loginData = json_decode($adminLogin['body'], true);
    if (isset($loginData['token'])) {
        $adminToken = $loginData['token'];
        echo "✓ Admin token obtained successfully\n";
    } else {
        echo "✗ No token in response\n";
    }
} else {
    echo "✗ Admin login failed\n";
}
echo "\n";

// Test 3: Protected Endpoints (with authentication)
echo "3. TESTING PROTECTED ENDPOINTS\n";
echo "===============================\n";

if ($adminToken) {
    // Test user info endpoint
    echo "Testing /api/user endpoint:\n";
    $userInfo = makeApiRequest('GET', '/api/user', [], $adminToken);
    echo "Status: " . $userInfo['status'] . "\n";
    echo "Response: " . substr($userInfo['body'], 0, 200) . "...\n\n";

    // Test projects API
    echo "Testing /api/ajax/projects endpoint:\n";
    $projects = makeApiRequest('GET', '/api/ajax/projects', [], $adminToken);
    echo "Status: " . $projects['status'] . "\n";
    echo "Response: " . substr($projects['body'], 0, 200) . "...\n\n";

    // Test tasks API
    echo "Testing /api/ajax/tasks endpoint:\n";
    $tasks = makeApiRequest('GET', '/api/ajax/tasks', [], $adminToken);
    echo "Status: " . $tasks['status'] . "\n";
    echo "Response: " . substr($tasks['body'], 0, 200) . "...\n\n";

    // Test dashboard stats API
    echo "Testing /api/ajax/dashboard/stats endpoint:\n";
    $dashboardStats = makeApiRequest('GET', '/api/ajax/dashboard/stats', [], $adminToken);
    echo "Status: " . $dashboardStats['status'] . "\n";
    echo "Response: " . substr($dashboardStats['body'], 0, 200) . "...\n\n";

    // Test reports API (admin only)
    echo "Testing /api/ajax/reports/projects endpoint (admin only):\n";
    $reportsProjects = makeApiRequest('GET', '/api/ajax/reports/projects', [], $adminToken);
    echo "Status: " . $reportsProjects['status'] . "\n";
    echo "Response: " . substr($reportsProjects['body'], 0, 200) . "...\n\n";

} else {
    echo "Skipping protected endpoint tests - no admin token available\n\n";
}

// Test 4: Test with different user roles
echo "4. TESTING DIFFERENT USER ROLES\n";
echo "================================\n";

// Test manager login
echo "Testing manager login:\n";
$managerLogin = makeApiRequest('POST', '/api/login', [
    'email' => 'manager@demo.com',
    'password' => 'password'
]);
echo "Status: " . $managerLogin['status'] . "\n";

$managerToken = null;
if ($managerLogin['status'] == 200) {
    $loginData = json_decode($managerLogin['body'], true);
    if (isset($loginData['token'])) {
        $managerToken = $loginData['token'];
        echo "✓ Manager token obtained successfully\n";

        // Test manager access to reports
        echo "Testing manager access to reports:\n";
        $managerReports = makeApiRequest('GET', '/api/ajax/reports/projects', [], $managerToken);
        echo "Manager reports access status: " . $managerReports['status'] . "\n";
    }
} else {
    echo "✗ Manager login failed\n";
}
echo "\n";

// Test developer login
echo "Testing developer login:\n";
$developer = User::where('role', 'developer')->first();
if ($developer) {
    echo "Found developer: " . $developer->email . "\n";
    // Note: We'll use the seeded password 'password' for all users
    $developerLogin = makeApiRequest('POST', '/api/login', [
        'email' => $developer->email,
        'password' => 'password'
    ]);
    echo "Status: " . $developerLogin['status'] . "\n";

    if ($developerLogin['status'] == 200) {
        $loginData = json_decode($developerLogin['body'], true);
        if (isset($loginData['token'])) {
            $developerToken = $loginData['token'];
            echo "✓ Developer token obtained successfully\n";

            // Test developer access to admin reports (should fail)
            echo "Testing developer access to admin reports (should fail):\n";
            $devReports = makeApiRequest('GET', '/api/ajax/reports/projects', [], $developerToken);
            echo "Developer reports access status: " . $devReports['status'] . " (should be 403)\n";
        }
    }
} else {
    echo "No developer user found\n";
}
echo "\n";

// Test 5: Logout
echo "5. TESTING LOGOUT\n";
echo "=================\n";

if ($adminToken) {
    echo "Testing admin logout:\n";
    $logout = makeApiRequest('POST', '/api/logout', [], $adminToken);
    echo "Status: " . $logout['status'] . "\n";
    echo "Response: " . $logout['body'] . "\n\n";

    // Test accessing protected endpoint after logout
    echo "Testing access to protected endpoint after logout:\n";
    $afterLogout = makeApiRequest('GET', '/api/user', [], $adminToken);
    echo "Status: " . $afterLogout['status'] . " (should be 401)\n";
    echo "Response: " . substr($afterLogout['body'], 0, 100) . "\n\n";
}

// Test 6: Unauthorized Access
echo "6. TESTING UNAUTHORIZED ACCESS\n";
echo "===============================\n";

echo "Testing access to protected endpoints without token:\n";
$unauthorized = makeApiRequest('GET', '/api/user');
echo "Status: " . $unauthorized['status'] . " (should be 401)\n";
echo "Response: " . $unauthorized['body'] . "\n\n";

echo "Testing access to admin-only endpoint without proper role:\n";
$unauthorizedAdmin = makeApiRequest('GET', '/api/ajax/reports/projects');
echo "Status: " . $unauthorizedAdmin['status'] . " (should be 401)\n";
echo "Response: " . $unauthorizedAdmin['body'] . "\n\n";

echo "=== API TESTING COMPLETED ===\n";