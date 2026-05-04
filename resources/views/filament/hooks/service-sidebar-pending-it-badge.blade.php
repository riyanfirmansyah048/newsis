{{-- Badge warning "Menunggu Konfirmasi IT" di sidebar, di samping badge PIC (danger) --}}
<script>
(function () {
    const count = @json($count);
    const pathSuffix = @json($path);

    function pathnameMatches(href) {
        try {
            const u = new URL(href, window.location.origin);

            return u.pathname === pathSuffix || u.pathname.endsWith(pathSuffix);
        } catch (e) {
            return false;
        }
    }

    function paint() {
        document.querySelectorAll('[data-pending-it-service-badge]').forEach((el) => el.remove());

        const links = document.querySelectorAll('.fi-sidebar-nav a.fi-sidebar-item-btn[href]');
        for (const a of links) {
            if (!pathnameMatches(a.href)) {
                continue;
            }

            let host = a.querySelector('.fi-sidebar-item-badge-ctn');
            if (!host) {
                host = document.createElement('span');
                host.className =
                    'fi-sidebar-item-badge-ctn flex items-center gap-1';
                a.appendChild(host);
            }

            const pill = document.createElement('span');
            pill.dataset.pendingItServiceBadge = '1';
            pill.className = 'fi-badge fi-size-sm fi-color-warning';
            pill.textContent = String(count);
            pill.setAttribute('title', 'Menunggu Konfirmasi IT');
            pill.setAttribute('aria-label', 'Service menunggu konfirmasi IT: ' + count);

            host.insertBefore(pill, host.firstChild);
            break;
        }
    }

    document.addEventListener('DOMContentLoaded', paint);
    document.addEventListener('livewire:navigated', paint);
})();
</script>
