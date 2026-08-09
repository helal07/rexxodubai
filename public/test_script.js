
    async function generateSitemap() {
        const btn = document.getElementById("btn-gen-sitemap");
        const res = document.getElementById("sitemap-result");
        if (!btn || !res) return;
        btn.disabled = true;
        const origHtml = btn.innerHTML;
        btn.innerHTML = "Generating...";
        res.className = "text-[12px] font-mono p-3 rounded-lg bg-[#f8fafc] border text-[#475569]";
        res.classList.remove("hidden");
        res.innerHTML = "Building sitemap from products and pages...";
        try {
            const r = await fetch("/admin/seo/generate-sitemap", {
                method: "POST",
                headers: { "Accept": "application/json", "X-CSRF-TOKEN": document.querySelector("meta[name=\"csrf-token\"]")?.getAttribute("content") || "" }
            });
            const d = await r.json();
            if (r.ok && d.success) {
                res.className = "text-[12px] font-mono p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800";
                res.innerHTML = d.message + "<br>" + d.entries + " URLs  Generated: " + d.generated_at;
                if(typeof loadSeoStatus === "function") loadSeoStatus();
            } else {
                res.className = "text-[12px] font-mono p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800";
                res.innerHTML = d.message;
            }
        } catch(e) {
            res.className = "text-[12px] font-mono p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800";
            res.innerHTML = "Server connection failed.";
        } finally {
            btn.disabled = false;
            btn.innerHTML = origHtml;
        }
    }

    async function generateRobots() {
        const btn = document.getElementById("btn-gen-robots");
        const res = document.getElementById("robots-result");
        if (!btn || !res) return;
        btn.disabled = true;
        const origHtml = btn.innerHTML;
        btn.innerHTML = "Updating...";
        res.className = "text-[12px] font-mono p-3 rounded-lg bg-[#f8fafc] border text-[#475569]";
        res.classList.remove("hidden");
        res.innerHTML = "Building robots.txt with sitemap reference...";
        try {
            const r = await fetch("/admin/seo/generate-robots", {
                method: "POST",
                headers: { "Accept": "application/json", "X-CSRF-TOKEN": document.querySelector("meta[name=\"csrf-token\"]")?.getAttribute("content") || "" }
            });
            const d = await r.json();
            if (r.ok && d.success) {
                res.className = "text-[12px] font-mono p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800";
                res.innerHTML = d.message + "<br>Generated: " + d.generated_at;
                if(typeof loadSeoStatus === "function") loadSeoStatus();
            } else {
                res.className = "text-[12px] font-mono p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800";
                res.innerHTML = d.message;
            }
        } catch(e) {
            res.className = "text-[12px] font-mono p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800";
            res.innerHTML = "Server connection failed.";
        } finally {
            btn.disabled = false;
            btn.innerHTML = origHtml;
        }
    }

    async function pingSeo() {
        const btn = document.getElementById("btn-ping");
        const res = document.getElementById("ping-result");
        const gBadge = document.getElementById("ping-google-badge");
        const bBadge = document.getElementById("ping-bing-badge");
        if (!btn || !res) return;
        btn.disabled = true;
        const origHtml = btn.innerHTML;
        btn.innerHTML = "Pinging...";
        if(gBadge) { gBadge.className="text-[10px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 shrink-0 animate-pulse"; gBadge.textContent="Pinging..."; }
        if(bBadge) { bBadge.className="text-[10px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 shrink-0 animate-pulse"; bBadge.textContent="Pinging..."; }
        res.className = "text-[12px] font-mono p-4 rounded-lg bg-[#f8fafc] border text-[#475569]";
        res.classList.remove("hidden");
        res.innerHTML = "Sending ping to Google & Bing...";
        try {
            const r = await fetch("/admin/seo/ping-search-engines", {
                method: "POST",
                headers: { "Accept": "application/json", "X-CSRF-TOKEN": document.querySelector("meta[name=\"csrf-token\"]")?.getAttribute("content") || "" }
            });
            const d = await r.json();
            if(gBadge) {
                const gOk = d.results?.Google?.success;
                gBadge.className = gOk ? "text-[10px] font-bold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0" : "text-[10px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 shrink-0";
                gBadge.textContent = gOk ? "? Accepted" : "? Failed";
            }
            if(bBadge) {
                const bOk = d.results?.Bing?.success;
                bBadge.className = bOk ? "text-[10px] font-bold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0" : "text-[10px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 shrink-0";
                bBadge.textContent = bOk ? "? Accepted" : "? Failed";
            }
            if (r.ok && d.success) {
                res.className = "text-[12px] font-mono p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 space-y-1";
                res.innerHTML = d.message;
            } else {
                res.className = "text-[12px] font-mono p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800";
                res.innerHTML = d.message;
            }
        } catch(e) {
            res.className = "text-[12px] font-mono p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800";
            res.innerHTML = "Server connection failed.";
        } finally {
            btn.disabled = false;
            btn.innerHTML = origHtml;
        }
    }

