<div class="wrap ai-sr-wrap">
  <h1>AI Smart Router</h1>
  <p style="color:#64748b;margin-top:-8px;margin-bottom:16px">Settings &mdash; 26 free models &middot; 5 API keys &middot; Auto-failover &middot; 24/7</p>

  <div class="ai-sr-status-bar">
    <span>Active: <strong><?php echo esc_html(($models[$current['model']] ?? [])['name'] ?? 'None') ?></strong> on Key #<?php echo ($current['key'] ?? 0) + 1 ?></span>
    <span>Responses: <strong><?php echo intval($stats['total_responses'] ?? 0) ?></strong> total &middot; <strong><?php echo intval($stats['today_responses'] ?? 0) ?></strong> today</span>
    <span>Exhausted: <strong><?php echo array_sum(array_map('count', is_array($exhausted) ? $exhausted : [])) ?></strong> models</span>
  </div>

  <nav class="ai-sr-tabs">
    <a href="?page=ai-smart-router&tab=dashboard" class="<?php echo $tab==='dashboard'?'active':'' ?>">Dashboard</a>
    <a href="?page=ai-smart-router&tab=keys" class="<?php echo $tab==='keys'?'active':'' ?>">API Keys</a>
    <a href="?page=ai-smart-router&tab=models" class="<?php echo $tab==='models'?'active':'' ?>">Models</a>
    <a href="?page=ai-smart-router&tab=integration" class="<?php echo $tab==='integration'?'active':'' ?>">Integration</a>
    <a href="?page=ai-smart-router&tab=pages" class="<?php echo $tab==='pages'?'active':'' ?>">Connected Pages</a>
    <a href="?page=ai-smart-router&tab=skills" class="<?php echo $tab==='skills'?'active':'' ?>">Skills</a>
    <a href="?page=ai-smart-router&tab=logs" class="<?php echo $tab==='logs'?'active':'' ?>">Activity Log</a>
  </nav>

  <?php if ($tab === 'dashboard'): ?>
  <div class="ai-sr-section">
    <h2>System Status</h2>
    <div class="ai-sr-grid-2">
      <div class="ai-sr-card">
        <h4>Active Model</h4>
        <div class="ai-sr-stat"><?php echo esc_html(($models[$current['model']] ?? [])['name'] ?? 'None') ?></div>
        <p class="ai-sr-meta">Key #<?php echo ($current['key'] ?? 0) + 1 ?> &middot; Tier <?php echo ($models[$current['model']] ?? [])['tier'] ?? '-' ?></p>
      </div>
      <div class="ai-sr-card">
        <h4>Model Queue</h4>
        <div class="ai-sr-stat"><?php echo count($models) ?> models</div>
        <p class="ai-sr-meta"><?php echo array_sum(array_map('count', is_array($exhausted) ? $exhausted : [])) ?> exhausted &middot; daily reset at midnight</p>
      </div>
      <div class="ai-sr-card">
        <h4>Response Stats</h4>
        <div class="ai-sr-stat"><?php echo intval($stats['total_responses'] ?? 0) ?></div>
        <p class="ai-sr-meta"><?php echo intval($stats['today_responses'] ?? 0) ?> today</p>
      </div>
      <div class="ai-sr-card">
        <h4>API Keys</h4>
        <div class="ai-sr-stat"><?php echo count(array_filter(is_array($settings) ? ($settings['api_keys'] ?? []) : [])) ?>/5</div>
        <p class="ai-sr-meta"><?php echo count(array_filter(is_array($settings) ? ($settings['api_keys'] ?? []) : [])) ?> configured</p>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <?php if ($tab === 'keys'): ?>
  <div class="ai-sr-section">
    <h2>API Key Manager</h2>
    <p style="color:#64748b;margin-bottom:12px">Add up to 5 OpenRouter API keys for auto-failover.</p>
    <form method="post" action="options.php">
      <?php settings_fields('ai_router_settings_group'); ?>
      <table class="ai-sr-key-table">
        <thead><tr><th>#</th><th>Status</th><th>API Key</th><th>Test</th></tr></thead>
        <tbody>
          <?php $api_keys = is_array($settings) ? ($settings['api_keys'] ?? []) : []; for ($i = 0; $i < 5; $i++): $key = $api_keys[$i] ?? ''; ?>
          <tr>
            <td>Key <?php echo $i + 1 ?></td>
            <td><?php echo $key ? '<span style="color:#22c55e">&#9679; Configured</span>' : '<span style="color:#94a3b8">&#9679; Empty</span>' ?></td>
            <td><input type="text" name="ai_router_settings[api_keys][<?php echo $i ?>]" value="<?php echo esc_attr($key) ?>" class="ai-sr-input wide" placeholder="sk-or-v1-..."></td>
            <td><button type="button" class="button ai-sr-test-key" data-key="<?php echo $i ?>">Test</button></td>
          </tr>
          <?php endfor; ?>
        </tbody>
      </table>
      <?php submit_button('Save API Keys'); ?>
    </form>
  </div>
  <?php endif; ?>

  <?php if ($tab === 'models'): ?>
  <div class="ai-sr-section">
    <h2>Model Rankings (<?php echo count($models) ?> free models)</h2>
    <p style="color:#64748b;margin-bottom:12px">Models are tried in order. 429/500 &rarr; auto-switch to next.</p>
    <table class="ai-sr-model-table">
      <thead><tr><th>#</th><th>Model</th><th>Tier</th><th>Status</th></tr></thead>
      <tbody>
        <?php foreach ($models as $i => $m):
          $is_exhausted = false;
          foreach ($exhausted as $key_exhausted) {
            if (in_array($m['id'], $key_exhausted)) { $is_exhausted = true; break; }
          }
          $active = ($i === ($current['model'] ?? 0));
        ?>
        <tr class="<?php echo $active ? 'active-row' : '' ?>">
          <td><?php echo $i + 1 ?></td>
          <td><?php echo esc_html($m['name']) ?></td>
          <td>Tier <?php echo $m['tier'] ?></td>
          <td><?php echo $active ? '<span style="color:#22c55e">Active</span>' : ($is_exhausted ? '<span style="color:#ef4444">Exhausted</span>' : '<span style="color:#94a3b8">Available</span>') ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>

  <?php if ($tab === 'integration'): ?>
  <div class="ai-sr-section">
    <h2>Integration</h2>
    <div class="ai-sr-grid-2">
      <div class="ai-sr-card">
        <h4>REST API Endpoint</h4>
        <p class="ai-sr-meta">POST JSON to get AI replies</p>
        <code style="display:block;padding:10px;background:#f1f5f9;border-radius:6px;margin:8px 0;word-break:break-all"><?php echo get_rest_url(null, 'ai-router/v1/chat') ?></code>
        <p style="font-size:12px;color:#64748b">Body: <code>{"message": "your question"}</code></p>
      </div>
      <div class="ai-sr-card">
        <h4>Shortcode</h4>
        <p class="ai-sr-meta">Place in any page or post</p>
        <code style="display:block;padding:10px;background:#f1f5f9;border-radius:6px;margin:8px 0">[ai_chat]</code>
        <p style="font-size:12px;color:#64748b">Full WhatsApp-style chat UI</p>
      </div>
      <div class="ai-sr-card">
        <h4>WhatsApp</h4>
        <p class="ai-sr-meta">Baileys bridge connects here</p>
        <form method="post" action="options.php">
          <?php settings_fields('ai_router_settings_group'); ?>
          <label style="font-size:12px;color:#64748b;display:block;margin:4px 0">WhatsApp Number</label>
          <input type="text" name="ai_router_settings[whatsapp_number]" value="<?php echo esc_attr((is_array($settings) ? $settings : [])['whatsapp_number'] ?? '+880130585531') ?>" class="ai-sr-input wide">
          <label style="font-size:12px;color:#64748b;display:block;margin:8px 0 4px">Method</label>
          <?php $whatsapp_method = (is_array($settings) ? $settings : [])['whatsapp_method'] ?? 'baileys'; ?>
          <select name="ai_router_settings[whatsapp_method]" class="ai-sr-input" style="width:200px">
            <option value="baileys" <?php selected($whatsapp_method, 'baileys') ?>>Baileys (Free)</option>
            <option value="business" <?php selected($whatsapp_method, 'business') ?>>WhatsApp Business API</option>
          </select>
          <label style="font-size:12px;color:#64748b;display:block;margin:8px 0 4px">UAP Worker Phone Meta Key</label>
          <input type="text" name="ai_router_settings[uap_whatsapp_key]" value="<?php echo esc_attr((is_array($settings) ? $settings : [])['uap_whatsapp_key'] ?? 'uap_phone') ?>" class="ai-sr-input" style="width:200px" placeholder="uap_phone">
          <p style="font-size:11px;color:#94a3b8;margin:2px 0 0">The user meta key where Ultimate Affiliate Pro stores phone numbers (default: uap_phone)</p>
          <?php submit_button('Save WhatsApp Settings'); ?>
        </form>
      </div>
      <div class="ai-sr-card">
        <h4>Webhook URL</h4>
        <p class="ai-sr-meta">For WhatsApp / external services</p>
        <code style="display:block;padding:10px;background:#f1f5f9;border-radius:6px;margin:8px 0;word-break:break-all"><?php echo get_rest_url(null, 'ai-router/v1/webhook') ?></code>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <?php if ($tab === 'pages'): ?>
  <?php $all = ai_sr_get_all_publishable(); $sel_c = ai_sr_get_connected_page_ids('customer'); $sel_w = ai_sr_get_connected_page_ids('worker'); ?>
  <div class="ai-sr-section">
    <h2>Connected Pages</h2>
    <p style="color:#64748b;margin-bottom:12px">Select which pages/posts the AI reads. <strong>Customer</strong> = for customer chat. <strong>Worker</strong> = for worker/internal chat. Use <code>[ai_chat type="worker"]</code> for worker-facing chat.</p>
    <form method="post" action="options.php">
      <?php settings_fields('ai_router_settings_group'); ?>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div>
          <h4 style="margin:0 0 8px;color:#0284c7">Customer Pages</h4>
          <div style="max-height:400px;overflow-y:auto;border:1px solid #e2e8f0;border-radius:8px;padding:8px">
            <?php foreach ($all['pages'] as $p): ?>
            <label style="display:flex;align-items:center;gap:8px;padding:6px 8px;border-radius:4px;cursor:pointer">
              <input type="checkbox" name="ai_router_settings[connected_pages][customer][]" value="<?php echo $p->ID ?>" <?php echo in_array($p->ID, $sel_c) ? 'checked' : '' ?>>
              <?php echo esc_html($p->post_title) ?>
            </label>
            <?php endforeach; ?>
            <?php foreach ($all['posts'] as $p): ?>
            <label style="display:flex;align-items:center;gap:8px;padding:6px 8px;border-radius:4px;cursor:pointer">
              <input type="checkbox" name="ai_router_settings[connected_pages][customer][]" value="<?php echo $p->ID ?>" <?php echo in_array($p->ID, $sel_c) ? 'checked' : '' ?>>
              <?php echo esc_html($p->post_title) ?>
            </label>
            <?php endforeach; ?>
          </div>
          <p style="color:#94a3b8;font-size:12px;margin:4px 0 0"><?php echo count($sel_c) ?> selected</p>
        </div>
        <div>
          <h4 style="margin:0 0 8px;color:#7c3aed">Worker Pages</h4>
          <div style="max-height:400px;overflow-y:auto;border:1px solid #e2e8f0;border-radius:8px;padding:8px">
            <?php foreach ($all['pages'] as $p): ?>
            <label style="display:flex;align-items:center;gap:8px;padding:6px 8px;border-radius:4px;cursor:pointer">
              <input type="checkbox" name="ai_router_settings[connected_pages][worker][]" value="<?php echo $p->ID ?>" <?php echo in_array($p->ID, $sel_w) ? 'checked' : '' ?>>
              <?php echo esc_html($p->post_title) ?>
            </label>
            <?php endforeach; ?>
            <?php foreach ($all['posts'] as $p): ?>
            <label style="display:flex;align-items:center;gap:8px;padding:6px 8px;border-radius:4px;cursor:pointer">
              <input type="checkbox" name="ai_router_settings[connected_pages][worker][]" value="<?php echo $p->ID ?>" <?php echo in_array($p->ID, $sel_w) ? 'checked' : '' ?>>
              <?php echo esc_html($p->post_title) ?>
            </label>
            <?php endforeach; ?>
          </div>
          <p style="color:#94a3b8;font-size:12px;margin:4px 0 0"><?php echo count($sel_w) ?> selected</p>
        </div>
      </div>
      <p style="color:#94a3b8;font-size:12px;margin-top:8px">Content cached for 1 hour. Use <code>[ai_chat type="customer"]</code> (default) or <code>[ai_chat type="worker"]</code> in your pages.</p>
      <?php submit_button('Save Connected Pages'); ?>
    </form>
  </div>
  <?php endif; ?>

  <?php if ($tab === 'skills'): ?>
  <?php $skills_data = get_option('ai_router_skills', []); $conv_count = count(get_option('ai_router_conversations', [])); ?>
  <div class="ai-sr-section">
    <h2>Skills & Learning</h2>
    <p style="color:#64748b;margin-bottom:12px">AI learns from conversations. Consolidation runs daily. Skills reduce token usage by caching frequent answers.</p>

    <div class="ai-sr-grid-2">
      <div class="ai-sr-card">
        <h4>Conversations Logged</h4>
        <div class="ai-sr-stat"><?php echo $conv_count ?></div>
        <p class="ai-sr-meta">Last 500 stored</p>
      </div>
      <div class="ai-sr-card">
        <h4>Worker Shortcuts</h4>
        <div class="ai-sr-stat"><?php echo $skills_data['worker']['total_shortcuts'] ?? 0 ?></div>
        <p class="ai-sr-meta">Cached answers (0 token cost)</p>
      </div>
      <div class="ai-sr-card">
        <h4>FAQ Patterns</h4>
        <div class="ai-sr-stat"><?php echo count($skills_data['customer']['faq'] ?? []) ?></div>
        <p class="ai-sr-meta">Questions asked 3+ times</p>
      </div>
      <div class="ai-sr-card">
        <h4>Last Consolidated</h4>
        <div class="ai-sr-stat"><?php echo $skills_data['updated'] ? esc_html($skills_data['updated']) : 'Never' ?></div>
        <p class="ai-sr-meta">Daily at midnight</p>
      </div>
    </div>

    <?php if (!empty($skills_data['customer']['topics'])): ?>
    <h3 style="margin-top:24px">Hot Topics (Customer Skills)</h3>
    <div style="display:flex;flex-wrap:wrap;gap:6px;margin:8px 0">
      <?php foreach ($skills_data['customer']['topics'] as $topic => $count): ?>
      <span style="background:#e0f2fe;color:#0369a1;padding:4px 12px;border-radius:20px;font-size:13px"><?php echo esc_html($topic) ?> (<?php echo $count ?>)</span>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($skills_data['customer']['faq'])): ?>
    <h3 style="margin-top:24px">Frequently Asked Questions</h3>
    <table class="ai-sr-model-table" style="margin-top:8px">
      <thead><tr><th>#</th><th>Question</th><th>Times Asked</th><th>Best Model</th></tr></thead>
      <tbody>
        <?php foreach (array_slice($skills_data['customer']['faq'], 0, 20) as $i => $f): ?>
        <tr>
          <td><?php echo $i + 1 ?></td>
          <td><?php echo esc_html(mb_substr($f['question'], 0, 60)) . (mb_strlen($f['question']) > 60 ? '...' : '') ?></td>
          <td><?php echo $f['count'] ?></td>
          <td><?php echo esc_html($f['best_model'] ?? '-') ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>

    <?php if (!empty($skills_data['worker']['shortcuts'])): ?>
    <h3 style="margin-top:24px">Worker Shortcuts (Zero-Token Answers)</h3>
    <table class="ai-sr-model-table" style="margin-top:8px">
      <thead><tr><th>Question</th><th>Hits</th><th>Model</th></tr></thead>
      <tbody>
        <?php foreach (array_slice($skills_data['worker']['shortcuts'], 0, 10) as $s): ?>
        <tr>
          <td><?php echo esc_html(mb_substr($s['question'], 0, 60)) . (mb_strlen($s['question']) > 60 ? '...' : '') ?></td>
          <td><?php echo $s['count'] ?></td>
          <td><?php echo esc_html($s['best_model'] ?? '-') ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>

    <form method="post" style="margin-top:16px">
      <input type="hidden" name="ai_sr_clear_skills" value="1">
      <?php wp_nonce_field('ai_sr_clear_skills_action', 'ai_sr_clear_skills_nonce'); ?>
      <button type="submit" class="button button-secondary" onclick="return confirm('Clear all conversations and skills? This cannot be undone.')">Clear All Skills & Conversations</button>
    </form>
  </div>
  <?php endif; ?>

  <?php if ($tab === 'logs'): ?>
  <div class="ai-sr-section">
    <h2>Activity Log</h2>
    <p style="color:#64748b;margin-bottom:12px">Last 200 events. Cleared automatically.</p>
    <table class="ai-sr-log-table">
      <thead><tr><th>Time</th><th>Event</th><th>Details</th></tr></thead>
      <tbody>
        <?php if (empty($logs)): ?>
        <tr><td colspan="3" style="text-align:center;color:#94a3b8">No events yet</td></tr>
        <?php else: ?>
        <?php foreach ($logs as $log): ?>
        <tr>
          <td><?php echo esc_html($log['time']) ?></td>
          <td><span class="ai-sr-event-<?php echo strtolower($log['event']) ?>"><?php echo esc_html($log['event']) ?></span></td>
          <td><?php echo esc_html($log['details']) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>