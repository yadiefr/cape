#!/bin/bash

# Script untuk sinkronisasi file storage di hosting
# Jalankan script ini di direktori root project Laravel

echo "=== SMK Storage Files Sync Script ==="
echo "Syncing files from storage to public directory..."

# Set paths
PROJECT_ROOT="/home/user/project_laravel"
PUBLIC_ROOT="/home/user/public_html"

# Navigate to project directory
cd "$PROJECT_ROOT" || exit 1

echo "Current directory: $(pwd)"

# Run Laravel command to sync files
echo "Running Laravel sync command..."
php artisan storage:sync --force

# Manual copy for critical files if command fails
echo "Manual sync as backup..."

# Create directories if they don't exist
mkdir -p "$PUBLIC_ROOT/storage"
mkdir -p "$PUBLIC_ROOT/storage/settings"

# Copy settings files
if [ -d "$PROJECT_ROOT/storage/app/public/settings" ]; then
    echo "Copying settings files..."
    cp -r "$PROJECT_ROOT/storage/app/public/settings/"* "$PUBLIC_ROOT/storage/settings/" 2>/dev/null || echo "No settings files to copy"
    
    # Set proper permissions
    find "$PUBLIC_ROOT/storage" -type f -exec chmod 644 {} \;
    find "$PUBLIC_ROOT/storage" -type d -exec chmod 755 {} \;
    
    echo "Settings files copied and permissions set"
else
    echo "No settings directory found in storage"
fi

# Create/update symbolic link if possible
echo "Creating storage symlink..."
if [ -e "$PUBLIC_ROOT/storage" ] && [ ! -L "$PUBLIC_ROOT/storage" ]; then
    echo "storage exists but is not a symlink - backing up..."
    mv "$PUBLIC_ROOT/storage" "$PUBLIC_ROOT/storage_backup_$(date +%Y%m%d_%H%M%S)"
fi

# Try to create symlink
if ln -sfn "$PROJECT_ROOT/storage/app/public" "$PUBLIC_ROOT/storage" 2>/dev/null; then
    echo "✓ Storage symlink created successfully"
else
    echo "⚠ Could not create symlink - using copied files instead"
    
    # Restore backup if symlink failed and we had backed up
    if [ -d "$PUBLIC_ROOT/storage_backup_"* ]; then
        latest_backup=$(ls -t "$PUBLIC_ROOT"/storage_backup_* | head -1)
        mv "$latest_backup" "$PUBLIC_ROOT/storage"
        echo "Restored storage backup"
    fi
fi

# List files to verify
echo "=== Current storage files ==="
ls -la "$PUBLIC_ROOT/storage/settings/" 2>/dev/null || echo "No settings files found"

echo "=== Sync Complete ==="
echo "If logo still doesn't show, check:"
echo "1. File permissions (644 for files, 755 for directories)"
echo "2. Web server configuration"
echo "3. .htaccess rules"
