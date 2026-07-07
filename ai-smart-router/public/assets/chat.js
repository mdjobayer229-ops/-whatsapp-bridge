(function(){
  var STORAGE_KEY = 'ai_sr_chats';
  var data = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
  var activeId = null;

  function save(){localStorage.setItem(STORAGE_KEY, JSON.stringify(data))}

  function findOrCreate(id, name){
    var c = data.find(function(x){ return x.id === id; });
    if(!c){
      c = {id:id, name:name, msgs:[{role:'assistant', content:'Hello! I am your AI assistant. How can I help you today?', time:Date.now()}], time:Date.now()};
      data.push(c); save();
    }
    return c;
  }

  window.aiSrRenderContacts = function(){
    var el = document.getElementById('aiSrContactList');
    if(!el) return;
    var q = (document.getElementById('aiSrSearch') || {}).value || '';
    q = q.toLowerCase();
    var filtered = data.filter(function(c){ return c.name.toLowerCase().indexOf(q) !== -1; });
    if(!filtered.length){
      el.innerHTML = '<div class="ai-sr-empty-state">No conversations</div>';
      return;
    }
    filtered.sort(function(a,b){ return b.time - a.time; });
    el.innerHTML = filtered.map(function(c){
      var last = c.msgs[c.msgs.length-1];
      var preview = last ? (last.content.length > 30 ? last.content.slice(0,30)+'...' : last.content) : '';
      return '<div class="ai-sr-chat-contact '+(activeId===c.id?'active':'')+'" onclick="aiSrOpenChat(\''+c.id+'\')">'+
        '<div class="av">'+c.name[0].toUpperCase()+'</div>'+
        '<div class="info"><div class="name">'+c.name+'</div><div class="preview">'+preview+'</div></div></div>';
    }).join('');
  };

  window.aiSrOpenChat = function(id){
    activeId = id;
    var sidebar = document.getElementById('aiSrSidebar');
    if(sidebar && window.innerWidth <= 600) sidebar.classList.remove('show');
    aiSrRenderContacts();
    aiSrRenderMain();
  };

  window.aiSrNewChat = function(){
    var name = prompt('Enter a name for this conversation:');
    if(!name) return;
    var id = 'conv_'+Date.now();
    findOrCreate(id, name);
    activeId = id;
    aiSrRenderMain();
    aiSrRenderContacts();
  };

  function escapeHTML(t){
    return t.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>');
  }

  function addMsg(role, content){
    var c = data.find(function(x){ return x.id === activeId; });
    if(!c) return;
    c.msgs.push({role:role, content:content, time:Date.now()});
    c.time = Date.now(); save();
    aiSrRenderMsgs();
    aiSrRenderContacts();
  }

  function showLoad(){
    var el = document.getElementById('aiSrMsgs');
    if(!el) return;
    var d = document.createElement('div');
    d.id = 'aiSrLoad';
    d.className = 'ai-sr-msg-wrap received';
    d.innerHTML = '<div class="bubble"><span class="ai-sr-loading"><span></span><span></span><span></span></span></div>';
    el.appendChild(d);
    el.scrollTop = el.scrollHeight;
  }

  function removeLoad(){
    var el = document.getElementById('aiSrLoad');
    if(el) el.remove();
  }

  window.aiSrSendMsg = function(){
    var input = document.getElementById('aiSrMsgInput');
    if(!input) return;
    var text = input.value.trim();
    if(!text) return;
    var c = data.find(function(x){ return x.id === activeId; });
    if(!c){ return; }
    addMsg('user', text);
    input.value = '';
    var btn = document.getElementById('aiSrSendBtn');
    if(btn){ btn.disabled = true; btn.innerHTML = '<span class="ai-sr-loading"><span></span><span></span><span></span></span>'; }
    showLoad();
    fetch(aiSrChat.restUrl, {
      method:'POST',
      headers:{'Content-Type':'application/json','X-WP-Nonce':aiSrChat.nonce},
      var chatType = (document.querySelector('.ai-sr-chat-app') || {}).getAttribute('data-chat-type') || 'customer';
      body:JSON.stringify({message:text, conversation_id:c.id, type:chatType})
    }).then(function(r){ return r.json(); }).then(function(d){
      removeLoad();
      if(btn){ btn.disabled = false; btn.innerHTML = '➤'; }
      if(d.success){ addMsg('assistant', d.reply); }
      else { addMsg('assistant', 'Error: '+(d.message||'No response')); }
    }).catch(function(e){
      removeLoad();
      if(btn){ btn.disabled = false; btn.innerHTML = '➤'; }
      addMsg('assistant', 'Network error. Please check your connection.');
    });
  };

  window.aiSrRenderMsgs = function(){
    var c = data.find(function(x){ return x.id === activeId; });
    var el = document.getElementById('aiSrMsgs');
    if(!el || !c) return;
    el.innerHTML = c.msgs.map(function(m){
      var cls = m.role === 'user' ? 'sent' : 'received';
      var t = new Date(m.time).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
      return '<div class="ai-sr-msg-wrap '+cls+'"><div class="bubble">'+escapeHTML(m.content)+'<div class="time">'+t+'</div></div></div>';
    }).join('');
    el.scrollTop = el.scrollHeight;
  };

  window.aiSrRenderMain = function(){
    var container = document.getElementById('aiSrMainChat');
    if(!container) return;
    var c = data.find(function(x){ return x.id === activeId; });
    if(!c){
      container.innerHTML = '<div class="ai-sr-chat-placeholder"><div style="font-size:48px;opacity:.3;margin-bottom:12px">💬</div><h3>AI Assistant</h3><p>Select a conversation</p></div>';
      return;
    }
    container.innerHTML = '<div class="ai-sr-chat-header">'+
      '<div class="av">'+c.name[0].toUpperCase()+'</div>'+
      '<div class="info"><div class="name">'+c.name+'</div><div class="status">online</div></div>'+
      '</div>'+
      '<div class="ai-sr-chat-msgs" id="aiSrMsgs"></div>'+
      '<div class="ai-sr-chat-input">'+
      '<input id="aiSrMsgInput" placeholder="Type a message" onkeydown="if(event.key===\"Enter\"){event.preventDefault();aiSrSendMsg()}">'+
      '<button id="aiSrSendBtn" onclick="aiSrSendMsg()">➤</button></div>';
    aiSrRenderMsgs();
    var inp = document.getElementById('aiSrMsgInput');
    if(inp) inp.focus();
  };

  // Init
  if(!data.length){
    data.push({id:'intro', name:'Me', msgs:[{role:'assistant', content:'Hello! I am your AI assistant. How can I help you?', time:Date.now()}], time:Date.now()});
    save();
  }
  activeId = data[0].id;
  if(document.getElementById('aiSrContactList')){ aiSrRenderContacts(); aiSrRenderMain(); }
})();
