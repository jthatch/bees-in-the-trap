#!/usr/bin/env sh

# Process our .env and create environmental variables in the shell we spawn the bees game
dotEnvFile="./.env"
if [[ -f $dotEnvFile ]]; then
  while read line; do
    # remove leading spaces (e.g. ' # some comment' becomes '# some comment')
    line="$(echo ${line} | sed -e 's/^[[:space:]]*//')"
    # if line isn't empty or doesn't begin with a comment
    if [ ! -z "$line" ] && [[ ${line:0:1} != '#' ]]; then
      # split line into key value stripping whitespace from end/beginning
      key=$(echo $line | cut -f1 -d= | sed -e 's/[[:space:]]*$//')
      value=$(echo $line | cut -f2 -d= | sed -e 's/^[[:space:]]*//')
      export ${key}=${value}
    fi
  done < $dotEnvFile
fi

# run our game
bin/console bees:play $@