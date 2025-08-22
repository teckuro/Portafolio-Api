#!/bin/bash

echo "=== Verifying Deployment ==="

# Variables
BASE_URL="https://web-production-eeecb.up.railway.app"
TEST_ENDPOINTS=(
    "/api/test"
    "/api/files/projects/ecommerceplatform.svg"
    "/api/files/works/anidaslatam.svg"
    "/storage/projects/ecommerceplatform.svg"
)

# Función para probar endpoint
test_endpoint() {
    local url="$1"
    local name="$2"
    
    echo "Testing $name: $url"
    
    response=$(curl -s -o /dev/null -w "%{http_code}" "$url" --max-time 10)
    
    if [ "$response" = "200" ]; then
        echo "  ✅ $name: OK (Status: $response)"
        return 0
    else
        echo "  ❌ $name: FAILED (Status: $response)"
        return 1
    fi
}

# Probar endpoints básicos
echo "1. Testing basic endpoints..."
for endpoint in "${TEST_ENDPOINTS[@]}"; do
    test_endpoint "$BASE_URL$endpoint" "$endpoint"
done

# Probar comandos de Laravel
echo ""
echo "2. Testing Laravel commands..."
php artisan test:image-routes
php artisan test:production-urls

echo ""
echo "=== Verification Complete ==="
