const fs = require('fs');
const path = require('path');

const DATA_FILE = path.join(__dirname, 'data', 'contacts.json');

let contacts = [];

function load() {
  try {
    if (fs.existsSync(DATA_FILE)) {
      contacts = JSON.parse(fs.readFileSync(DATA_FILE, 'utf8'));
    }
  } catch (_) { contacts = []; }
}

function save() {
  const dir = path.dirname(DATA_FILE);
  if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
  fs.writeFileSync(DATA_FILE + '.tmp', JSON.stringify(contacts));
  fs.renameSync(DATA_FILE + '.tmp', DATA_FILE);
}

function addOrUpdate(phone, data = {}) {
  const normalized = phone.replace(/[^0-9]/g, '');
  const idx = contacts.findIndex(c => c.phone === normalized);
  const now = new Date().toISOString();
  if (idx >= 0) {
    contacts[idx] = { ...contacts[idx], ...data, updated_at: now };
  } else {
    contacts.push({ phone: normalized, status: 'pending', priority_score: 0, name_guess: null, gender_guess: null, age_group_guess: null, source: data.source || 'unknown', created_at: now, updated_at: now, contacted_at: null, converted_at: null, notes: '', ...data });
  }
  save();
  return getContact(normalized);
}

function getContact(phone) {
  const normalized = phone.replace(/[^0-9]/g, '');
  return contacts.find(c => c.phone === normalized) || null;
}

function getContactsByStatus(status, limit = 100) {
  return contacts.filter(c => c.status === status).slice(0, limit);
}

function getPendingContacts(limit = 500) {
  return contacts.filter(c => c.status === 'pending').sort((a, b) => b.priority_score - a.priority_score).slice(0, limit);
}

function markContacted(phone) {
  return addOrUpdate(phone, { status: 'contacted', contacted_at: new Date().toISOString() });
}

function markReplied(phone) {
  return addOrUpdate(phone, { status: 'replied' });
}

function markConverted(phone) {
  return addOrUpdate(phone, { status: 'converted', converted_at: new Date().toISOString() });
}

function markBlocked(phone, reason) {
  return addOrUpdate(phone, { status: 'blocked', notes: reason });
}

function calculatePriority(phone, nameGuess, genderGuess, ageGroupGuess) {
  let score = 0;
  if (ageGroupGuess === '15-20') score += 30;
  else if (ageGroupGuess === '21-25') score += 25;
  else if (ageGroupGuess === '26-30') score += 20;
  else if (ageGroupGuess === '30+') score += 10;
  if (genderGuess === 'female') score += 25;
  else if (genderGuess === 'male') score += 15;
  const contact = getContact(phone);
  if (contact) {
    addOrUpdate(phone, { priority_score: score, name_guess: nameGuess, gender_guess: genderGuess, age_group_guess: ageGroupGuess });
  }
  return score;
}

function getStats() {
  const total = contacts.length;
  const pending = contacts.filter(c => c.status === 'pending').length;
  const contacted = contacts.filter(c => c.status === 'contacted').length;
  const replied = contacts.filter(c => c.status === 'replied').length;
  const converted = contacts.filter(c => c.status === 'converted').length;
  const blocked = contacts.filter(c => c.status === 'blocked').length;
  const highPriority = contacts.filter(c => c.priority_score >= 50).length;
  const female = contacts.filter(c => c.gender_guess === 'female').length;
  const male = contacts.filter(c => c.gender_guess === 'male').length;
  return { total, pending, contacted, replied, converted, blocked, highPriority, female, male };
}

load();
module.exports = { addOrUpdate, getContact, getContactsByStatus, getPendingContacts, markContacted, markReplied, markConverted, markBlocked, calculatePriority, getStats };
