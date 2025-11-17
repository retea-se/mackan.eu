#!/usr/bin/env node
import fs from 'fs';
import path from 'path';

const logsDir = path.resolve('tools/qr_v4/logs');
const runtimeLog = path.join(logsDir, 'runtime.log');

if (!fs.existsSync(runtimeLog)) {
  console.log('Ingen runtime.log att rotera.');
  process.exit(0);
}

const stamp = new Date().toISOString().split('T')[0];
const target = path.join(logsDir, `runtime-${stamp}.log`);

fs.copyFileSync(runtimeLog, target);
fs.writeFileSync(runtimeLog, '');

console.log(`Logg roterad till ${target}`);

