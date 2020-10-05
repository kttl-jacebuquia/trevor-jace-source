#!/usr/bin/env bash

# Node Cleanup
/app/scripts/cleanup-build-node.sh

# FontCustom
/app/scripts/pre-push-fontcustom.sh

# Build
echo "Building..."
if ! npm run build; then
	echo >&2 "Node build failed. Please check the logs."
	exit 1
fi

# Move
echo "Moving built files to their destinations..."
mv /app/build/plugin/js/*.js* /app/plugin/static/js/
mv /app/build/plugin/css/*.css* /app/plugin/static/css/
mv /app/build/theme/js/*.js* /app/theme/static/js/
mv /app/build/theme/css/*.css* /app/theme/static/css/
mv /app/build/runtime.js* /app/plugin/static/js/
