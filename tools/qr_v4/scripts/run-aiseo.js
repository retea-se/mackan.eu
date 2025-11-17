#!/usr/bin/env node
import fs from 'fs';
import path from 'path';

const reportsDir = path.resolve('tools/qr_v4/reports/aiseo');
const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
const outFile = path.join(reportsDir, `aiseo-${timestamp}.json`);

fs.mkdirSync(reportsDir, { recursive: true });

const payload = {
  generatedAt: new Date().toISOString(),
  target: process.env.QR_V4_URL || 'http://localhost:5173',
  verdict: 'pending-implementation',
  note: 'Koppla mot AISEO API och ersätt denna stub med riktiga data.'
};

fs.writeFileSync(outFile, JSON.stringify(payload, null, 2));
console.log(`AISEO-rapport sparad: ${outFile}`);

