/**
 * public/js/antrian.js
 * Logika real-time polling untuk Dashboard Admin (Modern UI).
 */

function refreshAntrianAdmin() {
    const tableBody = document.querySelector('#antrian-table tbody');
    if (!tableBody) return;

    fetch(window.location.href, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
        .then(res => res.json())
        .then(data => {
            if (!data.antrians) return;

            let newContent = '';
            const statusGroups = ['menunggu', 'diproses', 'selesai', 'batal'];
            let hasData = false;

            statusGroups.forEach(status => {
                if (data.antrians[status]) {
                    hasData = true;
                    data.antrians[status].forEach(a => {
                        newContent += renderRow(a);
                    });
                }
            });

            if (!hasData) {
                newContent = `
                <tr>
                    <td colspan="6" class="px-8 py-20 text-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line></svg>
                        </div>
                        <p class="text-slate-400 font-bold text-sm">Belum ada antrian hari ini</p>
                    </td>
                </tr>`;
            }

            tableBody.innerHTML = newContent;
        })
        .catch(err => console.error('Error polling data:', err));
}

function renderRow(a) {
    const statusClasses = {
        'menunggu': 'bg-amber-50 text-amber-600 border-amber-100',
        'diproses': 'bg-blue-50 text-blue-600 border-blue-100',
        'selesai': 'bg-emerald-50 text-emerald-600 border-emerald-100',
        'batal': 'bg-slate-100 text-slate-500 border-slate-200',
    };

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    let aksi = `<div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">`;

    if (a.status === 'menunggu') {
        aksi += `
            <form action="/admin/antrian/${a.id}/proses" method="POST">
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="PATCH">
                <button type="submit" class="p-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"></polyline><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>
                </button>
            </form>`;
    }

    if (a.status === 'diproses') {
        aksi += `
            <form action="/admin/antrian/${a.id}/selesai" method="POST">
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="PATCH">
                <button type="submit" class="p-2.5 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 shadow-lg shadow-emerald-500/20 transition-all">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </button>
            </form>`;
    }

    if (['menunggu', 'diproses'].includes(a.status)) {
        aksi += `
            <form action="/admin/antrian/${a.id}" method="POST" onsubmit="return confirm('Batalkan antrian ini?')">
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="p-2.5 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                </button>
            </form>`;
    }
    aksi += `</div>`;

    const vehicleClass = a.jenis_kendaraan === 'motor' ? 'bg-amber-100 text-amber-700' : 'bg-indigo-100 text-indigo-700';

    return `
        <tr class="hover:bg-slate-50/30 transition-colors group">
            <td class="px-8 py-6">
                <span class="text-xl font-black text-slate-900 tracking-tighter">${a.nomor_antrian}</span>
            </td>
            <td class="px-8 py-6">
                <p class="font-bold text-slate-800 text-sm tracking-tight">${a.nama_pelanggan}</p>
                <p class="text-xs text-slate-400 font-medium">${a.user?.email || '-'}</p>
            </td>
            <td class="px-8 py-6">
                <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider ${vehicleClass}">${a.jenis_kendaraan}</span>
                <p class="text-xs font-black text-slate-600 font-mono tracking-widest uppercase mt-1">${a.no_plat}</p>
            </td>
            <td class="px-8 py-6">
                <p class="text-sm font-semibold text-slate-600 tracking-tight">${a.jenis_layanan?.nama_layanan || '-'}</p>
            </td>
            <td class="px-8 py-6">
                <span class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest border ${statusClasses[a.status]}">
                    ${a.status}
                </span>
            </td>
            <td class="px-8 py-6 text-right">${aksi}</td>
        </tr>
    `;
}

setInterval(refreshAntrianAdmin, 10000);
