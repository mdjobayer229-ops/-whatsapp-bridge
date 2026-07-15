const fs = require('fs');
const path = require('path');

const DATA_FILE = path.join(__dirname, 'data', 'accounts.json');

const SCHEDULE = [
  { day: 3, limit: 20 },
  { day: 7, limit: 100 },
  { day: 14, limit: 500 },
  { day: Infinity, limit: 2000 }
];

let accounts = {};

function load() {
  try {
    if (fs.existsSync(DATA_FILE)) {
      accounts = JSON.parse(fs.readFileSync(DATA_FILE, 'utf8'));
    }
  } catch (_) { accounts = {}; }
}

function save() {
  const dir = path.dirname(DATA_FILE);
  if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
  fs.writeFileSync(DATA_FILE + '.tmp', JSON.stringify(accounts));
  fs.renameSync(DATA_FILE + '.tmp', DATA_FILE);
}

function registerAccount(accountId, phone) {
  if (!accounts[accountId]) {
    accounts[accountId] = {
      phone,
      day_count: 1,
      start_date: new Date().toISOString(),
      current_limit: 20,
      daily_sent: {},
      last_reset_date: new Date().toISOString().slice(0, 10)
    };
    save();
  }
  return accounts[accountId];
}

function getDailyLimit(accountId) {
  if (!accounts[accountId]) return 20;
  const acc = accounts[accountId];
  for (const s of SCHEDULE) {
    if (acc.day_count <= s.day) return s.limit;
  }
  return 2000;
}

function incrementDay(accountId) {
  if (!accounts[accountId]) return;
  accounts[accountId].day_count++;
  accounts[accountId].current_limit = getDailyLimit(accountId);
  save();
}

function getSentToday(accountId) {
  if (!accounts[accountId]) return 0;
  const today = new Date().toISOString().slice(0, 10);
  if (accounts[accountId].last_reset_date !== today) {
    accounts[accountId].daily_sent = {};
    accounts[accountId].last_reset_date = today;
    save();
  }
  return accounts[accountId].daily_sent[today] || 0;
}

function incrementSent(accountId) {
  if (!accounts[accountId]) return;
  const today = new Date().toISOString().slice(0, 10);
  if (accounts[accountId].last_reset_date !== today) {
    accounts[accountId].daily_sent = {};
    accounts[accountId].last_reset_date = today;
  }
  accounts[accountId].daily_sent[today] = (accounts[accountId].daily_sent[today] || 0) + 1;
  save();
}

function getStatus(accountId) {
  if (!accounts[accountId]) return null;
  const acc = accounts[accountId];
  const sentToday = getSentToday(accountId);
  const limit = getDailyLimit(accountId);
  return {
    phone: acc.phone,
    day_count: acc.day_count,
    limit,
    sent_today: sentToday,
    remaining: Math.max(0, limit - sentToday),
    percent: limit > 0 ? Math.round((sentToday / limit) * 100) : 0
  };
}

function getAllStatus() {
  const result = {};
  for (const id of Object.keys(accounts)) {
    result[id] = getStatus(id);
  }
  return result;
}

load();
module.exports = { registerAccount, getDailyLimit, incrementDay, getSentToday, incrementSent, getStatus, getAllStatus };
