document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('btnNuevaVenta');
    const modal = document.getElementById('nuevaVentaModal');
    const backdrop = document.getElementById('modalBackdrop');
    const closeBtn = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const form = document.getElementById('formNuevaVenta');
    let firstErrorFocused = false;

    function openModal() { if (modal) { modal.classList.remove('hidden'); document.body.style.overflow = 'hidden'; } }
    function closeModal() { if (modal) { modal.classList.add('hidden'); document.body.style.overflow = ''; } }

    btn?.addEventListener('click', openModal);
    closeBtn?.addEventListener('click', closeModal);
    cancelBtn?.addEventListener('click', closeModal);
    backdrop?.addEventListener('click', closeModal);

    form?.addEventListener('submit', async function (e) {
        e.preventDefault();
        clearAllFieldErrors();
        const data = new FormData(form);
        const payload = Object.fromEntries(data.entries());

        // Client-side required validation
        const requiredFields = ['ticket', 'cliente', 'total', 'metodo'];
        let hasClientError = false;
        requiredFields.forEach(name => {
            const val = (form.elements[name] && String(form.elements[name].value || '').trim());
            if (!val) {
                setFieldError(name, 'Este campo es requerido');
                hasClientError = true;
            }
        });
        if (hasClientError) return;

        try {
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            const token = tokenMeta ? tokenMeta.getAttribute('content') : '';
            const res = await fetch('/ventas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const json = await res.json().catch(() => ({}));

            if (res.status === 422 && json.errors) {
                // Server validation errors
                Object.keys(json.errors).forEach(field => {
                    setFieldError(field, json.errors[field].join(' '));
                });
                return;
            }

            if (!res.ok) {
                console.error('Error al guardar venta', json);
                alert('Error: revisa los datos e inténtalo de nuevo');
                return;
            }

            if (json.success) {
                const venta = json.venta;
                const tbody = document.getElementById('ventasTbody');
                if (tbody) {
                    const tr = document.createElement('tr');
                    tr.className = 'border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition';
                    const metodoLabel = {
                        'efectivo': 'Efectivo',
                        'tarjeta': 'Punto / Tarjeta',
                        'transferencia': 'Transferencia'
                    }[venta.metodo] || venta.metodo;

                    tr.innerHTML = `
                        <td class="px-6 py-4 font-bold">${escapeHtml(venta.ticket)}</td>
                        <td class="px-6 py-4">${escapeHtml(venta.cliente)}</td>
                        <td class="px-6 py-4 text-center">${venta.articulo ? 1 : 0}</td>
                        <td class="px-6 py-4 text-right font-bold text-green-700">$ ${Number(venta.total).toFixed(2)}</td>
                        <td class="px-6 py-4 text-center"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-bold">${escapeHtml(metodoLabel)}</span></td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-[#4A5B6A] hover:text-[#263A47] mx-1 transition-colors" title="Ver Detalle"><i class="ph ph-eye text-xl"></i></button>
                            <button class="text-[#4A5B6A] hover:text-[#263A47] mx-1 transition-colors" title="Imprimir"><i class="ph ph-printer text-xl"></i></button>
                        </td>
                    `;
                    tbody.prepend(tr);
                }

                form.reset();
                clearAllFieldErrors();
                closeModal();
            }
        } catch (error) {
            console.error('Exception al guardar venta', error);
            alert('Error inesperado al guardar la venta');
        }
    });

    function escapeHtml(str) {
        if (str === null || typeof str === 'undefined') return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function setFieldError(name, message) {
        const el = document.querySelector('[data-error-for="' + name + '"]');
        if (el) { el.textContent = message; el.classList.remove('hidden'); }
        // add red border to the corresponding input/select/textarea
        const input = form.elements[name] || document.querySelector('[name="' + name + '"]');
        if (input) {
            input.classList.add('border-red-500', 'ring-1', 'ring-red-300');
            if (!firstErrorFocused) {
                try { input.focus(); } catch (e) {}
                firstErrorFocused = true;
            }
        }
    }

    function clearFieldError(name) {
        const el = document.querySelector('[data-error-for="' + name + '"]');
        if (el) { el.textContent = ''; el.classList.add('hidden'); }
        const input = form.elements[name] || document.querySelector('[name="' + name + '"]');
        if (input) {
            input.classList.remove('border-red-500', 'ring-1', 'ring-red-300');
        }
    }

    function clearAllFieldErrors() {
        document.querySelectorAll('[data-error-for]').forEach(el => { el.textContent = ''; el.classList.add('hidden'); });
        // remove red border from all inputs in the form
        if (form) {
            ['border-red-500', 'ring-1', 'ring-red-300'].forEach(cls => {
                form.querySelectorAll('input, select, textarea').forEach(inp => inp.classList.remove(cls));
            });
            firstErrorFocused = false;
        }
    }
});
