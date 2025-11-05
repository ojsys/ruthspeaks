(function(){
  function $(sel, ctx){ return (ctx||document).querySelector(sel); }
  function on(el, ev, fn){ el && el.addEventListener(ev, fn); }

  // Progress bar
  var progressBar = $('.progress-bar');
  function updateProgress(){
    var scrollTop = window.scrollY || document.documentElement.scrollTop;
    var docHeight = document.documentElement.scrollHeight - window.innerHeight;
    var pct = 0;
    if (docHeight > 0) pct = Math.min(100, Math.max(0, (scrollTop / docHeight) * 100));
    if (progressBar) progressBar.style.width = pct + '%';
    window.__readingProgress = pct;
    return pct;
  }
  updateProgress();
  on(window, 'scroll', updateProgress);
  on(window, 'resize', updateProgress);

  // Active time tracking (pauses when tab hidden)
  var active = true;
  var lastTick = Date.now();
  var engagedMs = 0;
  function tick(){
    var now = Date.now();
    if (active) engagedMs += (now - lastTick);
    lastTick = now;
  }
  setInterval(tick, 250);
  on(document, 'visibilitychange', function(){ active = !document.hidden; lastTick = Date.now(); });
  on(window, 'focus', function(){ active = true; lastTick = Date.now(); });
  on(window, 'blur', function(){ active = false; });

  // Anonymous reader id cookie
  function cookieGet(name){
    return document.cookie.split('; ').find(r => r.startsWith(name+'='))?.split('=')[1];
  }
  function cookieSet(name, val, days){
    var d = new Date();
    d.setTime(d.getTime() + (days*24*60*60*1000));
    document.cookie = name + '=' + val + '; expires=' + d.toUTCString() + '; path=/; SameSite=Lax';
  }
  function randomId(){ return (crypto && crypto.randomUUID) ? crypto.randomUUID() : (Math.random().toString(36).slice(2) + Date.now()); }

  var anonId = cookieGet('reader_id');
  if (!anonId){ anonId = randomId(); cookieSet('reader_id', anonId, 365); }

  // API helpers
  function postJSON(url, data){
    return fetch(url, { method:'POST', headers:{ 'Content-Type':'application/json' }, body: JSON.stringify(data) })
      .then(r => r.json());
  }

  // Periodic read tracking
  var postId = window.POST_ID || null;
  var postMinMs = window.MIN_TIME_MS || 0;
  var progressGoal = window.PROGRESS_GOAL || 75;
  var trackerTimer = null;

  function sendTrack(){
    if (!postId) return;
    var prog = Math.round(updateProgress());
    postJSON('/api/track', {
      postId: postId,
      anonId: anonId,
      progress: prog,
      timeSpentMs: engagedMs
    }).catch(()=>{});
  }
  trackerTimer = setInterval(sendTrack, 4000);
  on(window, 'beforeunload', sendTrack);

  // Giveaway UI
  var cta = $('.giveaway-cta');
  var modalBackdrop = $('.modal-backdrop');
  var claimBtn = $('#claim-btn');
  var emailInput = $('#claim-email');
  var keywordInput = $('#claim-keyword');
  var resultBox = $('#claim-result');
  var minReached = false, scrollReached = false;

  function evalUnlockState(){
    scrollReached = (window.__readingProgress || 0) >= progressGoal;
    minReached = engagedMs >= postMinMs;
    if (cta) cta.disabled = !(scrollReached && minReached);
  }
  setInterval(evalUnlockState, 1000);
  on(window, 'scroll', evalUnlockState);

  on(cta, 'click', function(){
    if (cta.disabled) return;
    if (modalBackdrop) modalBackdrop.style.display = 'flex';
  });
  on(modalBackdrop, 'click', function(e){ if (e.target === modalBackdrop) modalBackdrop.style.display = 'none'; });

  on(claimBtn, 'click', function(){
    if (!postId) return;
    var email = (emailInput && emailInput.value || '').trim();
    var keyword = (keywordInput && keywordInput.value || '').trim();
    if (!email || !email.includes('@')) { resultBox.textContent = 'Please enter a valid email.'; return; }
    claimBtn.disabled = true; resultBox.textContent = 'Submitting...';
    postJSON('/api/unlock', {
      postId: postId,
      anonId: anonId,
      email: email,
      keyword: keyword,
      timeSpentMs: engagedMs,
      progress: Math.round(window.__readingProgress||0)
    }).then(function(res){
      if (res.success) {
        resultBox.innerHTML = 'Congratulations! Your code: <b>'+res.code+'</b>';
      } else {
        resultBox.textContent = res.message || 'Unable to claim at this time.';
      }
      claimBtn.disabled = false;
    }).catch(function(){
      resultBox.textContent = 'Network error. Try again.';
      claimBtn.disabled = false;
    });
  });
})();

