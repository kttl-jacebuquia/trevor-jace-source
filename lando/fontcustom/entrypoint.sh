#!/usr/bin/env bash

ENVIRONMENT=${2:-"development"}

compile_() {
	PACKAGE=$1

	if [ "${ENVIRONMENT}" == "development" ]; then
		PREPROCESSOR_PATH="/wp-content/${PACKAGE}s/trevor/static/icon-font/"
	else
		PREPROCESSOR_PATH="../icon-font"
	fi

	echo "Compiling ${PACKAGE} fonts"
	cd "/app/fontcustom/${PACKAGE}" || exit
	fontcustom compile -c config.yml -p "$PREPROCESSOR_PATH"
}

if [ "$1" == "help" ]; then # Do nothing on docker-compose up
	echo "<PACKAGE: all|theme|plugin> [ENVIRONMENT: development]"
elif [ "$1" == "all" ]; then
	compile_ theme
	compile_ plugin
elif [ "$1" == "theme" ]; then
	compile_ theme
elif [ "$1" == "plugin" ]; then
	compile_ plugin
else
	fontcustom "$@"
fi

exit 0
