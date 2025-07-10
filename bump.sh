#!/bin/bash

# bump.sh
# Usage: ./bump.sh 1.2.3

NEW_VERSION=$1

if [[ -z "$NEW_VERSION" ]]; then
  echo "Usage: ./bump.sh <new-version>"
  exit 1
fi

# Update composer.json version
jq ".version = \"$NEW_VERSION\"" composer.json > composer.tmp.json && mv composer.tmp.json composer.json

# Commit and tag
git add composer.json
git commit -m "chore: release v$NEW_VERSION"
git tag -a "v$NEW_VERSION" -m "Release v$NEW_VERSION"
git push origin main --tags
