$extensions = @("*.php", "*.js", "*.css", "*.blade.php")
$directories = @("app", "resources", "routes", "config", "database", "bootstrap")

# Function to remove comments from a file
function Remove-Comments {
    param (
        [string]$filePath
    )

    $content = Get-Content -Path $filePath -Raw
    $extension = [System.IO.Path]::GetExtension($filePath)
    $filename = [System.IO.Path]::GetFileName($filePath)
    
    Write-Host "Processing $filePath"
    
    # Skip vendor files and compiled views
    if ($filePath -like "*vendor*" -or $filePath -like "*storage/framework/views*") {
        Write-Host "  Skipping vendor/compiled file"
        return
    }
    
    # Handle different file types
    if ($extension -eq ".php" -or $extension -eq ".blade.php") {
        # Remove PHP-style comments (// and /* */)
        $content = $content -replace '//.*?(\r?\n|$)', '$1' # Remove single line comments
        $content = $content -replace '/\*[\s\S]*?\*/', '' # Remove multi-line comments
        
        # Remove HTML comments in blade files
        if ($extension -eq ".blade.php") {
            $content = $content -replace '<!--[\s\S]*?-->', ''
        }
    }
    elseif ($extension -eq ".js") {
        # Remove JS comments (// and /* */)
        $content = $content -replace '//.*?(\r?\n|$)', '$1'
        $content = $content -replace '/\*[\s\S]*?\*/', ''
    }
    elseif ($extension -eq ".css") {
        # Remove CSS comments (/* */)
        $content = $content -replace '/\*[\s\S]*?\*/', ''
    }
    
    # Write the modified content back to the file
    Set-Content -Path $filePath -Value $content
}

# Process each directory
foreach ($dir in $directories) {
    if (Test-Path $dir) {
        Write-Host "Scanning directory: $dir"
        foreach ($ext in $extensions) {
            Get-ChildItem -Path $dir -Filter $ext -Recurse | ForEach-Object {
                Remove-Comments -filePath $_.FullName
            }
        }
    }
    else {
        Write-Host "Directory not found: $dir"
    }
}

# Also process files in the root directory
foreach ($ext in $extensions) {
    Get-ChildItem -Path "." -Filter $ext -File | ForEach-Object {
        Remove-Comments -filePath $_.FullName
    }
}

Write-Host "Comment removal completed!" 