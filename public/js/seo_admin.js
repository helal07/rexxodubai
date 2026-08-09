async function loadSeoStatus() {
    try {
        const r = await fetch('/admin/seo/status', { headers: { 'Accept': 'application/json' } });
        const d = await r.json();
        
        const sBadge = document.getElementById('sitemap-status-badge');
        const sUpdated = document.getElementById('sitemap-last-updated');
        const sEntries = document.getElementById('sitemap-entries');
        const sCheck = document.getElementById('sitemap-check-card');
        if (d.sitemap && d.sitemap.exists) {
            if (sBadge) { sBadge.className = 'bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase'; sBadge.textContent = '✔ Exists'; }
            if (sUpdated) sUpdated.textContent = d.sitemap.last_updated || '-';
            if (sEntries) sEntries.textContent = (d.sitemap.entries || 0) + ' URLs';
            if (sCheck) { sCheck.className = 'flex items-start gap-2.5 p-3 rounded-xl bg-emerald-50 border border-emerald-200'; sCheck.querySelector('i').setAttribute('data-lucide','check-circle-2'); sCheck.querySelector('i').className='w-4 h-4 text-emerald-600 mt-0.5 shrink-0'; sCheck.querySelector('p:first-child').className='text-[12px] font-bold text-emerald-800'; sCheck.querySelectorAll('p')[1].className='text-[10px] text-emerald-700'; sCheck.querySelectorAll('p')[1].textContent='Sitemap exists - '+d.sitemap.entries+' URLs indexed'; if(window.lucide) lucide.createIcons(); }
        } else {
            if (sBadge) { sBadge.className = 'bg-amber-50 text-amber-700 border border-amber-200 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase'; sBadge.textContent = '⚠ Not Found'; }
            if (sUpdated) sUpdated.textContent = 'Never';
            if (sEntries) sEntries.textContent = '0';
            if (sCheck) { sCheck.className = 'flex items-start gap-2.5 p-3 rounded-xl bg-amber-50 border border-amber-200'; sCheck.querySelector('i').setAttribute('data-lucide','alert-triangle'); sCheck.querySelector('i').className='w-4 h-4 text-amber-600 mt-0.5 shrink-0'; sCheck.querySelector('p:first-child').className='text-[12px] font-bold text-amber-800'; sCheck.querySelectorAll('p')[1].className='text-[10px] text-amber-700'; sCheck.querySelectorAll('p')[1].textContent='Sitemap missing - indexing issue'; if(window.lucide) lucide.createIcons(); }
        }

        const rBadge = document.getElementById('robots-status-badge');
        const rUpdated = document.getElementById('robots-last-updated');
        const rCheck = document.getElementById('robots-check-card');
        if (d.robots && d.robots.exists) {
            if (rBadge) { rBadge.className = 'bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase'; rBadge.textContent = '✔ Configured'; }
            if (rUpdated) rUpdated.textContent = d.robots.last_updated || '-';
            if (rCheck) { rCheck.className = 'flex items-start gap-2.5 p-3 rounded-xl bg-emerald-50 border border-emerald-200'; rCheck.querySelector('i').setAttribute('data-lucide','check-circle-2'); rCheck.querySelector('i').className='w-4 h-4 text-emerald-600 mt-0.5 shrink-0'; rCheck.querySelector('p:first-child').className='text-[12px] font-bold text-emerald-800'; rCheck.querySelectorAll('p')[1].className='text-[10px] text-emerald-700'; rCheck.querySelectorAll('p')[1].textContent='robots.txt configured securely'; if(window.lucide) lucide.createIcons(); }
        } else {
            if (rBadge) { rBadge.className = 'bg-rose-50 text-rose-700 border border-rose-200 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase'; rBadge.textContent = '⚠ Missing'; }
            if (rUpdated) rUpdated.textContent = 'Never';
            if (rCheck) { rCheck.className = 'flex items-start gap-2.5 p-3 rounded-xl bg-rose-50 border border-rose-200'; rCheck.querySelector('i').setAttribute('data-lucide','x-circle'); rCheck.querySelector('i').className='w-4 h-4 text-rose-600 mt-0.5 shrink-0'; rCheck.querySelector('p:first-child').className='text-[12px] font-bold text-rose-800'; rCheck.querySelectorAll('p')[1].className='text-[10px] text-rose-700'; rCheck.querySelectorAll('p')[1].textContent='robots.txt missing - critical SEO issue'; if(window.lucide) lucide.createIcons(); }
        }
    } catch(e) {}
}

