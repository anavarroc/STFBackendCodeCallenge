#!/bin/sh
set -eu
echo "Installing git hooks..."
rm -rf .git/hooks
ln -s ../git-hooks .git/hooks
echo "Git hooks installation finished"
