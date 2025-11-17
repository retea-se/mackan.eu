#!/usr/bin/env node
import { spawnSync } from 'child_process';
import fs from 'fs';
import path from 'path';

const reportsDir = path.resolve('tools/qr_v4/reports/smoke');
fs.mkdirSync(reportsDir, { recursive: true });

const result = spawnSync('npx', ['playwright', 'test', 'tests/smoke', '--config=playwright.config.ts'], {
  cwd: path.resolve('tools/qr_v4'),
  stdio: 'inherit'
});

const payload = {
  generatedAt: new Date().toISOString(),
  status: result.status === 0 ? 'passed' : 'failed',
};

const outFile = path.join(reportsDir, `smoke-${new Date().toISOString().replace(/[:.]/g, '-')}.json`);
fs.writeFileSync(outFile, JSON.stringify(payload, null, 2));

process.exit(result.status ?? 1);

