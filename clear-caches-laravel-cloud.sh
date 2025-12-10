#!/bin/bash

echo "================================================"
echo "Laravel Cloud - Clear Caches After Deployment"
echo "================================================"
echo ""
echo "Run this script on Laravel Cloud after deploying"
echo "the storage route fix."
echo ""

# Clear route cache
echo "1. Clearing route cache..."
php artisan route:clear
if [ $? -eq 0 ]; then
    echo "   ✓ Route cache cleared"
else
    echo "   ✗ Failed to clear route cache"
fi

# Clear config cache
echo ""
echo "2. Clearing config cache..."
php artisan config:clear
if [ $? -eq 0 ]; then
    echo "   ✓ Config cache cleared"
else
    echo "   ✗ Failed to clear config cache"
fi

# Clear application cache
echo ""
echo "3. Clearing application cache..."
php artisan cache:clear
if [ $? -eq 0 ]; then
    echo "   ✓ Application cache cleared"
else
    echo "   ✗ Failed to clear application cache"
fi

# Clear view cache
echo ""
echo "4. Clearing view cache..."
php artisan view:clear
if [ $? -eq 0 ]; then
    echo "   ✓ View cache cleared"
else
    echo "   ✗ Failed to clear view cache"
fi

# Rebuild route cache
echo ""
echo "5. Rebuilding route cache..."
php artisan route:cache
if [ $? -eq 0 ]; then
    echo "   ✓ Route cache rebuilt"
else
    echo "   ✗ Failed to rebuild route cache"
fi

# Rebuild config cache
echo ""
echo "6. Rebuilding config cache..."
php artisan config:cache
if [ $? -eq 0 ]; then
    echo "   ✓ Config cache rebuilt"
else
    echo "   ✗ Failed to rebuild config cache"
fi

echo ""
echo "================================================"
echo "✓ Cache clearing complete!"
echo "================================================"
echo ""
echo "Next steps:"
echo "1. Test image upload on your site"
echo "2. Check if product images display correctly"
echo "3. Open browser console (F12) and verify no 404 errors"
echo ""
echo "If images still don't work:"
echo "- Wait 1-2 minutes for changes to propagate"
echo "- Hard refresh browser (Ctrl+Shift+R or Cmd+Shift+R)"
echo "- Check Laravel Cloud logs for errors"
echo ""


