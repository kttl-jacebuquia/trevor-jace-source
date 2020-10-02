#!/usr/bin/env bash

# Cleanup
/app/scripts/cleanup-build-fontcustom.sh

# Production build
/app/lando/fontcustom/entrypoint.sh all production
