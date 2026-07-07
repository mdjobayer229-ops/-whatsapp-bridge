(function(){
  document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.ai-sr-test-key').forEach(function(btn){
      btn.addEventListener('click', function(){
        var idx = this.getAttribute('data-key');
        var input = this.closest('tr').querySelector('input');
        var key = input.value.trim();
        if(!key){ alert('Enter an API key first'); return; }
        var original = this.textContent;
        this.textContent = 'Testing...';
        this.disabled = true;
        fetch(aiSr.restUrl + '/chat', {
          method:'POST',
          headers:{'Content-Type':'application/json','X-WP-Nonce':aiSr.nonce},
          body:JSON.stringify({message:'Say hello in one word.'})
        }).then(function(r){ return r.json(); }).then(function(d){
          alert(d.success ? 'OK: Model responded successfully' : 'Failed: ' + (d.message||'Unknown error'));
        }).catch(function(e){
          alert('Connection error: ' + e.message);
        }).finally(function(){
          this.textContent = original;
          this.disabled = false;
        }.bind(this));
      });
    });
  });
})();
