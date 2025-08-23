# Script para probar el upload de imágenes
$uri = "https://api-portafolio.up.railway.app/api/admin/upload"

# Crear una imagen PNG simple usando PowerShell
$pngData = [System.Convert]::FromBase64String("iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==")
[System.IO.File]::WriteAllBytes("test-image.png", $pngData)

$imagePath = "test-image.png"

# Crear el formulario multipart
$boundary = [System.Guid]::NewGuid().ToString()
$LF = "`r`n"

$bodyLines = (
    "--$boundary",
    "Content-Disposition: form-data; name=`"category`"",
    "",
    "temp",
    "--$boundary",
    "Content-Disposition: form-data; name=`"image`"; filename=`"$imagePath`"",
    "Content-Type: image/png",
    "",
    [System.IO.File]::ReadAllBytes($imagePath),
    "--$boundary--"
) -join $LF

try {
    $response = Invoke-WebRequest -Uri $uri -Method POST -Body $bodyLines -ContentType "multipart/form-data; boundary=$boundary"
    Write-Host "Status: $($response.StatusCode)"
    Write-Host "Response: $($response.Content)"
} catch {
    Write-Host "Error: $($_.Exception.Message)"
    if ($_.Exception.Response) {
        $reader = New-Object System.IO.StreamReader($_.Exception.Response.GetResponseStream())
        $responseBody = $reader.ReadToEnd()
        Write-Host "Response Body: $responseBody"
    }
}
