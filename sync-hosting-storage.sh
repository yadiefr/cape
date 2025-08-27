#!/bin/bash

# Sync Storage Files for Hosting Environment
# This script copies files from Laravel project storage to public_html storage directory

# Get the directory where this script is located
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$SCRIPT_DIR"

# Define paths
PUBLIC_HTML_DIR="$(dirname "$PROJECT_DIR")/public_html"
SOURCE_STORAGE="$PROJECT_DIR/storage/app/public"
TARGET_STORAGE="$PUBLIC_HTML_DIR/storage"

echo "🔄 Starting storage sync for hosting environment..."
echo "📂 Project directory: $PROJECT_DIR"
echo "🌐 Public HTML directory: $PUBLIC_HTML_DIR"
echo "📁 Source storage: $SOURCE_STORAGE"
echo "🎯 Target storage: $TARGET_STORAGE"

# Check if directories exist
if [ ! -d "$SOURCE_STORAGE" ]; then
    echo "❌ Error: Source storage directory not found: $SOURCE_STORAGE"
    exit 1
fi

if [ ! -d "$PUBLIC_HTML_DIR" ]; then
    echo "❌ Error: Public HTML directory not found: $PUBLIC_HTML_DIR"
    exit 1
fi

# Create target storage directory if it doesn't exist
if [ ! -d "$TARGET_STORAGE" ]; then
    echo "📁 Creating target storage directory..."
    mkdir -p "$TARGET_STORAGE"
    chmod 755 "$TARGET_STORAGE"
fi

# Sync settings directory
if [ -d "$SOURCE_STORAGE/settings" ]; then
    echo "📋 Syncing settings files..."
    
    # Create settings directory in target
    mkdir -p "$TARGET_STORAGE/settings"
    chmod 755 "$TARGET_STORAGE/settings"
    
    # Copy all files from settings
    if cp -r "$SOURCE_STORAGE/settings/"* "$TARGET_STORAGE/settings/" 2>/dev/null; then
        # Set proper permissions
        find "$TARGET_STORAGE/settings" -type f -exec chmod 644 {} \;
        find "$TARGET_STORAGE/settings" -type d -exec chmod 755 {} \;
        echo "✅ Settings files synced successfully"
    else
        echo "⚠️  No settings files found to sync"
    fi
else
    echo "⚠️  Settings directory not found in source storage"
fi

# Sync profile photos directory
if [ -d "$SOURCE_STORAGE/profile_photos" ]; then
    echo "👤 Syncing profile photos..."
    
    # Create profile_photos directory in target
    mkdir -p "$TARGET_STORAGE/profile_photos"
    chmod 755 "$TARGET_STORAGE/profile_photos"
    
    # Copy all files from profile_photos
    if cp -r "$SOURCE_STORAGE/profile_photos/"* "$TARGET_STORAGE/profile_photos/" 2>/dev/null; then
        # Set proper permissions
        find "$TARGET_STORAGE/profile_photos" -type f -exec chmod 644 {} \;
        find "$TARGET_STORAGE/profile_photos" -type d -exec chmod 755 {} \;
        echo "✅ Profile photos synced successfully"
    else
        echo "⚠️  No profile photos found to sync"
    fi
else
    echo "⚠️  Profile photos directory not found in source storage"
fi

# Sync any other directories
echo "📁 Syncing other storage directories..."
for dir in "$SOURCE_STORAGE"/*; do
    if [ -d "$dir" ]; then
        dirname=$(basename "$dir")
        
        # Skip already processed directories
        if [ "$dirname" != "settings" ] && [ "$dirname" != "profile_photos" ]; then
            echo "📂 Syncing $dirname directory..."
            
            # Create directory in target
            mkdir -p "$TARGET_STORAGE/$dirname"
            chmod 755 "$TARGET_STORAGE/$dirname"
            
            # Copy files
            if cp -r "$dir/"* "$TARGET_STORAGE/$dirname/" 2>/dev/null; then
                # Set proper permissions
                find "$TARGET_STORAGE/$dirname" -type f -exec chmod 644 {} \;
                find "$TARGET_STORAGE/$dirname" -type d -exec chmod 755 {} \;
                echo "✅ $dirname directory synced successfully"
            else
                echo "⚠️  No files found in $dirname directory"
            fi
        fi
    fi
done

# Run Laravel artisan command to sync from database settings
echo "🔧 Running Laravel storage sync command..."
cd "$PROJECT_DIR"

if command -v php >/dev/null 2>&1; then
    php artisan storage:sync --force
    if [ $? -eq 0 ]; then
        echo "✅ Laravel storage sync completed successfully"
    else
        echo "❌ Laravel storage sync failed"
    fi
else
    echo "⚠️  PHP command not found, skipping Laravel artisan command"
fi

echo "🎉 Storage synchronization completed!"
echo ""
echo "💡 Usage tips:"
echo "   • Run this script after uploading new logos or profile photos"
echo "   • You can also run: php artisan storage:sync from the project directory"
echo "   • Make sure file permissions are correct (644 for files, 755 for directories)"
