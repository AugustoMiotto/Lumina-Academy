/*
 * Service Worker — Lumina Academy
 *
 * Estratégia deliberadamente simples (o app é SSR com sessão e CSRF):
 *  - Páginas HTML NUNCA são guardadas em cache (evita token CSRF vencido / erro 419
 *    e conteúdo de outro usuário). Vão sempre pela rede.
 *  - Se a rede falhar numa navegação, mostra /offline.html.
 *  - Só arquivos estáticos (CSS, JS, ícones) são guardados e servidos do cache.
 *  - Requisições que não são GET (formulários POST) passam direto, sem interferência.
 *
 * Ao mudar qualquer arquivo da lista PRECACHE, aumente CACHE_VERSION.
 */
const CACHE_VERSION = 'v1';
const CACHE_NAME = 'lumina-static-' + CACHE_VERSION;

const PRECACHE = [
    '/offline.html',
    '/css/app.css',
    '/js/pwa-register.js',
    '/manifest.json',
    '/icons/icon-192.png',
    '/icons/icon-512.png',
    '/icons/apple-touch-icon.png',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => cache.addAll(PRECACHE))
            .then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(
                keys
                    .filter((key) => key.startsWith('lumina-static-') && key !== CACHE_NAME)
                    .map((key) => caches.delete(key))
            ))
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    const request = event.request;

    // Formulários (POST) e qualquer método diferente de GET: não mexe.
    if (request.method !== 'GET') {
        return;
    }

    const url = new URL(request.url);

    // Só trata o mesmo domínio.
    if (url.origin !== self.location.origin) {
        return;
    }

    // Navegação (páginas): rede primeiro; sem rede, página offline.
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request).catch(() => caches.match('/offline.html'))
        );
        return;
    }

    // Arquivos estáticos: cache primeiro, atualizando em segundo plano.
    if (PRECACHE.includes(url.pathname)) {
        event.respondWith(
            caches.match(request).then((cached) => {
                const refresh = fetch(request)
                    .then((response) => {
                        if (response && response.ok) {
                            const copy = response.clone();
                            caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
                        }
                        return response;
                    })
                    .catch(() => cached);

                return cached || refresh;
            })
        );
    }
    // Todo o resto (ex.: /up, /certificates/verify/...) segue o fluxo normal da rede.
});
