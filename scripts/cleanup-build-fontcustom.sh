#!/usr/bin/env bash

echo "Cleaning icon-fonts artifacts..."

# Previews
rm -rf /app/source/build/icon-font

# Destination folders
rm -rf /app/source/{plugin,theme}/static/icon-font
