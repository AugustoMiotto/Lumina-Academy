/* Registra o service worker. Carregado com "defer": não bloqueia a renderização. */
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
        navigator.serviceWorker.register('/sw.js').catch(function (erro) {
            console.warn('Lumina: service worker não registrado.', erro);
        });
    });
}
