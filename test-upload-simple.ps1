# Script simple para probar upload
$uri = "https://api-portafolio.up.railway.app/api/admin/upload"

# Crear una imagen PNG simple
$pngData = [System.Convert]::FromBase64String("iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==")
[System.IO.File]::WriteAllBytes("test-image.png", $pngData)

try {
    # Usar Invoke-RestMethod con multipart
    $form = @{
        image = Get-Item "test-image.png"
        category = "temp"
    }
    
    $response = Invoke-RestMethod -Uri $uri -Method POST -Form $form
    Write-Host "Success: $($response | ConvertTo-Json -Depth 3)"
} catch {
    Write-Host "Error: $($_.Exception.Message)"
    if ($_.Exception.Response) {
        $reader = New-Object System.IO.StreamReader($_.Exception.Response.GetResponseStream())
        $responseBody = $reader.ReadToEnd()
        Write-Host "Response: $responseBody"
    }
}
