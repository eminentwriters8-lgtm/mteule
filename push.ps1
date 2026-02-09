# Quick Push Helper
Write-Host "MTEULE Git Push Helper" -ForegroundColor Cyan
Write-Host "======================" -ForegroundColor White

# Get current branch
$branch = git branch --show-current
Write-Host "Current branch: $branch" -ForegroundColor Yellow

# Check for changes
$status = git status --porcelain
if (-not $status) {
    Write-Host "No changes to commit." -ForegroundColor Yellow
    exit
}

Write-Host "`nChanges detected:" -ForegroundColor Green
git status

# Ask for commit message
$defaultMsg = "Update: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')"
Write-Host "`nEnter commit message (default: $defaultMsg):" -ForegroundColor Cyan
$commitMsg = Read-Host
if ([string]::IsNullOrWhiteSpace($commitMsg)) {
    $commitMsg = $defaultMsg
}

# Add and commit
git add .
git commit -m $commitMsg

# Push
Write-Host "`nPushing to GitHub..." -ForegroundColor Green
if ($branch -eq "master") {
    git push origin master
} else {
    git push origin main
}

Write-Host "`n✅ Done! Changes pushed to GitHub." -ForegroundColor Green

# Show repository URL
Write-Host "`nGitHub Repository:" -ForegroundColor Cyan
Write-Host "https://github.com/eminentwriters8-lgtm/mteule" -ForegroundColor White
