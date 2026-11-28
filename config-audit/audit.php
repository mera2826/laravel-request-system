
<?php

/*
|--------------------------------------------------------------------------
| Laboratory 9 - Laravel Configuration Audit
|--------------------------------------------------------------------------
| Driver: Althea Mera M. Lascota
| Reviewer: Michaela Pauline Deticio
|
| Checks effective Laravel configuration against a nonsecret baseline.
| External infrastructure checks must be supported by separate evidence.
| Secret values are never printed.
*/

$basePath = dirname(__DIR__);
$baselinePath = __DIR__ . '/baseline.example';

function reportCheck(string $name, string $status, string $details): void
{
    printf("[%s] %s - %s\n", $status, $name, $details);
}

if (! file_exists($baselinePath)) {
    fwrite(STDERR, "[FAIL] Baseline - baseline.example was not found.\n");
    exit(1);
}

$baseline = parse_ini_file($baselinePath, true, INI_SCANNER_RAW);

if ($baseline === false) {
    fwrite(STDERR, "[FAIL] Baseline - could not be parsed.\n");
    exit(1);
}

if (
    ! file_exists($basePath . '/bootstrap/app.php') ||
    ! file_exists($basePath . '/vendor/autoload.php')
) {
    fwrite(STDERR, "[FAIL] Laravel bootstrap - application files were not found.\n");
    exit(1);
}

require $basePath . '/vendor/autoload.php';

$app = require $basePath . '/bootstrap/app.php';

$kernel = $app->make(
    Illuminate\Contracts\Console\Kernel::class
);

$kernel->bootstrap();

$failed = 0;
$passed = 0;
$unverified = 0;

echo "LABORATORY 9 - CONFIGURATION AUDIT\n";
echo "Laravel version: " . $app->version() . "\n";
echo "PHP version: " . PHP_VERSION . "\n";
echo "Target: Dell disposable Lab 9 clone\n";
echo "Secrets are not displayed.\n\n";

// Effective Laravel environment.
$expectedEnvironment =
    $baseline['environment']['expected_app_env'] ?? null;

$actualEnvironment = config('app.env');

if ($expectedEnvironment === null) {
    reportCheck('Application environment', 'FAIL', 'Baseline value is missing.');
    $failed++;
} elseif ($actualEnvironment === $expectedEnvironment) {
    reportCheck('Application environment', 'PASS', 'Effective environment matches baseline.');
    $passed++;
} else {
    reportCheck('Application environment', 'FAIL', 'Effective environment does not match baseline.');
    $failed++;
}

// Effective Laravel debug setting.
$expectedDebug = filter_var(
    $baseline['environment']['expected_app_debug'] ?? null,
    FILTER_VALIDATE_BOOLEAN,
    FILTER_NULL_ON_FAILURE
);

$actualDebug = (bool) config('app.debug');

if ($expectedDebug === null) {
    reportCheck('Application debug mode', 'FAIL', 'Baseline value is missing or invalid.');
    $failed++;
} elseif ($actualDebug === $expectedDebug) {
    reportCheck('Application debug mode', 'PASS', 'Effective debug setting matches baseline.');
    $passed++;
} else {
    reportCheck('Application debug mode', 'FAIL', 'Effective debug setting does not match baseline.');
    $failed++;
}

// Application key presence only; never print its value.
$keyPresent = is_string(config('app.key')) && config('app.key') !== '';

if ($keyPresent) {
    reportCheck('Application key', 'PASS', 'Application key is configured; value withheld.');
    $passed++;
} else {
    reportCheck('Application key', 'FAIL', 'Application key is missing.');
    $failed++;
}

// Validate important nonsecret baseline entries.
$requiredBaseline = [
    'document_root' => 'expected_document_root',
    'writable_paths' => 'expected_writable_paths',
    'service_identity' => 'expected_application_user',
    'database_access' => 'expected_database_access',
    'exposed_services' => 'expected_web_binding',
    'tls' => 'expected_tls_configuration',
];

foreach ($requiredBaseline as $section => $key) {
    $value = $baseline[$section][$key] ?? null;

    if ($value === null || trim((string) $value) === '') {
        reportCheck(
            "Baseline entry: $section.$key",
            'FAIL',
            'Required baseline entry is missing.'
        );
        $failed++;
    } else {
        reportCheck(
            "Baseline entry: $section.$key",
            'PASS',
            'Required nonsecret baseline entry is present.'
        );
        $passed++;
    }
}

// Infrastructure checks require separate container/host evidence.
$externalChecks = [
    'Document root' => 'See sanitized Nginx configuration evidence.',
    'Writable path permissions' => 'See www-data write-test and ownership evidence.',
    'Service identities' => 'See Docker process identity evidence.',
    'Container security settings' => 'See Docker inspect evidence; writable root filesystem requires hardening review.',
    'Database network exposure' => 'See Docker port and internal network evidence.',
    'Database connectivity' => 'See Laravel db:show evidence; migration table is absent in the disposable clone.',
    'TLS configuration' => 'No SSL directives found; external HTTPS deployment remains unverified.',
    'Patch responsibilities' => 'Requires confirmation of responsible maintainers.',
    'Secrets handling and access' => 'Git exclusion is verified; access control, rotation, and revocation need documentation.',
];

foreach ($externalChecks as $name => $details) {
    reportCheck($name, 'UNVERIFIED', $details);
    $unverified++;
}

echo "\nAUDIT SUMMARY\n";
echo "PASS: $passed\n";
echo "FAIL: $failed\n";
echo "UNVERIFIED: $unverified\n";

if ($failed > 0) {
    echo "AUDIT RESULT: UNSUCCESSFUL - required checks failed.\n";
    exit(1);
}

echo "AUDIT RESULT: AUTOMATED BASELINE CHECKS PASSED; external checks remain unverified.\n";
exit(0);
