<?php

declare(strict_types=1);

/**
 * ASAI Automated Deployment Extraction Helper
 *
 * This script runs remotely on Hostinger to extract deploy.zip,
 * sync storage assets, clear bootstrap caches, and self-delete.
 *
 * Security: Token is sent via POST body, never in URL query strings.
 * Lifecycle: Self-deletes both deploy.zip and this script on completion.
 */

// Only accept POST requests to prevent token leaking in access logs
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method Not Allowed');
}

$token = $_POST['token'] ?? '';
$expectedToken = '__DEPLOY_TOKEN__';

if (empty($token) || !hash_equals($expectedToken, $token)) {
    http_response_code(403);
    die('Forbidden: Invalid Token');
}

// Locate target directory and zip archive
$targetDir = dirname(__DIR__);
$zipFile = $targetDir . '/deploy.zip';

if (!file_exists($zipFile)) {
    $zipFile = __DIR__ . '/deploy.zip';
}

if (!file_exists($zipFile)) {
    http_response_code(404);
    die('Error: deploy.zip not found on server');
}

$zip = new ZipArchive();

if ($zip->open($zipFile) !== true) {
    http_response_code(500);
    die('Error: Failed to open deploy.zip archive');
}

// Extract archive to target directory
$zip->extractTo($targetDir);
$zip->close();

// Auto-cleanup archive and extraction script
@unlink($zipFile);
@unlink(__FILE__);

// Sync public uploads from storage/app/public to public/storage if directory exists
$srcDir = $targetDir . '/storage/app/public';
$dstDir = $targetDir . '/public/storage';

if (is_dir($srcDir)) {
    if (!is_dir($dstDir)) {
        @mkdir($dstDir, 0755, true);
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($srcDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $item) {
        $subPath = $iterator->getSubPathname();
        $target = $dstDir . '/' . $subPath;

        if ($item->isDir()) {
            if (!is_dir($target)) {
                @mkdir($target, 0755, true);
            }
        } else {
            if (!is_dir(dirname($target))) {
                @mkdir(dirname($target), 0755, true);
            }

            @copy($item->getPathname(), $target);
        }
    }
}

// Clear cached bootstrap files so new configs/routes take effect immediately
foreach (glob($targetDir . '/bootstrap/cache/*.php') as $cachedFile) {
    @unlink($cachedFile);
}

echo 'DEPLOY_SUCCESS';
