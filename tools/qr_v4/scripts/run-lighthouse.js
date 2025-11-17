#!/usr/bin/env node
/**
 * Kör Lighthouse och exporterar JSON-rapport.
 */
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
import lighthouse from 'lighthouse';
import * as chromeLauncher from 'chrome-launcher';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const reportsDir = path.resolve(__dirname, '../reports/lighthouse');
const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
const outFile = path.join(reportsDir, `lighthouse-${timestamp}.json`);

const targetUrl = process.env.QR_V4_URL || 'http://localhost:5173';

fs.mkdirSync(reportsDir, { recursive: true });

async function runLighthouse() {
  console.log(`Kör Lighthouse för ${targetUrl}...`);

  const chrome = await chromeLauncher.launch({ chromeFlags: ['--headless'] });
  const options = {
    logLevel: 'info',
    output: 'json',
    onlyCategories: ['performance', 'accessibility', 'best-practices', 'seo'],
    port: chrome.port,
  };

  try {
    const runnerResult = await lighthouse(targetUrl, options);
    const report = runnerResult.lhr;

    const summary = {
      generatedAt: new Date().toISOString(),
      target: targetUrl,
      version: report.lighthouseVersion,
      fetchTime: report.fetchTime,
      categories: {
        performance: {
          score: report.categories.performance?.score ? Math.round(report.categories.performance.score * 100) : null,
          title: report.categories.performance?.title || 'Performance'
        },
        accessibility: {
          score: report.categories.accessibility?.score ? Math.round(report.categories.accessibility.score * 100) : null,
          title: report.categories.accessibility?.title || 'Accessibility'
        },
        'best-practices': {
          score: report.categories['best-practices']?.score ? Math.round(report.categories['best-practices'].score * 100) : null,
          title: report.categories['best-practices']?.title || 'Best Practices'
        },
        seo: {
          score: report.categories.seo?.score ? Math.round(report.categories.seo.score * 100) : null,
          title: report.categories.seo?.title || 'SEO'
        }
      },
      audits: {
        'first-contentful-paint': report.audits['first-contentful-paint']?.numericValue || null,
        'largest-contentful-paint': report.audits['largest-contentful-paint']?.numericValue || null,
        'total-blocking-time': report.audits['total-blocking-time']?.numericValue || null,
        'cumulative-layout-shift': report.audits['cumulative-layout-shift']?.numericValue || null,
        'speed-index': report.audits['speed-index']?.numericValue || null,
      },
      fullReport: report
    };

    fs.writeFileSync(outFile, JSON.stringify(summary, null, 2));
    console.log(`\n✅ Lighthouse rapport sparad: ${outFile}`);
    console.log(`\n📊 Sammanfattning:`);
    console.log(`   Performance: ${summary.categories.performance.score || 'N/A'}/100`);
    console.log(`   Accessibility: ${summary.categories.accessibility.score || 'N/A'}/100`);
    console.log(`   Best Practices: ${summary.categories['best-practices'].score || 'N/A'}/100`);
    console.log(`   SEO: ${summary.categories.seo.score || 'N/A'}/100`);

    await chrome.kill();
    process.exit(0);
  } catch (error) {
    console.error('❌ Lighthouse-körning misslyckades:', error.message);
    await chrome.kill();
    process.exit(1);
  }
}

runLighthouse();
