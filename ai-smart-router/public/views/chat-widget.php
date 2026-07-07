<div class="ai-sr-chat-app" data-chat-type="<?php echo esc_attr($atts['type']) ?>">
  <div class="ai-sr-chat-container">
    <div class="ai-sr-chat-sidebar" id="aiSrSidebar">
      <div class="ai-sr-chat-sidebar-header">
        <h3><?php echo esc_html($atts['title']) ?></h3>
        <button onclick="aiSrNewChat()" class="ai-sr-chat-new">+ New</button>
      </div>
      <div class="ai-sr-chat-search">
        <input id="aiSrSearch" placeholder="Search conversations" oninput="aiSrRenderContacts()">
      </div>
      <div class="ai-sr-chat-contacts" id="aiSrContactList"></div>
    </div>
    <div class="ai-sr-chat-main" id="aiSrMainChat">
      <div class="ai-sr-chat-placeholder">
        <div style="font-size:48px;opacity:.3;margin-bottom:12px">&#128172;</div>
        <h3>AI Assistant</h3>
        <p>Select a conversation or start a new chat</p>
      </div>
    </div>
  </div>
</div>
