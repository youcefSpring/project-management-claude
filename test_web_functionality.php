<?php

function makeWebRequest($method, $url, $data = [], $cookies = null) {
    $baseUrl = 'http://localhost:8000';
    $fullUrl = $baseUrl . $url;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fullUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookies ?? '');
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies ?? '');

    $headers = [
        'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Language: en-US,en;q=0.5',
        'Accept-Encoding: gzip, deflate',
        'Connection: keep-alive',
        'Upgrade-Insecure-Requests: 1'
    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    $error = curl_error($ch);
    curl_close($ch);

    return [
        'status' => $httpCode,
        'body' => $response,
        'error' => $error,
        'effective_url' => $effectiveUrl
    ];
}

function extractCsrfToken($html) {
    if (preg_match('/name="_token"\s+value="([^"]+)"/', $html, $matches)) {
        return $matches[1];
    }
    return null;
}

echo "=== WEB FUNCTIONALITY TESTING ===\n\n";

$cookieFile = tempnam(sys_get_temp_dir(), 'test_cookies');

// Test 1: Access login page
echo "1. TESTING LOGIN PAGE ACCESS\n";
echo "=============================\n";
$loginPage = makeWebRequest('GET', '/login', [], $cookieFile);
echo "Login page status: " . $loginPage['status'] . "\n";
echo "Login page accessible: " . ($loginPage['status'] == 200 ? 'YES' : 'NO') . "\n";

$csrfToken = extractCsrfToken($loginPage['body']);
echo "CSRF token extracted: " . ($csrfToken ? 'YES' : 'NO') . "\n\n";

// Test 2: Test Authentication
echo "2. TESTING AUTHENTICATION\n";
echo "==========================\n";

