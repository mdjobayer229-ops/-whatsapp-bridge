const BATCH_SIZE = 50;
const BATCH_DELAY_MS = 3000;
const JITTER_MAX = 2000;

async function validateBatch(sock, phones) {
  const results = [];
  const batches = [];
  for (let i = 0; i < phones.length; i += BATCH_SIZE) {
    batches.push(phones.slice(i, i + BATCH_SIZE));
  }
  for (let i = 0; i < batches.length; i++) {
    const batch = batches[i];
    try {
      const exists = await sock.onWhatsApp(batch.map(p => p + '@s.whatsapp.net'));
      for (const phone of batch) {
        const found = exists.find(e => e.jid.startsWith(phone.slice(-11)));
        results.push({
          phone,
          isWhatsApp: !!found,
          jid: found ? found.jid : null,
          exists: found ? found.exists : false
        });
      }
      if (i < batches.length - 1) {
        const delay = BATCH_DELAY_MS + Math.round(Math.random() * JITTER_MAX);
        await new Promise(r => setTimeout(r, delay));
      }
    } catch (err) {
      for (const phone of batch) {
        results.push({ phone, isWhatsApp: false, jid: null, exists: false, error: err.message });
      }
      await new Promise(r => setTimeout(r, 10000));
    }
  }
  return results;
}

module.exports = { validateBatch };
