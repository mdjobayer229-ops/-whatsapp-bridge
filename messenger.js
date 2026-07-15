const fs = require('fs');
const path = require('path');

const DATA_FILE = path.join(__dirname, 'data', 'queue.json');
const SEND_INTERVAL_MS = 2500;
const SEND_JITTER_MS = 2000;
const BATCH_SIZE = 3;
const BATCH_PAUSE_MS = 8000;

let queue = [];
let processing = false;

function load() {
  try {
    if (fs.existsSync(DATA_FILE)) {
      queue = JSON.parse(fs.readFileSync(DATA_FILE, 'utf8'));
    }
  } catch (_) { queue = []; }
}

function save() {
  const dir = path.dirname(DATA_FILE);
  if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
  fs.writeFileSync(DATA_FILE + '.tmp', JSON.stringify(queue));
  fs.renameSync(DATA_FILE + '.tmp', DATA_FILE);
}

function enqueue(to, text, priority = 0) {
  const item = {
    id: Date.now().toString(36) + Math.random().toString(36).slice(2, 6),
    to,
    text,
    priority,
    status: 'pending',
    account_id: null,
    created_at: new Date().toISOString(),
    sent_at: null,
    attempts: 0
  };
  queue.push(item);
  save();
  return item;
}

function dequeue(accountId) {
  const idx = queue.findIndex(q => q.status === 'pending');
  if (idx < 0) return null;
  queue[idx].status = 'claimed';
  queue[idx].account_id = accountId;
  queue[idx].claimed_at = new Date().toISOString();
  save();
  return queue[idx];
}

function markSent(id) {
  const item = queue.find(q => q.id === id);
  if (item) {
    item.status = 'sent';
    item.sent_at = new Date().toISOString();
    save();
  }
}

function markFailed(id, error) {
  const item = queue.find(q => q.id === id);
  if (item) {
    item.attempts++;
    if (item.attempts >= 3) {
      item.status = 'failed';
    } else {
      item.status = 'pending';
    }
    item.error = error;
    item.account_id = null;
    save();
  }
}

async function processQueue(sock, accountId, dailyLimit, sentToday, onMessage) {
  if (processing) return;
  processing = true;
  try {
    const pending = queue.filter(q => q.status === 'pending').sort((a, b) => b.priority - a.priority);
    if (pending.length === 0) { processing = false; return; }
    const available = Math.max(0, dailyLimit - sentToday);
    const toSend = pending.slice(0, Math.min(available, pending.length));
    if (toSend.length === 0) { processing = false; return; }
    for (let i = 0; i < toSend.length; i += BATCH_SIZE) {
      const batch = toSend.slice(i, i + BATCH_SIZE);
      const batchPromises = batch.map(async (item) => {
        try {
          const claimed = dequeue(accountId);
          if (!claimed) return;
          await sock.sendMessage(item.to, { text: item.text });
          markSent(item.id);
          if (onMessage) onMessage({ to: item.to, text: item.text, account_id: accountId, status: 'sent' });
        } catch (err) {
          markFailed(item.id, err.message);
        }
      });
      await Promise.all(batchPromises.map(p => p.catch(() => {})));
      if (i + BATCH_SIZE < toSend.length) {
        const pause = BATCH_PAUSE_MS + Math.round(Math.random() * 4000);
        await new Promise(r => setTimeout(r, pause));
      }
    }
  } finally {
    processing = false;
  }
}

function getQueueLength() {
  return queue.filter(q => q.status === 'pending').length;
}

function getQueueStats() {
  const pending = queue.filter(q => q.status === 'pending').length;
  const sent = queue.filter(q => q.status === 'sent').length;
  const failed = queue.filter(q => q.status === 'failed').length;
  return { total: queue.length, pending, sent, failed };
}

load();
module.exports = { enqueue, processQueue, getQueueLength, getQueueStats };
