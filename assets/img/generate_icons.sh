#!/bin/bash
# SATU DATU SANGGUL - Icon Generation Script
# This script generates app icons using ImageMagick

# Check if ImageMagick is installed
if ! command -v convert &> /dev/null; then
    echo "ImageMagick is not installed. Please install it first:"
    echo "  Ubuntu/Debian: sudo apt-get install imagemagick"
    echo "  macOS: brew install imagemagick"
    exit 1
fi

# Create icons directory
mkdir -p $(dirname "$0")

# Colors
PRIMARY_COLOR="#0ea5e9"
SECONDARY_COLOR="#c026d3"
BG_COLOR="#ffffff"

# Create SVG icon first
cat > /tmp/datusanggul-icon.svg << 'EOF'
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
  <defs>
    <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#0ea5e9;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#c026d3;stop-opacity:1" />
    </linearGradient>
  </defs>
  <rect width="512" height="512" rx="100" fill="url(#grad)"/>
  <path d="M140 120h232c11 0 20 9 20 20v252c0 11-9 20-20 20H140c-11 0-20-9-20-20V140c0-11 9-20 20-20z" fill="white"/>
  <rect x="160" y="160" width="80" height="60" rx="10" fill="#0ea5e9"/>
  <rect x="272" y="160" width="80" height="60" rx="10" fill="#c026d3"/>
  <rect x="160" y="240" width="80" height="60" rx="10" fill="#0ea5e9"/>
  <rect x="272" y="240" width="80" height="60" rx="10" fill="#c026d3"/>
  <rect x="160" y="320" width="192" height="30" rx="10" fill="#e5e7eb"/>
  <rect x="160" y="360" width="120" height="20" rx="10" fill="#e5e7eb"/>
</svg>
EOF

# Generate icons at different sizes
SIZES=(72 96 128 144 152 192 384 512)

for SIZE in "${SIZES[@]}"
do
    echo "Generating icon-${SIZE}.png..."
    convert /tmp/datusanggul-icon.svg -resize ${SIZE}x${SIZE} $(dirname "$0")/icon-${SIZE}.png
done

# Generate favicon
echo "Generating favicon.png..."
convert /tmp/datusanggul-icon.svg -resize 32x32 $(dirname "$0")/favicon.png

# Generate favicon.ico
echo "Generating favicon.ico..."
convert /tmp/datusanggul-icon.svg -resize 16x16 $(dirname "$0")/favicon-16.png
convert /tmp/datusanggul-icon.svg -resize 32x32 $(dirname "$0")/favicon-32.png
convert $(dirname "$0")/favicon-16.png $(dirname "$0")/favicon-32.png $(dirname "$0")/favicon.ico

# Generate shortcut icons
echo "Generating shortcut icons..."
convert -size 96x96 xc:transparent \
    -fill "#0ea5e9" -draw "circle 48,48 48,10" \
    -fill white -pointsize 60 -gravity center -annotate 0 "🛏️" \
    $(dirname "$0")/shortcut-kamar.png

convert -size 96x96 xc:transparent \
    -fill "#c026d3" -draw "circle 48,48 48,10" \
    -fill white -pointsize 60 -gravity center -annotate 0 "👨‍⚕️" \
    $(dirname "$0")/shortcut-jadwal.png

# Cleanup
rm /tmp/datusanggul-icon.svg
rm $(dirname "$0")/favicon-16.png
rm $(dirname "$0")/favicon-32.png

echo "All icons generated successfully!"
echo ""
echo "Generated files:"
ls -lh $(dirname "$0")/*.png $(dirname "$0")/*.ico
