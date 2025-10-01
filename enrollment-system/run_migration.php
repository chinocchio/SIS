<?php

/**
 * Simple script to run the documents table migration
 * Run this on the server: php run_migration.php
 */

// Bootstrap CodeIgniter
require_once __DIR__ . '/vendor/autoload.php';

$app = \Config\Services::codeigniter();
$app->initialize();

try {
    echo "🔄 Running documents table migration...\n";
    
    $migrate = \Config\Services::migrations();
    $migrate->setNamespace('App');
    
    // Run the specific migration
    $result = $migrate->version('2025-09-26-000001');
    
    if ($result === false) {
        echo "❌ Migration failed!\n";
        exit(1);
    }
    
    echo "✅ Migration completed successfully!\n";
    echo "📋 Added missing fields to documents table:\n";
    echo "   - original_filename (VARCHAR 255)\n";
    echo "   - file_size (INT 11)\n";
    echo "   - description (TEXT)\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
