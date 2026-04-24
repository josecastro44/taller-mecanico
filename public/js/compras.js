// JS para controlar el modal y formulario de "Nueva Compra"
document.addEventListener('DOMContentLoaded', function () {
    const btnNueva = document.getElementById('btnNuevaCompra');
    const modal = document.getElementById('nuevaCompraModal');
    const backdrop = document.getElementById('modalBackdropCompra');
    const closeBtn = document.getElementById('closeModalCompra');
    const cancelBtn = document.getElementById('cancelCompraBtn');
    const form = document.getElementById('formNuevaCompra');
    const tbody = document.getElementById('comprasTbody');
    const errorBanner = document.getElementById('modalErrorBannerCompra');
    const errorMessage = document.getElementById('modalErrorMessageCompra');

    if (!btnNueva || !modal || !form) return;

    function openModal() {
        modal.classList.remove('hidden');
        // focus first input
        const first = modal.querySelector('input, select, textarea');
        if (first) first.focus();
    }

    function closeModal() {
        modal.classList.add('hidden');
        clearErrors();
        form.reset();
        hideBanner();
    }

    function showBanner(msg) {
        if (!errorBanner) return;
        errorMessage.textContent = msg || 'Error inesperado';
        errorBanner.classList.remove('hidden');
    }

    function hideBanner() {
        if (!errorBanner) return;
        errorBanner.classList.add('hidden');
        errorMessage.textContent = '';
    }

    function setFieldError(name, msg) {
        const span = form.querySelector(`[data-error-for="${name}"]`);
        const input = form.querySelector(`[name="${name}"]`);
        if (span) {
            span.textContent = msg;
            span.classList.remove('hidden');
        }
        if (input) {
            input.classList.add('border-red-600');
        }
    }

    function clearErrors() {
        form.querySelectorAll('[data-error-for]').forEach(s => {
            s.classList.add('hidden');
            s.textContent = '';
        });
        form.querySelectorAll('.border-red-600').forEach(i => i.classList.remove('border-red-600'));
    }

    btnNueva.addEventListener('click', openModal);
    closeBtn && closeBtn.addEventListener('click', closeModal);
    cancelBtn && cancelBtn.addEventListener('click', closeModal);
    backdrop && backdrop.addEventListener('click', closeModal);

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        clearErrors();
        hideBanner();

        const payload = {
            orden: form.orden.value.trim(),
            proveedor: form.proveedor.value.trim(),
            fecha: form.fecha.value,
            monto: form.monto.value,
            estado: form.estado.value,
            acciones: form.acciones ? form.acciones.value.trim() : ''
        };

        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const token = tokenMeta ? tokenMeta.getAttribute('content') : '';

        fetch('/compras', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify(payload)
        }).then(async res => {
            if (res.status === 422) {
                const data = await res.json().catch(() => ({}));
                if (data.errors) {
                    Object.keys(data.errors).forEach(k => setFieldError(k, data.errors[k][0] || data.errors[k]));
                } else {
                    showBanner('Datos inválidos. Revise el formulario.');
                }
                return;
            }
            if (!res.ok) {
                const txt = await res.text().catch(() => 'Error en el servidor');
                showBanner(txt);
                return;
            }
            const data = await res.json().catch(() => null);
            if (data && data.success) {
                // Insertar fila en la tabla (no persiste si el servidor no guarda)
                const c = data.compra || payload;
                const tr = document.createElement('tr');
                tr.className = 'border-b border-[#B4C5D8]/30 hover:bg-[#B4C5D8]/10 transition';
                tr.innerHTML = `
                    <td class="px-6 py-4 font-bold">${escapeHtml(c.orden)}</td>
                    <td class="px-6 py-4">${escapeHtml(c.proveedor)}</td>
                    <td class="px-6 py-4 text-[#728495]">${escapeHtml(formatDate(c.fecha))}</td>
                    <td class="px-6 py-4 text-right font-bold text-red-600">$ ${Number(c.monto).toFixed(2)}</td>
                    <td class="px-6 py-4 text-center">${renderEstado(c.estado)}</td>
                    <td class="px-6 py-4 text-center"><button class="text-[#4A5B6A] hover:text-[#263A47] mx-1 transition-colors" title="Ver Detalles"><i class="ph ph-eye text-xl"></i></button></td>
                `;
                if (tbody) tbody.prepend(tr);
                closeModal();
            } else {
                showBanner('Respuesta inesperada del servidor');
            }
        }).catch(err => {
            showBanner('No se pudo conectar al servidor');
            console.error(err);
        });
    });

    function renderEstado(e) {
        if (!e) return '';
        if (e === 'recibido') return '<span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">Recibido</span>';
        if (e === 'en_transito') return '<span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold">En Tránsito</span>';
        return '<span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-bold">Pendiente</span>';
    }

    function formatDate(d) {
        if (!d) return '';
        try {
            const dt = new Date(d);
            return dt.toLocaleDateString();
        } catch (e) {
            return d;
        }
    }

    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str).replace(/[&<>"'`]/g, function (s) {
            return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;","`":"&#96;"})[s];
        });
    }
});
