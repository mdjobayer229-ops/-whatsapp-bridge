const fs = require('fs');
const path = require('path');

const DATA_FILE = path.join(__dirname, 'data', 'blocklist.json');

let blocklist = [];

function load() {
  try {
    if (fs.existsSync(DATA_FILE)) {
      blocklist = JSON.parse(fs.readFileSync(DATA_FILE, 'utf8'));
    }
  } catch (_) { blocklist = []; }
}

function save() {
  const dir = path.dirname(DATA_FILE);
  if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
  fs.writeFileSync(DATA_FILE + '.tmp', JSON.stringify(blocklist));
  fs.renameSync(DATA_FILE + '.tmp', DATA_FILE);
}

function isBlocked(phone) {
  const normalized = phone.replace(/[^0-9]/g, '');
  return blocklist.some(b => {
    const blocked = b.phone.replace(/[^0-9]/g, '');
    return blocked === normalized;
  });
}

function addToBlocklist(phone, reason = 'manual') {
  const normalized = phone.replace(/[^0-9]/g, '');
  if (!isBlocked(normalized)) {
    blocklist.push({ phone: normalized, reason, created_at: new Date().toISOString() });
    save();
    return true;
  }
  return false;
}

function removeFromBlocklist(phone) {
  const normalized = phone.replace(/[^0-9]/g, '');
  const before = blocklist.length;
  blocklist = blocklist.filter(b => b.phone.replace(/[^0-9]/g, '') !== normalized);
  if (blocklist.length !== before) {
    save();
    return true;
  }
  return false;
}

function getBlocklist() {
  return [...blocklist];
}

load();
module.exports = { isBlocked, addToBlocklist, removeFromBlocklist, getBlocklist };