if ($csrfToken) {
    echo "Testing admin login...\n";
    $loginData = [
        'email' => 'admin@demo.com',
        'password' => 'password',
        '_token' => $csrfToken
    ];

    $loginResponse = makeWebRequest('POST', '/login', $loginData, $cookieFile);
    echo "Login response status: " . $loginResponse['status'] . "\n";
    echo "Redirected to: " . $loginResponse['effective_url'] . "\n";

    $isLoggedIn = strpos($loginResponse['effective_url'], 'dashboard') !== false;
    echo "Login successful: " . ($isLoggedIn ? 'YES' : 'NO') . "\n\n";

    if ($isLoggedIn) {
        // Test 3: Dashboard Access
        echo "3. TESTING DASHBOARD ACCESS\n";
        echo "===========================\n";
        $dashboard = makeWebRequest('GET', '/dashboard', [], $cookieFile);
        echo "Dashboard status: " . $dashboard['status'] . "\n";
        echo "Dashboard accessible: " . ($dashboard['status'] == 200 ? 'YES' : 'NO') . "\n";

        // Check if dashboard contains expected elements
        $hasSidebar = strpos($dashboard['body'], 'sidebar') !== false;
        $hasProjectsLink = strpos($dashboard['body'], 'Projects') !== false;
        $hasTasksLink = strpos($dashboard['body'], 'Tasks') !== false;
        $hasAdminLink = strpos($dashboard['body'], 'Administration') !== false;

        echo "Dashboard contains:\n";
        echo "- Sidebar: " . ($hasSidebar ? 'YES' : 'NO') . "\n";
        echo "- Projects link: " . ($hasProjectsLink ? 'YES' : 'NO') . "\n";
        echo "- Tasks link: " . ($hasTasksLink ? 'YES' : 'NO') . "\n";
        echo "- Admin link: " . ($hasAdminLink ? 'YES' : 'NO') . "\n\n";

        // Test 4: Admin Functionalities
        echo "4. TESTING ADMIN FUNCTIONALITIES\n";
        echo "=================================\n";

        // Test admin dashboard
        $adminDashboard = makeWebRequest('GET', '/admin', [], $cookieFile);
        echo "Admin dashboard status: " . $adminDashboard['status'] . "\n";
        echo "Admin dashboard accessible: " . ($adminDashboard['status'] == 200 ? 'YES' : 'NO') . "\n";

        // Test user management
        $userManagement = makeWebRequest('GET', '/users', [], $cookieFile);
        echo "User management status: " . $userManagement['status'] . "\n";
        echo "User management accessible: " . ($userManagement['status'] == 200 ? 'YES' : 'NO') . "\n";

        // Test projects management
        $projectsPage = makeWebRequest('GET', '/projects', [], $cookieFile);
        echo "Projects page status: " . $projectsPage['status'] . "\n";
        echo "Projects page accessible: " . ($projectsPage['status'] == 200 ? 'YES' : 'NO') . "\n";

        // Test tasks management
        $tasksPage = makeWebRequest('GET', '/tasks', [], $cookieFile);
        echo "Tasks page status: " . $tasksPage['status'] . "\n";
        echo "Tasks page accessible: " . ($tasksPage['status'] == 200 ? 'YES' : 'NO') . "\n";

        // Test reports (admin/manager only)
        $reportsPage = makeWebRequest('GET', '/reports', [], $cookieFile);
        echo "Reports page status: " . $reportsPage['status'] . "\n";
        echo "Reports page accessible: " . ($reportsPage['status'] == 200 ? 'YES' : 'NO') . "\n\n";

        // Test 5: Admin Settings and Cache Management
        echo "5. TESTING ADMIN SETTINGS\n";
        echo "==========================\n";

        $adminSettings = makeWebRequest('GET', '/admin/settings', [], $cookieFile);
        echo "Admin settings status: " . $adminSettings['status'] . "\n";
        echo "Admin settings accessible: " . ($adminSettings['status'] == 200 ? 'YES' : 'NO') . "\n";

        // Test cache management endpoints
        echo "\nTesting cache management:\n";

        // Get CSRF token from admin page for cache actions
        $adminPage = makeWebRequest('GET', '/admin', [], $cookieFile);
        $adminCsrfToken = extractCsrfToken($adminPage['body']);

        if ($adminCsrfToken) {
            $cacheActions = [
                'clear' => '/admin/cache/clear',
                'config' => '/admin/cache/config',
                'routes' => '/admin/cache/routes',
                'views' => '/admin/cache/views'
            ];

            foreach ($cacheActions as $action => $endpoint) {
                $cacheResponse = makeWebRequest('POST', $endpoint, ['_token' => $adminCsrfToken], $cookieFile);
                echo "- Cache {$action}: " . ($cacheResponse['status'] == 302 ? 'SUCCESS' : 'FAILED') . " (Status: {$cacheResponse['status']})\n";
            }
        }
        echo "\n";

        // Test 6: Logout
        echo "6. TESTING LOGOUT\n";
        echo "=================\n";

        // Get fresh CSRF token for logout
        $dashboardPage = makeWebRequest('GET', '/dashboard', [], $cookieFile);
        $logoutToken = extractCsrfToken($dashboardPage['body']);

        if ($logoutToken) {
            $logoutResponse = makeWebRequest('POST', '/logout', ['_token' => $logoutToken], $cookieFile);
            echo "Logout status: " . $logoutResponse['status'] . "\n";
            echo "Redirected to: " . $logoutResponse['effective_url'] . "\n";

            $isLoggedOut = strpos($logoutResponse['effective_url'], 'login') !== false;
            echo "Logout successful: " . ($isLoggedOut ? 'YES' : 'NO') . "\n\n";

            // Test access to protected page after logout
            echo "Testing dashboard access after logout:\n";
            $dashboardAfterLogout = makeWebRequest('GET', '/dashboard', [], $cookieFile);
            echo "Dashboard status after logout: " . $dashboardAfterLogout['status'] . "\n";
            echo "Redirected to: " . $dashboardAfterLogout['effective_url'] . "\n";
            $redirectedToLogin = strpos($dashboardAfterLogout['effective_url'], 'login') !== false;
            echo "Properly redirected to login: " . ($redirectedToLogin ? 'YES' : 'NO') . "\n\n";
        }
    }
} else {
    echo "Cannot test login - no CSRF token available\n\n";
}

