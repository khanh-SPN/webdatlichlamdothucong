<?php
/** @var \App\View\AppView $this */
?>

    <!-- Teacher availability: floating launcher + modal (see script below) -->
    <button
        type="button"
        id="availability-fab"
        class="fixed bottom-6 right-6 z-[120] flex h-12 w-12 items-center justify-center rounded-full bg-primary-600 text-white shadow-md transition hover:bg-primary-700 hover:shadow-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2"
        aria-haspopup="dialog"
        aria-controls="availability-dialog"
        aria-expanded="false"
        aria-label="Open studio assistant"
    >
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
    </button>

    <style>
        .cc-chat-msg { animation: cc-chat-in 0.18s ease-out forwards; }
        @keyframes cc-chat-in {
            from { opacity: 0.6; }
            to { opacity: 1; }
        }
        .cc-typing-dot:nth-child(2) { animation-delay: 0.15s; }
        .cc-typing-dot:nth-child(3) { animation-delay: 0.3s; }
    </style>

    <div
        id="availability-modal-root"
        class="fixed inset-0 z-[116] hidden"
        aria-hidden="true"
    >
        <div
            class="absolute inset-0 bg-neutral-900/35 transition-opacity"
            id="availability-modal-backdrop"
            tabindex="-1"
        ></div>
        <div
            role="dialog"
            aria-modal="true"
            aria-labelledby="availability-dialog-title"
            id="availability-dialog"
            class="absolute bottom-24 left-4 right-4 top-auto mx-auto flex h-[min(560px,calc(100vh-7rem))] max-h-[min(560px,calc(100vh-7rem))] w-full max-w-md flex-col overflow-hidden rounded-2xl border border-neutral-200/90 bg-white shadow-2xl shadow-neutral-900/10 ring-1 ring-black/5 sm:left-auto sm:right-6"
        >
            <!-- Header -->
            <div class="flex shrink-0 items-center justify-between gap-3 border-b border-primary-100/80 bg-gradient-to-r from-primary-50/90 via-white to-white px-4 py-3.5">
                <div class="flex min-w-0 items-center gap-2.5">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white text-primary-600 shadow-sm ring-1 ring-primary-100">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h2 id="availability-dialog-title" class="text-sm font-semibold leading-tight text-neutral-900">
                            Studio assistant
                        </h2>
                        <p class="mt-0.5 text-xs leading-snug text-neutral-600">
                            Find the right page, get short summaries, and ask about workshops or policies. Bookings when you're signed in.
                        </p>
                    </div>
                </div>
                <div class="flex shrink-0 items-center gap-0.5">
                    <button
                        type="button"
                        id="availability-clear-chat"
                        class="rounded-md p-2 text-neutral-400 transition hover:bg-neutral-100 hover:text-neutral-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/40"
                        aria-label="Clear conversation"
                        title="Clear conversation"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                    <button
                        type="button"
                        id="availability-modal-close"
                        class="rounded-md p-2 text-neutral-400 transition hover:bg-neutral-100 hover:text-neutral-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/40"
                        aria-label="Close"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex min-h-0 flex-1 flex-col">
                <div class="shrink-0 border-b border-neutral-100 bg-neutral-50/90 px-4 py-2.5">
                    <h3 class="text-xs font-medium uppercase tracking-wide text-neutral-500">Suggestions</h3>
                    <div id="availability-starter-chips" class="mt-2 max-h-[5.5rem] overflow-y-auto pr-0.5 flex flex-wrap gap-1.5" role="group" aria-label="Suggested questions">
                        <button type="button" class="availability-chip rounded-md border border-neutral-200 bg-white px-2.5 py-1 text-left text-xs font-medium text-neutral-700 transition hover:border-primary-200 hover:bg-primary-50/50 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/30" data-q="When is the next workshop session?">
                            Next workshop session?
                        </button>
                        <button type="button" class="availability-chip rounded-md border border-neutral-200 bg-white px-2.5 py-1 text-left text-xs font-medium text-neutral-700 transition hover:border-primary-200 hover:bg-primary-50/50 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/30" data-q="Show all upcoming workshops">
                            All upcoming workshops
                        </button>
                        <button type="button" class="availability-chip rounded-md border border-neutral-200 bg-white px-2.5 py-1 text-left text-xs font-medium text-neutral-700 transition hover:border-primary-200 hover:bg-primary-50/50 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/30" data-q="What should I bring to class?">
                            What should I bring?
                        </button>
                        <button type="button" class="availability-chip rounded-md border border-neutral-200 bg-white px-2.5 py-1 text-left text-xs font-medium text-neutral-700 transition hover:border-primary-200 hover:bg-primary-50/50 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/30" data-q="How do I book a workshop?">
                            How do I book?
                        </button>
                        <button type="button" class="availability-chip rounded-md border border-neutral-200 bg-white px-2.5 py-1 text-left text-xs font-medium text-neutral-700 transition hover:border-primary-200 hover:bg-primary-50/50 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/30" data-q="Where can I contact the studio or send a message?">
                            Contact the studio
                        </button>
                    </div>
                </div>

                <p id="availability-chat-status" role="status" aria-live="polite" class="shrink-0 px-4 pt-2 min-h-[1.25rem] text-xs text-neutral-500"></p>

                <div
                    id="availability-chat-scroll"
                    class="min-h-0 flex-1 overflow-y-auto bg-neutral-50 p-4"
                >
                    <div id="availability-chat-empty" class="flex min-h-[10rem] flex-col items-center justify-center py-4 text-center">
                        <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-full border border-neutral-200 bg-white text-neutral-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <h3 class="mb-1 text-sm font-medium text-neutral-800">Ask anything</h3>
                        <p class="max-w-[280px] text-xs leading-relaxed text-neutral-500">
                            Workshops, policies, or "where is that on your website?" — answers stay grounded in what we publish here.
                        </p>
                    </div>
                    <div id="availability-chat-log" class="hidden space-y-3" role="log" aria-relevant="additions" aria-live="polite"></div>
                    <div id="availability-typing-indicator" class="hidden pt-1" aria-hidden="true">
                        <div class="flex justify-start">
                            <div class="inline-flex items-center gap-1.5 rounded-lg border border-neutral-200 bg-white px-3 py-2 shadow-sm">
                                <span class="cc-typing-dot h-1.5 w-1.5 rounded-full bg-neutral-400"></span>
                                <span class="cc-typing-dot h-1.5 w-1.5 rounded-full bg-neutral-400"></span>
                                <span class="cc-typing-dot h-1.5 w-1.5 rounded-full bg-neutral-400"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="availability-chat-form" class="shrink-0 border-t border-neutral-100 bg-white p-3">
                    <label class="sr-only" for="availability-chat-input">Your question</label>
                    <div class="relative flex items-stretch gap-2">
                        <input
                            id="availability-chat-input"
                            type="text"
                            maxlength="2000"
                            autocomplete="off"
                            placeholder="Workshops, FAQs, or finding a page…"
                            class="min-w-0 flex-1 rounded-lg border border-neutral-200 bg-white px-3 py-2.5 text-sm text-neutral-900 placeholder:text-neutral-400 focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500"
                        />
                        <button
                            type="submit"
                            id="availability-chat-submit"
                            class="inline-flex shrink-0 items-center justify-center rounded-lg bg-primary-600 px-3.5 text-white transition hover:bg-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-1 disabled:cursor-not-allowed disabled:bg-neutral-200 disabled:text-neutral-400"
                            aria-label="Send"
                        >
                            <span id="availability-send-icon" class="flex items-center justify-center">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </span>
                            <span id="availability-loading-icon" class="hidden items-center justify-center">
                                <svg class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>

                <p id="availability-chat-meta" class="shrink-0 border-t border-neutral-100 px-4 pb-2.5 pt-1.5 min-h-[1rem] text-xs text-neutral-400"></p>
            </div>
        </div>
    </div>
