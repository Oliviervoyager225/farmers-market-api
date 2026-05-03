<div
    x-data="{
        getInitials() {
            const fn = ($wire.get('data.firstname') || '').trim();
            const ln = ($wire.get('data.lastname')  || '').trim();
            return (fn || ln) ? (fn.charAt(0) + ln.charAt(0)).toUpperCase() : 'FB';
        }
    }"
    style="display:flex;align-items:center;gap:24px;padding:8px 0;">

    {{-- Avatar circle --}}
    <div
        x-text="getInitials()"
        style="width:88px;height:88px;border-radius:50%;background:#16a34a;color:#fff;
               display:flex;align-items:center;justify-content:center;
               font-size:2rem;font-weight:700;letter-spacing:.05em;flex-shrink:0;">
        FB
    </div>

    {{-- Upload button + hint --}}
    <div>
        <button type="button"
            style="display:inline-flex;align-items:center;gap:8px;padding:8px 16px;
                   border:1px solid #d1d5db;border-radius:8px;background:#fff;
                   font-size:13px;font-weight:500;color:#374151;cursor:default;">
            <svg style="width:15px;height:15px;color:#6b7280;" xmlns="http://www.w3.org/2000/svg"
                 fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0
                         21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
            </svg>
            Upload Photo
        </button>
        <p style="margin-top:6px;font-size:11px;color:#6b7280;">JPG, PNG up to 2MB</p>
    </div>
</div>
