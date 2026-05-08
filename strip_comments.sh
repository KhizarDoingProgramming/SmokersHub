#!/usr/bin/env bash
# strip_comments.sh - Remove // and /* */ comments from PHP source files in the project.
# Usage: chmod +x strip_comments.sh && ./strip_comments.sh

# Find all .php files under the project directory (excluding vendor if present)
find . -type f -name "*.php" ! -path "*/vendor/*" | while read -r file; do
    echo "Processing $file"
    # Create a temporary file
    tmp=$(mktemp)
    # Remove single-line // comments (but not URLs) and block comments /* */
    # First, remove block comments
    sed -e ':a' -e 's@/\*[^*]*\*+\([^/*][^*]*\*+\)*/@@g;ta' "$file" > "$tmp"
    # Then, remove // comments that are not preceded by : (to keep http://)
    sed -e 's@\([^:\]\)//.*$@\1@' "$tmp" > "$file"
    rm -f "$tmp"
    # Trim trailing whitespace on each line
    sed -i 's/[[:space:]]*$//' "$file"
done
echo "All PHP files have been cleaned of comments."
