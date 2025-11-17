#!/usr/bin/env node
/**
 * Kör SEO-kontroller och exporterar rapport.
 */
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
import lighthouse from 'lighthouse';
import * as chromeLauncher from 'chrome-launcher';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const reportsDir = path.resolve(__dirname, '../reports/seo');
const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
const outFile = path.join(reportsDir, `seo-${timestamp}.json`);

const targetUrl = process.env.QR_V4_URL || 'http://localhost:5173';

fs.mkdirSync(reportsDir, { recursive: true });

async function runSEO() {
  console.log(`Kör SEO-kontroller för ${targetUrl}...`);

  const chrome = await chromeLauncher.launch({ chromeFlags: ['--headless'] });
  const options = {
    logLevel: 'info',
    output: 'json',
    onlyCategories: ['seo'],
    port: chrome.port,
  };

  try {
    const runnerResult = await lighthouse(targetUrl, options);
    const report = runnerResult.lhr;
    const seoCategory = report.categories.seo;

    const seoAudits = Object.entries(report.audits)
      .filter(([key, audit]) => audit.details?.type === 'table' || audit.score !== null)
      .map(([key, audit]) => ({
        id: key,
        title: audit.title,
        description: audit.description,
        score: audit.score !== null ? Math.round(audit.score * 100) : null,
        displayValue: audit.displayValue,
        scoreDisplayMode: audit.scoreDisplayMode,
        warnings: audit.warnings || []
      }))
      .filter(audit => audit.id.includes('meta') ||
                      audit.id.includes('title') ||
                      audit.id.includes('description') ||
                      audit.id.includes('viewport') ||
                      audit.id.includes('document-title') ||
                      audit.id.includes('html-has-lang') ||
                      audit.id.includes('link-text') ||
                      audit.id.includes('crawlable-links') ||
                      audit.id.includes('hreflang') ||
                      audit.id.includes('canonical') ||
                      audit.id.includes('robots-txt') ||
                      audit.id.includes('tap-targets') ||
                      audit.id.includes('font-size') ||
                      audit.id.includes('plugins') ||
                      audit.id.includes('valid-lang'));

    const summary = {
      generatedAt: new Date().toISOString(),
      target: targetUrl,
      version: report.lighthouseVersion,
      fetchTime: report.fetchTime,
      overallScore: seoCategory?.score ? Math.round(seoCategory.score * 100) : null,
      checks: seoAudits,
      passed: seoAudits.filter(a => a.score === 100).length,
      failed: seoAudits.filter(a => a.score !== null && a.score < 100).length,
      notApplicable: seoAudits.filter(a => a.score === null).length,
      fullReport: {
        category: seoCategory,
        audits: report.audits
      }
    };

    fs.writeFileSync(outFile, JSON.stringify(summary, null, 2));
    console.log(`\n✅ SEO-rapport sparad: ${outFile}`);
    console.log(`\n📊 Sammanfattning:`);
    console.log(`   Total score: ${summary.overallScore || 'N/A'}/100`);
    console.log(`   Passerade: ${summary.passed}`);
    console.log(`   Misslyckade: ${summary.failed}`);
    console.log(`   Ej tillämpliga: ${summary.notApplicable}`);

    if (summary.failed > 0) {
      console.log(`\n⚠️  Misslyckade kontroller:`);
      summary.checks
        .filter(a => a.score !== null && a.score < 100)
        .forEach(a => {
          console.log(`   - ${a.title} (${a.score}/100)`);
        });
    }

    await chrome.kill();
    process.exit(0);
  } catch (error) {
    console.error('❌ SEO-körning misslyckades:', error.message);
    await chrome.kill();
    process.exit(1);
  }
}

runSEO();
