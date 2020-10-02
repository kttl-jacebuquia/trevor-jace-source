#!/usr/bin/env bash

# Cleanup
/app/scripts/cleanup-build-fontcustom.sh

# Development build
/app/lando/fontcustom/entrypoint.sh all development
