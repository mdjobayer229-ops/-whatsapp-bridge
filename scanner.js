const fs = require('fs');
const path = require('path');

const PREFIXES = ['013', '014', '015', '016', '017', '018', '019'];
const DATA_FILE = path.join(__dirname, 'data', 'scanned.json');

let scanned = [];

function load() {
  try {
    if (fs.existsSync(DATA_FILE)) {
      scanned = JSON.parse(fs.readFileSync(DATA_FILE, 'utf8'));
    }
  } catch (_) { scanned = []; }
}

function save() {
  const dir = path.dirname(DATA_FILE);
  if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
  fs.writeFileSync(DATA_FILE + '.tmp', JSON.stringify(scanned));
  fs.renameSync(DATA_FILE + '.tmp', DATA_FILE);
}

function generateBatch(count = 1000) {
  const generated = [];
  const used = new Set(scanned.map(s => s.phone));
  let attempts = 0;
  while (generated.length < count && attempts < count * 10) {
    attempts++;
    const prefix = PREFIXES[Math.floor(Math.random() * PREFIXES.length)];
    const suffix = String(Math.floor(10000000 + Math.random() * 90000000));
    const phone = '88' + prefix + suffix;
    if (!used.has(phone)) {
      used.add(phone);
      generated.push(phone);
    }
  }
  const entries = generated.map(phone => ({ phone, status: 'generated', scanned_at: null, source: 'prefix_generator' }));
  scanned = scanned.concat(entries);
  save();
  return generated;
}

function getUnscanned(limit = 500) {
  return scanned.filter(s => s.status === 'generated').slice(0, limit);
}

function markScanned(phone, isWhatsApp, jid) {
  const entry = scanned.find(s => s.phone === phone);
  if (entry) {
    entry.status = isWhatsApp ? 'whatsapp' : 'no_whatsapp';
    entry.scanned_at = new Date().toISOString();
    entry.jid = jid || null;
    save();
  }
}

function getStats() {
  const total = scanned.length;
  const whatsapp = scanned.filter(s => s.status === 'whatsapp').length;
  const noWhatsApp = scanned.filter(s => s.status === 'no_whatsapp').length;
  const pending = scanned.filter(s => s.status === 'generated').length;
  return { total, whatsapp, noWhatsApp, pending };
}

load();
module.exports = { generateBatch, getUnscanned, markScanned, getStats };
