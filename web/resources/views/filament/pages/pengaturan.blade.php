<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Harga Kost --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold">Harga Kost</h3>
                        <p class="text-sm text-gray-500">Atur harga per gedung dan tipe kamar</p>
                    </div>
                </div>
                <div class="text-sm text-gray-600 mb-4">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Putri (1 orang)</span>
                            <span class="font-semibold">Rp 600.000</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Putri (2 orang)</span>
                            <span class="font-semibold">Rp 350.000/orang</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Putra (1 orang)</span>
                            <span class="font-semibold">Rp 600.000</span>
                        </div>
                    </div>
                </div>
                <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    Kelola Harga →
                </button>
            </div>

            {{-- Biaya Tambahan --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold">Biaya Tambahan</h3>
                        <p class="text-sm text-gray-500">Kelola biaya tambahan seperti magicom, dll</p>
                    </div>
                </div>
                <div class="text-sm text-gray-600 mb-4">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Magicom</span>
                            <span class="font-semibold">Rp 25.000</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Catokan</span>
                            <span class="font-semibold">Rp 15.000</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Kulkas</span>
                            <span class="font-semibold">Rp 50.000</span>
                        </div>
                    </div>
                </div>
                <button class="text-yellow-600 hover:text-yellow-700 text-sm font-medium">
                    Kelola Biaya →
                </button>
            </div>

            {{-- Metode Pembayaran --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold">Metode Pembayaran</h3>
                        <p class="text-sm text-gray-500">Atur rekening bank dan QRIS</p>
                    </div>
                </div>
                <div class="text-sm text-gray-600 mb-4">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Aktif</span>
                            <span>Transfer Bank BCA</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Aktif</span>
                            <span>QRIS</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">Nonaktif</span>
                            <span>E-Wallet</span>
                        </div>
                    </div>
                </div>
                <button class="text-green-600 hover:text-green-700 text-sm font-medium">
                    Kelola Metode →
                </button>
            </div>

            {{-- WhatsApp --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold">WhatsApp</h3>
                        <p class="text-sm text-gray-500">Konfigurasi bot dan reminder</p>
                    </div>
                </div>
                <div class="text-sm text-gray-600 mb-4">
                    <div class="space-y-2">
                        <div>Status Bot: <span class="text-green-600 font-semibold">Terhubung</span></div>
                        <div>Reminder H-2: <span class="text-green-600">Aktif</span></div>
                        <div>Reminder H-1: <span class="text-green-600">Aktif</span></div>
                        <div>Reminder H: <span class="text-green-600">Aktif</span></div>
                    </div>
                </div>
                <button class="text-purple-600 hover:text-purple-700 text-sm font-medium">
                    Kelola WhatsApp →
                </button>
            </div>
        </div>

        {{-- Info --}}
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex gap-3">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Pengaturan Sistem</p>
                    <p>Halaman pengaturan detail untuk masing-masing fitur sedang dalam pengembangan. Saat ini data masih menggunakan nilai default.</p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