// Test 7: Test Different User Roles
echo "7. TESTING DIFFERENT USER ROLES\n";
echo "================================\n";

// Test manager login
echo "Testing manager login:\n";
$loginPage = makeWebRequest('GET', '/login', [], $cookieFile);
$managerCsrfToken = extractCsrfToken($loginPage['body']);

if ($managerCsrfToken) {
    $managerLoginData = [
        'email' => 'manager@demo.com',
        'password' => 'password',
        '_token' => $managerCsrfToken
    ];

    $managerLogin = makeWebRequest('POST', '/login', $managerLoginData, $cookieFile);
    echo "Manager login status: " . $managerLogin['status'] . "\n";

    $managerLoggedIn = strpos($managerLogin['effective_url'], 'dashboard') !== false;
    echo "Manager login successful: " . ($managerLoggedIn ? 'YES' : 'NO') . "\n";

    if ($managerLoggedIn) {
        // Test manager access to admin features (should fail)
        $managerAdminAccess = makeWebRequest('GET', '/admin', [], $cookieFile);
        echo "Manager admin access status: " . $managerAdminAccess['status'] . " (should be 403 or redirect)\n";

        // Test manager access to user management (should fail)
        $managerUserAccess = makeWebRequest('GET', '/users', [], $cookieFile);
        echo "Manager user management access: " . ($managerUserAccess['status'] == 200 ? 'ALLOWED' : 'DENIED') . "\n";

        // Test manager access to reports (should work)
        $managerReportsAccess = makeWebRequest('GET', '/reports', [], $cookieFile);
        echo "Manager reports access: " . ($managerReportsAccess['status'] == 200 ? 'ALLOWED' : 'DENIED') . "\n";

        // Logout manager
        $managerDashboard = makeWebRequest('GET', '/dashboard', [], $cookieFile);
        $managerLogoutToken = extractCsrfToken($managerDashboard['body']);
        if ($managerLogoutToken) {
            makeWebRequest('POST', '/logout', ['_token' => $managerLogoutToken], $cookieFile);
        }
    }
}
echo "\n";

// Test 8: Public Pages Access
echo "8. TESTING PUBLIC PAGES\n";
echo "========================\n";

$publicPages = [
    '/health' => 'Health check',
    '/login' => 'Login page',
    '/register' => 'Registration page'
];

foreach ($publicPages as $url => $description) {
    $response = makeWebRequest('GET', $url, [], $cookieFile);
    echo "{$description}: " . ($response['status'] == 200 ? 'ACCESSIBLE' : 'INACCESSIBLE') . " (Status: {$response['status']})\n";
}

echo "\n";

// Test 9: Error Handling
echo "9. TESTING ERROR HANDLING\n";
echo "==========================\n";

// Test 404 page
$notFound = makeWebRequest('GET', '/nonexistent-page', [], $cookieFile);
echo "404 handling: " . ($notFound['status'] == 404 ? 'PROPER' : 'IMPROPER') . " (Status: {$notFound['status']})\n";

// Test accessing protected endpoint without authentication
$protectedAccess = makeWebRequest('GET', '/admin/dashboard', [], $cookieFile);
echo "Protected endpoint without auth: " . ($protectedAccess['status'] != 200 ? 'PROPERLY BLOCKED' : 'ACCESSIBLE') . " (Status: {$protectedAccess['status']})\n";

echo "\n";

// Clean up
unlink($cookieFile);

echo "=== WEB FUNCTIONALITY TESTING COMPLETED ===\n";