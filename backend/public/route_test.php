<?php
require_once __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

// Bootstrap the application
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get the URL generator
$url = app('url');

// Generate the admin login URL
$adminLoginUrl = $url->route('admin.login');
echo "Generated admin login URL: " . $adminLoginUrl . "\n";

// Also test the direct URL
echo "Direct URL test: " . url('/admin/login') . "\n";
?>