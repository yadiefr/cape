<?php
/**
 * Storage file handler for hosting environments that don't support symbolic links
 * This file serves files from storage/app/public when symlink is not available
 */

// Security check
if (!isset($_GET['file'])) {
    http_response_code(404);
    exit('File not found');
}

$file = $_GET['file'];

// Sanitize the file path
$file = ltrim($file, '/');
$file = str_replace(['../', '..\\', '..'], '', $file);

// Define the storage path
$storagePath = __DIR__ . '/../storage/app/public/' . $file;

// Check if file exists
if (!file_exists($storagePath) || !is_file($storagePath)) {
    // Log for debugging
    error_log("Storage file not found: " . $storagePath);
    http_response_code(404);
    exit('File not found');
}

// Check if file is readable
if (!is_readable($storagePath)) {
    error_log("Storage file not readable: " . $storagePath);
    http_response_code(403);
    exit('File not readable - check permissions');
}

// Security: Only allow files within storage/app/public
$realPath = realpath($storagePath);
$allowedPath = realpath(__DIR__ . '/../storage/app/public/');

if (strpos($realPath, $allowedPath) !== 0) {
    error_log("Access denied for file: " . $storagePath);
    http_response_code(403);
    exit('Access denied');
}

// Get file info
$fileInfo = pathinfo($storagePath);
$extension = strtolower($fileInfo['extension'] ?? '');

// Set appropriate content type
$mimeTypes = [
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
    'webp' => 'image/webp',
    'svg' => 'image/svg+xml',
    'pdf' => 'application/pdf',
    'doc' => 'application/msword',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'xls' => 'application/vnd.ms-excel',
    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'mp3' => 'audio/mpeg',
    'wav' => 'audio/wav',
    'mp4' => 'video/mp4',
    'txt' => 'text/plain',
];

$contentType = $mimeTypes[$extension] ?? 'application/octet-stream';

// Set headers
header('Content-Type: ' . $contentType);
header('Content-Length: ' . filesize($storagePath));
header('Cache-Control: public, max-age=31536000');
header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000));

// For images, add additional headers
if (strpos($contentType, 'image/') === 0) {
    header('Accept-Ranges: bytes');
}

// Output the file
readfile($storagePath);
exit;
