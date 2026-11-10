
<dialog
    id="global-delete-modal"
    class="m-auto w-[calc(100%-2rem)] max-w-md rounded-2xl border-0 bg-white p-0 text-slate-800 shadow-2xl backdrop:bg-slate-950/50"
>
    <div class="p-6">

        <div class="flex items-start justify-between mb-5">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="22" height="22" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M3 6h18"/>
                        <path d="M8 6V4h8v2"/>
                        <path d="m19 6-1 14H6L5 6"/>
                        <path d="M10 11v5"/>
                        <path d="M14 11v5"/>
                    </svg>
                </div>

                <div>
                    <p class="text-[10px] font-bold tracking-widest text-red-600 uppercase">
                        Aksi Kritis • Perlu Konfirmasi
                    </p>
                    <h2 class="text-lg font-bold text-slate-900">
                        Konfirmasi Penghapusan
                    </h2>
                </div>
            </div>

            <button
                type="button"
                data-delete-close
                class="text-slate-400 hover:text-slate-700 text-2xl leading-none"
                aria-label="Tutup"
            >
                &times;
            </button>
        </div>

        <p class="text-sm text-slate-600 leading-relaxed">
            Apakah Anda yakin ingin menghapus
            <strong id="delete-target-name" class="text-slate-900">
                data ini
            </strong>?
            Tindakan ini tidak dapat dibatalkan.
        </p>

        <div class="mt-5 rounded-lg border border-amber-200 bg-amber-50 p-3">
            <p class="text-xs text-amber-800 leading-relaxed">
                <strong>Perhatian:</strong> Pastikan data yang dipilih
                memang sudah tidak diperlukan. Data yang memiliki
                riwayat terkait mungkin tidak dapat dihapus.
            </p>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <button
                type="button"
                data-delete-cancel
                class="px-4 py-2 rounded-lg border border-slate-300 text-sm font-medium text-slate-600 hover:bg-slate-50"
            >
                Batal
            </button>

            <button
                type="button"
                id="confirm-delete-button"
                class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-semibold hover:bg-red-700 disabled:opacity-50"
            >
                Ya, Hapus Data
            </button>
        </div>

        <form id="global-delete-form" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>

    </div>
</dialog>