async function generateSitemap() {
    const btn = document.getElementById('btn-gen-sitemap');
    const res = document.getElementById('sitemap-result');
    if (!btn || !res) return;
    btn.disabled = true;
    const origHtml = btn.innerHTML;
    btn.innerHTML = 'Generating...';
    res.className = 'text-[12px] font-mono p-3 rounded-lg bg-[#f8fafc] border text-[#475569]';
    res.classList.remove('hidden');
    res.innerHTML = 'Building sitemap from products and pages...';
    try {
        const csrfToken = document.querySelector('meta[name=\"csrf-token\"]') ? document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content') : '';
        const r = await fetch('/admin/seo/generate-sitemap', {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
        });
        const d = await r.json();
        if (r.ok && d.success) {
            res.className = 'text-[12px] font-mono p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800';
            res.innerHTML = d.message + '<br>' + d.entries + ' URLs Generated: ' + d.generated_at;
            if(typeof loadSeoStatus === 'function') loadSeoStatus();
        } else {
            res.className = 'text-[12px] font-mono p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800';
            res.innerHTML = d.message || 'Error occurred';
        }
    } catch(e) {
        res.className = 'text-[12px] font-mono p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800';
        res.innerHTML = 'Server connection failed.';
    } finally {
        btn.disabled = false;
        btn.innerHTML = origHtml;
    }
}

async function generateRobots() {
    const btn = document.getElementById('btn-gen-robots');
    const res = document.getElementById('robots-result');
    if (!btn || !res) return;
    btn.disabled = true;
    const origHtml = btn.innerHTML;
    btn.innerHTML = 'Updating...';
    res.className = 'text-[12px] font-mono p-3 rounded-lg bg-[#f8fafc] border text-[#475569]';
    res.classList.remove('hidden');
    res.innerHTML = 'Building robots.txt with sitemap reference...';
    try {
        const csrfToken = document.querySelector('meta[name=\"csrf-token\"]') ? document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content') : '';
        const r = await fetch('/admin/seo/generate-robots', {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
        });
        const d = await r.json();
        if (r.ok && d.success) {
            res.className = 'text-[12px] font-mono p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800';
            res.innerHTML = d.message + '<br>Generated: ' + d.generated_at;
            if(typeof loadSeoStatus === 'function') loadSeoStatus();
        } else {
            res.className = 'text-[12px] font-mono p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800';
            res.innerHTML = d.message || 'Error occurred';
        }
    } catch(e) {
        res.className = 'text-[12px] font-mono p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800';
        res.innerHTML = 'Server connection failed.';
    } finally {
        btn.disabled = false;
        btn.innerHTML = origHtml;
    }
}

async function pingSeo() {
    const btn = document.getElementById('btn-ping');
    const res = document.getElementById('ping-result');
    const gBadge = document.getElementById('ping-google-badge');
    const bBadge = document.getElementById('ping-bing-badge');
    if (!btn || !res) return;
    btn.disabled = true;
    const origHtml = btn.innerHTML;
    btn.innerHTML = 'Pinging...';
    if(gBadge) { gBadge.className='text-[10px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 shrink-0 animate-pulse'; gBadge.textContent='Pinging...'; }
    if(bBadge) { bBadge.className='text-[10px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 shrink-0 animate-pulse'; bBadge.textContent='Pinging...'; }
    res.className = 'text-[12px] font-mono p-4 rounded-lg bg-[#f8fafc] border text-[#475569]';
    res.classList.remove('hidden');
    res.innerHTML = 'Sending ping to Google & Bing...';
    try {
        const csrfToken = document.querySelector('meta[name=\"csrf-token\"]') ? document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content') : '';
        const r = await fetch('/admin/seo/ping-search-engines', {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
        });
        const d = await r.json();
        if(gBadge) {
            const gOk = d.results?.Google?.success;
            gBadge.className = gOk ? 'text-[10px] font-bold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0' : 'text-[10px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 shrink-0';
            gBadge.textContent = gOk ? '✔ Accepted' : '⚠ Failed';
        }
        if(bBadge) {
            const bOk = d.results?.Bing?.success;
            bBadge.className = bOk ? 'text-[10px] font-bold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0' : 'text-[10px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 shrink-0';
            bBadge.textContent = bOk ? '✔ Accepted' : '⚠ Failed';
        }
        if (r.ok && d.success) {
            res.className = 'text-[12px] font-mono p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 space-y-1';
            res.innerHTML = d.message || 'Success';
        } else {
            res.className = 'text-[12px] font-mono p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800';
            res.innerHTML = d.message || 'Error occurred';
        }
    } catch(e) {
        res.className = 'text-[12px] font-mono p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800';
        res.innerHTML = 'Server connection failed.';
    } finally {
        btn.disabled = false;
        btn.innerHTML = origHtml;
    }
}

// Bind to window explicitly to bypass any module/scope issues
window.generateSitemap = generateSitemap;
window.generateRobots = generateRobots;
window.pingSeo = pingSeo;
window.loadSeoStatus = loadSeoStatus;

document.addEventListener('DOMContentLoaded', loadSeoStatus);
