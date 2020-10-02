#!/usr/bin/env bash

echo "Cleaning the node artifacts..."

# Build dirs
rm -rf /app/build/{plugin,theme}/{css,js}

# Destination dirs
rm -rf /app/{plugin,theme}/static/{css,js}

# Create new destination dirs
bash -c 'mkdir -p /app/{plugin,theme}/static/{css,js}'
