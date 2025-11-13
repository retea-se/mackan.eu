# PowerShell-skript för att skapa GitHub issues
# Användning: .\create-issues.ps1

$repo = "tempdump/mackan-eu"
$issuesPath = "github-issues"

# Lista över issues att skapa
$issues = @(
    @{
        File = "01-sakerhet-ta-bort-eval.md"
        Title = "🛡️ SÄKERHET: Ta bort eval() från converter/utilities.js"
        Labels = @("bug", "security")
    },
    @{
        File = "02-sakerhet-input-validering.md"
        Title = "🛡️ SÄKERHET: Lägg till input-validering för verktyg med POST-data"
        Labels = @("bug", "security")
    },
    @{
        File = "03-seo-meta-description.md"
        Title = "🔍 SEO: Lägg till metaDescription för verktyg som saknar det"
        Labels = @("enhancement", "seo")
    },
    @{
        File = "04-seo-json-ld.md"
        Title = "🔍 SEO: Lägg till strukturerad data (JSON-LD) för alla verktyg"
        Labels = @("enhancement", "seo")
    },
    @{
        File = "05-ux-felhantering-toast.md"
        Title = "🎨 UX: Standardisera felhantering - Ersätt alert() med toast"
        Labels = @("enhancement", "ux")
    },
    @{
        File = "06-ux-loading-indikatorer.md"
        Title = "🎨 UX: Lägg till loading-indikatorer för asynkrona operationer"
        Labels = @("enhancement", "ux")
    },
    @{
        File = "07-kodkvalitet-bem-struktur.md"
        Title = "🔧 KODKVALITET: Migrera gamla verktyg till BEM-struktur"
        Labels = @("enhancement", "refactoring")
    },
    @{
        File = "08-kodkvalitet-gemensam-js.md"
        Title = "🔧 KODKVALITET: Skapa gemensam JavaScript-bas för vanliga funktioner"
        Labels = @("enhancement", "refactoring")
    }
)

# Kontrollera att repositoryt finns
Write-Host "Kontrollerar repositoryt $repo..." -ForegroundColor Yellow
try {
    gh repo view $repo --json name 2>&1 | Out-Null
    if ($LASTEXITCODE -ne 0) {
        Write-Host "❌ Repositoryt $repo finns inte eller du har inte åtkomst till det." -ForegroundColor Red
        Write-Host "Kontrollera att repositoryt finns och att du har rätt åtkomst." -ForegroundColor Yellow
        exit 1
    }
} catch {
    Write-Host "❌ Kunde inte kontrollera repositoryt: $_" -ForegroundColor Red
    exit 1
}

Write-Host "✅ Repositoryt finns!" -ForegroundColor Green
Write-Host ""

# Skapa issues
foreach ($issue in $issues) {
    $filePath = Join-Path $issuesPath $issue.File

    if (-not (Test-Path $filePath)) {
        Write-Host "❌ Filen $filePath finns inte!" -ForegroundColor Red
        continue
    }

    Write-Host "Skapar issue: $($issue.Title)..." -ForegroundColor Cyan

    # Läs issue-body från fil
    $body = Get-Content $filePath -Raw

    # Bygg label-argument
    $labelArgs = @()
    foreach ($label in $issue.Labels) {
        $labelArgs += "-l"
        $labelArgs += $label
    }

    # Skapa issue via GitHub CLI
    try {
        $result = & gh issue create --repo $repo --title $issue.Title --body $body $labelArgs 2>&1
        if ($LASTEXITCODE -eq 0) {
            Write-Host "✅ Issue skapad: $($issue.Title)" -ForegroundColor Green
            if ($result) {
                Write-Host "   URL: $result" -ForegroundColor Gray
            }
        } else {
            Write-Host "❌ Kunde inte skapa issue: $($issue.Title)" -ForegroundColor Red
            Write-Host "   Fel: $result" -ForegroundColor Yellow
        }
    } catch {
        Write-Host "❌ Fel vid skapande av issue: $_" -ForegroundColor Red
    }

    Write-Host ""
}

Write-Host "Klart! Alla issues har skapats." -ForegroundColor Green