<script>
(function () {
    const askUrl = <?= json_encode($this->Url->build(['controller' => 'TeacherAvailability', 'action' => 'ask'])) ?>;
    const bookingUrl = <?= json_encode($this->Url->build(['controller' => 'Bookings', 'action' => 'add'])) ?>;
    const loginUrl = <?= json_encode($this->Url->build('/pages/login', ['fullBase' => true])) ?>;

    const ASK_TIMEOUT_MS = 35000;
    /** Minimum time the loading state stays visible after send (ms). */
    const MIN_REPLY_DELAY_MS = 1000;

    const STORAGE_KEY = 'studio_assistant_chat';
    const MAX_STORAGE_AGE_MS = 1000 * 60 * 60 * 4; // 4 hours

    let chatHistory = [];
    let availabilityModalOpen = false;
    let askAbort = null;

    function saveChatToStorage() {
        try {
            const data = {
                history: chatHistory,
                timestamp: Date.now()
            };
            localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
        } catch (e) {
            // Ignore storage errors (e.g., private mode)
        }
    }

    function loadChatFromStorage() {
        try {
            const raw = localStorage.getItem(STORAGE_KEY);
            if (!raw) return false;
            const data = JSON.parse(raw);
            if (!data || !Array.isArray(data.history)) return false;
            // Check expiration
            if (data.timestamp && (Date.now() - data.timestamp) > MAX_STORAGE_AGE_MS) {
                localStorage.removeItem(STORAGE_KEY);
                return false;
            }
            chatHistory = data.history.slice(-10); // Keep last 10 messages
            return true;
        } catch (e) {
            return false;
        }
    }

    function clearChatStorage() {
        try {
            localStorage.removeItem(STORAGE_KEY);
        } catch (e) {
            // Ignore
        }
    }

    function renderChatHistory() {
        if (!chatHistory || chatHistory.length === 0) return;
        const log = document.getElementById('availability-chat-log');
        if (!log) return;
        // Clear existing content
        log.innerHTML = '';
        ensureChatMessagesVisible();
        // Render each message
        chatHistory.forEach(function(msg) {
            if (!msg || !msg.role || !msg.content) return;
            if (msg.role === 'user') {
                appendChat('user', msg.content);
            } else if (msg.role === 'assistant') {
                // For assistant messages, render as simple text (without complex buttons)
                const row = document.createElement('div');
                row.className = 'flex justify-start cc-chat-msg';
                const bubble = document.createElement('div');
                bubble.className = 'max-w-[85%] rounded-lg border border-neutral-200 bg-white px-3 py-2 text-left text-sm leading-relaxed text-neutral-800 shadow-sm';
                bubble.style.whiteSpace = 'pre-wrap';
                bubble.textContent = msg.content;
                row.appendChild(bubble);
                log.appendChild(row);
            }
        });
        scrollChatToBottom();
    }

    function csrfHeader() {
        const m = document.querySelector('meta[name="csrf-token"]');
        const t = m && m.getAttribute('content');
        return t ? { 'X-CSRF-Token': t } : {};
    }

    function chatScrollEl() {
        return document.getElementById('availability-chat-scroll');
    }

    function ensureChatMessagesVisible() {
        document.getElementById('availability-chat-empty')?.classList.add('hidden');
        document.getElementById('availability-chat-log')?.classList.remove('hidden');
    }

    function resetChatUI() {
        const log = document.getElementById('availability-chat-log');
        if (log) log.innerHTML = '';
        document.getElementById('availability-chat-empty')?.classList.remove('hidden');
        document.getElementById('availability-chat-log')?.classList.add('hidden');
        document.getElementById('availability-typing-indicator')?.classList.add('hidden');
        clearChatStorage();
    }

    function scrollChatToBottom() {
        const sc = chatScrollEl();
        if (sc) sc.scrollTop = sc.scrollHeight;
    }

    function appendChat(role, text) {
        const log = document.getElementById('availability-chat-log');
        if (!log) return;
        ensureChatMessagesVisible();
        const row = document.createElement('div');
        row.className =
            (role === 'user' ? 'flex justify-end' : 'flex justify-start') + ' cc-chat-msg';
        const bubble = document.createElement('div');
        bubble.className =
            role === 'user'
                ? 'max-w-[85%] rounded-lg bg-primary-600 px-3 py-2 text-left text-sm leading-relaxed text-white'
                : 'max-w-[85%] rounded-lg border border-neutral-200 bg-white px-3 py-2 text-left text-sm leading-relaxed text-neutral-800 shadow-sm';
        bubble.style.whiteSpace = 'pre-wrap';
        bubble.textContent = text;
        row.appendChild(bubble);
        log.appendChild(row);
        scrollChatToBottom();
    }

    function replyImpliesBookingCta(text) {
        if (!text) return false;
        const normalized = text.replace(/[\s\u00A0]+/g, ' ');
        return /booking\s+page/i.test(normalized);
    }

    /** Remove raw URLs from model text so navigation uses buttons only. */
    function stripHttpUrls(text) {
        if (!text || typeof text !== 'string') return text;
        return text
            .replace(/\s*https?:\/\/[^\s)\]>]+/gi, '')
            .replace(/\n{3,}/g, '\n\n')
            .replace(/[ \t]+\n/g, '\n')
            .trim();
    }

    function appendAssistantMessage(text, showBookingCta, bookingLinks, loginUrl, suggestedLinks) {
        const log = document.getElementById('availability-chat-log');
        if (!log) return;
        ensureChatMessagesVisible();
        const showBtn = Boolean(showBookingCta) || replyImpliesBookingCta(text);
        const row = document.createElement('div');
        row.className = 'flex justify-start cc-chat-msg';
        const wrap = document.createElement('div');
        wrap.className =
            'max-w-[90%] rounded-xl border border-neutral-200/90 bg-white px-3 py-2.5 text-left text-sm leading-relaxed text-neutral-800 shadow-sm';
        const textEl = document.createElement('div');
        textEl.style.whiteSpace = 'pre-wrap';
        textEl.textContent = stripHttpUrls(text);
        wrap.appendChild(textEl);
        if (Array.isArray(suggestedLinks) && suggestedLinks.length > 0) {
            const lab = document.createElement('p');
            lab.className = 'mt-3 mb-1.5 text-xs font-semibold uppercase tracking-wide text-neutral-500';
            lab.textContent = 'Quick links';
            wrap.appendChild(lab);
            const list = document.createElement('div');
            list.className = 'flex flex-col gap-2';
            suggestedLinks.forEach(function (link) {
                if (!link || !link.url) return;
                const a = document.createElement('a');
                a.href = link.url;
                a.rel = 'noopener noreferrer';
                a.setAttribute('role', 'button');
                a.textContent = link.label || 'Open page';
                if (link.hint) a.setAttribute('title', link.hint);
                a.className =
                    'flex w-full items-center justify-center rounded-xl border-2 border-neutral-200 bg-white px-4 py-2.5 text-center text-sm font-semibold text-neutral-800 no-underline shadow-sm transition hover:border-primary-400 hover:bg-primary-50/60 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-1';
                list.appendChild(a);
            });
            wrap.appendChild(list);
        }
        if (Array.isArray(bookingLinks) && bookingLinks.length > 0) {
            const lab = document.createElement('p');
            lab.className = 'mt-3 mb-1.5 text-xs font-semibold uppercase tracking-wide text-neutral-500';
            lab.textContent = 'Book this session';
            wrap.appendChild(lab);
            const bookList = document.createElement('div');
            bookList.className = 'flex flex-col gap-2';
            bookingLinks.forEach(function (link) {
                if (!link || !link.url) return;
                const a = document.createElement('a');
                a.href = link.url;
                a.textContent = link.label || 'Open booking';
                a.rel = 'noopener noreferrer';
                a.setAttribute('role', 'button');
                a.className =
                    'flex w-full items-center justify-center rounded-xl border-2 border-primary-200 bg-primary-50 px-4 py-2.5 text-center text-sm font-semibold text-primary-900 no-underline shadow-sm transition hover:border-primary-400 hover:bg-primary-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-1';
                bookList.appendChild(a);
            });
            wrap.appendChild(bookList);
        }
        if (loginUrl) {
            const a = document.createElement('a');
            a.href = loginUrl;
            a.textContent = 'Sign in to your account';
            a.setAttribute('role', 'button');
            a.className =
                'mt-2 flex w-full items-center justify-center rounded-xl border-2 border-neutral-300 bg-neutral-50 px-4 py-2.5 text-center text-sm font-semibold text-neutral-800 no-underline shadow-sm transition hover:bg-neutral-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500/40 focus-visible:ring-offset-1';
            wrap.appendChild(a);
        }
        if (showBtn) {
            const a = document.createElement('a');
            a.href = bookingUrl;
            a.textContent = 'Book a workshop';
            a.setAttribute('role', 'button');
            a.className =
                'mt-2 flex w-full items-center justify-center rounded-xl bg-primary-600 px-4 py-2.5 text-center text-sm font-semibold text-white no-underline shadow-md transition hover:bg-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-1';
            wrap.appendChild(a);
        }
        row.appendChild(wrap);
        log.appendChild(row);
        scrollChatToBottom();
    }

    function appendTimeoutActions() {
        const log = document.getElementById('availability-chat-log');
        if (!log) return;
        ensureChatMessagesVisible();
        const row = document.createElement('div');
        row.className = 'flex justify-start cc-chat-msg';
        const wrap = document.createElement('div');
        wrap.className =
            'max-w-[85%] rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-left text-xs text-amber-950';
        wrap.innerHTML =
            '<p class="m-0 font-medium text-amber-900">Still waiting for a reply?</p><p class="mt-1 text-xs text-amber-800/90">Try the full schedule or book directly.</p>';
        const btnRow = document.createElement('div');
        btnRow.className = 'mt-2 flex flex-col gap-2';
        const a1 = document.createElement('button');
        a1.type = 'button';
        a1.textContent = 'Ask: show all upcoming workshops';
        a1.className =
            'rounded-md border border-amber-200 bg-white px-3 py-2 text-center text-xs font-semibold text-amber-900 hover:bg-amber-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-400/50';
        a1.addEventListener('click', function () {
            const input = document.getElementById('availability-chat-input');
            if (input) input.value = 'Show all upcoming workshops';
            updateSendButtonState();
            document.getElementById('availability-chat-form')?.requestSubmit();
        });
        const a2 = document.createElement('a');
        a2.href = bookingUrl;
        a2.textContent = 'Book a workshop';
        a2.setAttribute('role', 'button');
        a2.className =
            'flex w-full items-center justify-center rounded-xl bg-amber-600 px-4 py-2.5 text-center text-sm font-semibold text-white no-underline shadow-sm hover:bg-amber-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-500/40';
        btnRow.appendChild(a1);
        btnRow.appendChild(a2);
        wrap.appendChild(btnRow);
        row.appendChild(wrap);
        log.appendChild(row);
        scrollChatToBottom();
    }

    function updateSendButtonState() {
        const input = document.getElementById('availability-chat-input');
        const submit = document.getElementById('availability-chat-submit');
        if (!input || !submit) return;
        const empty = !(input.value || '').trim();
        const loading = submit.dataset.loading === '1';
        submit.disabled = empty || loading;
    }

    function setLoading(isLoading, statusEl) {
        const submit = document.getElementById('availability-chat-submit');
        const input = document.getElementById('availability-chat-input');
        const sendIcon = document.getElementById('availability-send-icon');
        const loadIcon = document.getElementById('availability-loading-icon');
        const typing = document.getElementById('availability-typing-indicator');
        if (submit) submit.dataset.loading = isLoading ? '1' : '';
        if (input) input.disabled = isLoading;
        if (sendIcon && loadIcon) {
            sendIcon.classList.toggle('hidden', isLoading);
            loadIcon.classList.toggle('hidden', !isLoading);
            loadIcon.classList.toggle('inline-flex', isLoading);
        }
        if (typing) {
            typing.classList.toggle('hidden', !isLoading);
            if (isLoading) typing.setAttribute('aria-hidden', 'false');
            else typing.setAttribute('aria-hidden', 'true');
        }
        if (isLoading) scrollChatToBottom();
        updateSendButtonState();
        if (statusEl) {
            if (isLoading) {
                statusEl.textContent = 'Preparing an answer…';
            } else {
                statusEl.textContent = '';
            }
        }
    }

    function onAvailabilityEscape(ev) {
        if (ev.key === 'Escape') closeAvailabilityModal();
    }

    function trapFocusInDialog(ev) {
        if (!availabilityModalOpen || ev.key !== 'Tab') return;
        const dialog = document.getElementById('availability-dialog');
        if (!dialog || !dialog.contains(ev.target)) return;
        const focusables = dialog.querySelectorAll(
            'button:not([disabled]):not([aria-hidden="true"]), [href], input:not([disabled]), select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        const list = Array.prototype.filter.call(focusables, function (el) {
            return el.offsetParent !== null || el === document.activeElement;
        });
        if (list.length === 0) return;
        const first = list[0];
        const last = list[list.length - 1];
        if (ev.shiftKey) {
            if (document.activeElement === first) {
                ev.preventDefault();
                last.focus();
            }
        } else if (document.activeElement === last) {
            ev.preventDefault();
            first.focus();
        }
    }

    function openAvailabilityModal() {
        const root = document.getElementById('availability-modal-root');
        const fab = document.getElementById('availability-fab');
        if (!root || availabilityModalOpen) return;
        availabilityModalOpen = true;
        root.classList.remove('hidden');
        root.setAttribute('aria-hidden', 'false');
        if (fab) fab.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
        document.addEventListener('keydown', onAvailabilityEscape);
        // Load and render chat history when opening
        if (chatHistory.length === 0) {
            loadChatFromStorage();
        }
        if (chatHistory.length > 0) {
            renderChatHistory();
        }
        requestAnimationFrame(function () {
            document.getElementById('availability-chat-input')?.focus();
            updateSendButtonState();
        });
    }

    function closeAvailabilityModal() {
        const root = document.getElementById('availability-modal-root');
        const fab = document.getElementById('availability-fab');
        if (!root || !availabilityModalOpen) return;
        availabilityModalOpen = false;
        root.classList.add('hidden');
        root.setAttribute('aria-hidden', 'true');
        if (fab) {
            fab.setAttribute('aria-expanded', 'false');
            fab.focus();
        }
        document.body.style.overflow = '';
        document.removeEventListener('keydown', onAvailabilityEscape);
    }

    const fabEl = document.getElementById('availability-fab');
    fabEl?.addEventListener('click', function () {
        const root = document.getElementById('availability-modal-root');
        if (!root) return;
        if (!root.classList.contains('hidden')) {
            closeAvailabilityModal();
        } else {
            openAvailabilityModal();
        }
    });

    document.getElementById('availability-modal-close')?.addEventListener('click', closeAvailabilityModal);
    document.getElementById('availability-modal-backdrop')?.addEventListener('click', closeAvailabilityModal);

    document.getElementById('availability-clear-chat')?.addEventListener('click', function () {
        if (askAbort) {
            askAbort.abort();
            askAbort = null;
        }
        chatHistory = [];
        clearChatStorage();
        resetChatUI();
        const meta = document.getElementById('availability-chat-meta');
        if (meta) meta.textContent = '';
        const statusEl = document.getElementById('availability-chat-status');
        if (statusEl) statusEl.textContent = '';
        setLoading(false, statusEl);
    });

    document.getElementById('availability-dialog')?.addEventListener('keydown', trapFocusInDialog);

    document.querySelectorAll('.availability-chip').forEach(function (chip) {
        chip.addEventListener('click', function () {
            const q = chip.getAttribute('data-q') || '';
            const input = document.getElementById('availability-chat-input');
            if (input) input.value = q;
            updateSendButtonState();
            document.getElementById('availability-chat-form')?.requestSubmit();
        });
    });

    const form = document.getElementById('availability-chat-form');
    const input = document.getElementById('availability-chat-input');
    const meta = document.getElementById('availability-chat-meta');
    const statusEl = document.getElementById('availability-chat-status');

    input?.addEventListener('input', updateSendButtonState);

    if (form && input) {
        form.addEventListener('submit', function (ev) {
            ev.preventDefault();
            const q = (input.value || '').trim();
            if (!q) return;
            if (askAbort) {
                askAbort.abort();
            }
            appendChat('user', q);
            const historyPayload = chatHistory.slice();
            input.value = '';
            updateSendButtonState();
            setLoading(true, statusEl);
            if (meta) meta.textContent = '';

            const controller = new AbortController();
            askAbort = controller;
            const t = window.setTimeout(function () {
                controller.abort();
            }, ASK_TIMEOUT_MS);
            const requestStart = Date.now();

            fetch(askUrl, {
                method: 'POST',
                credentials: 'same-origin',
                signal: controller.signal,
                headers: Object.assign(
                    { 'Content-Type': 'application/json', Accept: 'application/json' },
                    csrfHeader()
                ),
                body: JSON.stringify({ message: q, history: historyPayload }),
            })
                .then(function (r) {
                    return r.json().then(function (j) {
                        return { ok: r.ok, j: j };
                    });
                })
                .then(function ({ ok, j }) {
                    window.clearTimeout(t);
                    const elapsed = Date.now() - requestStart;
                    const waitMs = Math.max(0, MIN_REPLY_DELAY_MS - elapsed);
                    window.setTimeout(function () {
                        setLoading(false, statusEl);
                        askAbort = null;
                        if (!ok) {
                            appendChat('assistant', j.error || 'Something went wrong.');
                            return;
                        }
                        chatHistory.push({ role: 'user', content: q });
                        chatHistory.push({ role: 'assistant', content: j.reply || '' });
                        if (chatHistory.length > 10) {
                            chatHistory = chatHistory.slice(-10);
                        }
                        saveChatToStorage();
                        appendAssistantMessage(
                            j.reply || '',
                            j.show_booking_cta === true,
                            j.booking_links || [],
                            j.reply_type === 'login_required' ? j.login_url || loginUrl : null,
                            j.suggested_links || []
                        );
                        if (meta) {
                            if (j.reply_type === 'off_topic') {
                                meta.textContent = '';
                            } else if (j.reply_type === 'schedule_list') {
                                meta.textContent = 'Full list from our published schedule.';
                            } else if (j.reply_type === 'login_required') {
                                meta.textContent = '';
                            } else if (j.reply_type === 'site_guide') {
                                meta.textContent = 'Matched public pages on this site.';
                            } else if (j.reply_type === 'booking_help') {
                                meta.textContent = 'Booking steps from our site (AI not required).';
                            } else if (j.reply_type === 'faq_local') {
                                meta.textContent = 'From our FAQ content (AI not required).';
                            } else if (j.source === 'openai') {
                                meta.textContent =
                                    'From schedule, FAQs, and site map (confirm dates if unsure).';
                            } else if (j.reply_type === 'my_bookings') {
                                meta.textContent = 'From your account bookings on file.';
                            } else {
                                meta.textContent = 'Answer from our published schedule, FAQs, and pages.';
                            }
                        }
                    }, waitMs);
                })
                .catch(function (err) {
                    window.clearTimeout(t);
                    setLoading(false, statusEl);
                    askAbort = null;
                    if (err && err.name === 'AbortError') {
                        appendChat(
                            'assistant',
                            'That request took too long. You can try again, ask for the full schedule, or book from the booking page.'
                        );
                        appendTimeoutActions();
                        return;
                    }
                    appendChat('assistant', 'Network error. Please try again.');
                });
        });
    }

    // Try to load chat history on page load (for other pages that include this element)
    if (loadChatFromStorage() && chatHistory.length > 0) {
        // History loaded, will be rendered when modal opens
    }

    updateSendButtonState();
})();
</script>

