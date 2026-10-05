<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { 
    Search, 
    Filter, 
    MoreVertical, 
    Eye, 
    Edit,
    RotateCcw,
    Users,
    BadgeCheck,
    AlertTriangle,
    ClipboardCheck,
    Plus,
    CheckCircle2,
    Clock,
    AlertCircle,
    MessageCircle,
    ChevronDown
} from 'lucide-vue-next';

const tenants = ref([]);
const totalRooms = 38;

const fetchTenants = async () => {
    try {
        const response = await axios.get('/api/tenants');
        if (response.data.status === 'success') {
            tenants.value = response.data.data;
        }
    } catch (error) {
        console.error("Error fetching tenants:", error);
    }
};

onMounted(() => {
    fetchTenants();
});

const stats = computed(() => {
    const total = tenants.value.length;
    const aktif = tenants.value.filter(t => t.status.text === 'Aktif').length;
    const berakhir = tenants.value.filter(t => t.status.text === 'Akan Checkout' || t.status.text === 'Berakhir').length;
    const checkout = tenants.value.filter(t => t.status.text === 'Checkout').length;
    
    return {
        total,
        aktif,
        berakhir,
        checkout
    };
});

</script>

<template>
    <div class="w-full h-full">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-[#042B60] tracking-tight">Penghuni</h1>
                <p class="text-slate-500 mt-1.5 text-sm">Kelola data seluruh penghuni kost dengan mudah dan lengkap.</p>
            </div>
            <button class="bg-[#042B60] hover:bg-[#042B60]/90 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2 shadow-sm">
                <Plus class="w-4 h-4" /> Tambah Penghuni
            </button>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <!-- Total -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-[#042B60]/10 text-[#042B60] flex items-center justify-center shrink-0">
                    <Users class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 mb-0.5">Total Penghuni</p>
                    <p class="text-2xl font-extrabold text-[#042B60] leading-none">{{ stats.total }}</p>
                    <p class="text-[11px] font-medium text-slate-400 mt-1">Dari {{ totalRooms }} kamar</p>
                </div>
            </div>
            <!-- Aktif -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                    <BadgeCheck class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 mb-0.5">Penghuni Aktif</p>
                    <p class="text-2xl font-extrabold text-[#042B60] leading-none">{{ stats.aktif }}</p>
                    <p class="text-[11px] font-medium text-slate-400 mt-1">Sedang tinggal</p>
                </div>
            </div>
            <!-- Berakhir -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                    <AlertTriangle class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 mb-0.5">Akan Berakhir</p>
                    <p class="text-2xl font-extrabold text-[#042B60] leading-none">{{ stats.berakhir }}</p>
                    <p class="text-[11px] font-medium text-slate-400 mt-1">Dalam 7 hari</p>
                </div>
            </div>
            <!-- Checkout -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                    <ClipboardCheck class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 mb-0.5">Sudah Checkout</p>
                    <p class="text-2xl font-extrabold text-[#042B60] leading-none">{{ stats.checkout }}</p>
                    <p class="text-[11px] font-medium text-slate-400 mt-1">Selesai masa sewa</p>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <!-- Toolbar -->
            <div class="p-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4">
                <div class="relative w-80">
                    <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                    <input 
                        type="text" 
                        placeholder="Cari nama, nomor WhatsApp, atau kamar..." 
                        class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#042B60]/20 focus:border-[#042B60]/50"
                    >
                </div>
                <div class="flex items-center gap-3">
                    <select class="border border-slate-200 bg-white rounded-lg px-3 py-2 text-sm font-medium text-slate-600 focus:outline-none focus:border-[#042B60]/50">
                        <option>Semua Gedung</option>
                    </select>
                    <select class="border border-slate-200 bg-white rounded-lg px-3 py-2 text-sm font-medium text-slate-600 focus:outline-none focus:border-[#042B60]/50">
                        <option>Semua Status</option>
                    </select>
                    <select class="border border-slate-200 bg-white rounded-lg px-3 py-2 text-sm font-medium text-slate-600 focus:outline-none focus:border-[#042B60]/50">
                        <option>Semua Pembayaran</option>
                    </select>
                    
                    <button class="bg-[#042B60] text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm hover:bg-[#042B60]/90 transition-colors">
                        <Filter class="w-4 h-4" /> Filter
                    </button>
                    <button class="text-slate-600 border border-slate-200 px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                        <RotateCcw class="w-4 h-4" /> Reset
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-4 font-bold text-[11px] uppercase tracking-wider text-slate-500">No</th>
                            <th class="px-4 py-4 font-bold text-[11px] uppercase tracking-wider text-slate-500">Nama</th>
                            <th class="px-4 py-4 font-bold text-[11px] uppercase tracking-wider text-slate-500">WhatsApp</th>
                            <th class="px-4 py-4 font-bold text-[11px] uppercase tracking-wider text-slate-500">Gedung</th>
                            <th class="px-4 py-4 font-bold text-[11px] uppercase tracking-wider text-slate-500">Kamar</th>
                            <th class="px-4 py-4 font-bold text-[11px] uppercase tracking-wider text-slate-500">Check-in</th>
                            <th class="px-4 py-4 font-bold text-[11px] uppercase tracking-wider text-slate-500">Check-out</th>
                            <th class="px-4 py-4 font-bold text-[11px] uppercase tracking-wider text-slate-500">Durasi</th>
                            <th class="px-4 py-4 font-bold text-[11px] uppercase tracking-wider text-slate-500">Total</th>
                            <th class="px-4 py-4 font-bold text-[11px] uppercase tracking-wider text-slate-500">Pembayaran</th>
                            <th class="px-4 py-4 font-bold text-[11px] uppercase tracking-wider text-slate-500">Status</th>
                            <th class="px-4 py-4 font-bold text-[11px] uppercase tracking-wider text-slate-500">Catatan</th>
                            <th class="px-5 py-4 font-bold text-[11px] uppercase tracking-wider text-slate-500 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="(tenant, index) in tenants" :key="tenant.id" class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-4 py-4 font-medium text-slate-400">{{ index + 1 }}</td>
                            <td class="px-4 py-4">
                                <p class="font-bold text-[#042B60]">{{ tenant.name }}</p>
                                <p class="text-xs font-medium text-slate-400 mt-0.5">{{ tenant.occupants }} orang</p>
                            </td>
                            <td class="px-4 py-4">
                                <div class="font-medium text-slate-600">
                                    {{ tenant.whatsapp }}
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-semibold text-slate-700">{{ tenant.building }}</p>
                                <p v-if="tenant.floor" class="text-xs font-medium text-slate-400 mt-0.5">Lantai {{ tenant.floor }}</p>
                            </td>
                            <td class="px-4 py-4 font-bold text-slate-800">
                                {{ tenant.room }}
                            </td>
                            <td class="px-4 py-4 font-medium text-slate-500">{{ tenant.check_in }}</td>
                            <td class="px-4 py-4 font-medium text-slate-500">{{ tenant.check_out }}</td>
                            <td class="px-4 py-4 font-medium text-slate-500 capitalize">{{ tenant.duration }}</td>
                            <td class="px-4 py-4 font-semibold text-slate-800">{{ tenant.total }}</td>
                            <td class="px-4 py-4">
                                <button v-if="tenant.payment.type === 'success'" class="inline-flex items-center justify-between px-2.5 py-1 rounded bg-emerald-500 text-white text-[11px] font-bold shadow-sm hover:bg-emerald-600 transition-colors cursor-pointer group w-full max-w-[100px]">
                                    <span>LUNAS</span>
                                    <ChevronDown class="w-3.5 h-3.5 ml-1.5 opacity-70 group-hover:opacity-100" />
                                </button>
                                <button v-else-if="tenant.payment.type === 'info'" class="inline-flex items-center justify-between px-2.5 py-1 rounded bg-blue-600 text-white text-[11px] font-bold shadow-sm hover:bg-blue-700 transition-colors cursor-pointer group w-full max-w-[100px]">
                                    <span>PENDING</span>
                                    <ChevronDown class="w-3.5 h-3.5 ml-1.5 opacity-70 group-hover:opacity-100" />
                                </button>
                                <button v-else class="inline-flex items-center justify-between px-2.5 py-1 rounded bg-amber-500 text-white text-[11px] font-bold shadow-sm hover:bg-amber-600 transition-colors cursor-pointer group w-full max-w-[100px]">
                                    <span>VERIFIKASI</span>
                                    <ChevronDown class="w-3.5 h-3.5 ml-1.5 opacity-70 group-hover:opacity-100" />
                                </button>
                            </td>
                            <td class="px-4 py-4">
                                <button v-if="tenant.status.type === 'success'" class="inline-flex items-center justify-between px-2.5 py-1.5 rounded-md bg-emerald-500 text-white text-[11px] font-bold hover:bg-emerald-600 transition-colors cursor-pointer group w-full max-w-[100px] shadow-sm">
                                    <span>AKTIF</span>
                                    <ChevronDown class="w-3.5 h-3.5 ml-1.5 opacity-70 group-hover:opacity-100" />
                                </button>
                                <button v-else-if="tenant.status.type === 'warning'" class="inline-flex items-center justify-between px-2.5 py-1.5 rounded-md bg-amber-500 text-white text-[11px] font-bold hover:bg-amber-600 transition-colors cursor-pointer group w-full max-w-[100px] shadow-sm">
                                    <span>BERAKHIR</span>
                                    <ChevronDown class="w-3.5 h-3.5 ml-1.5 opacity-70 group-hover:opacity-100" />
                                </button>
                                <button v-else class="inline-flex items-center justify-between px-2.5 py-1.5 rounded-md bg-slate-500 text-white text-[11px] font-bold hover:bg-slate-600 transition-colors cursor-pointer group w-full max-w-[100px] shadow-sm">
                                    <span>CHECKOUT</span>
                                    <ChevronDown class="w-3.5 h-3.5 ml-1.5 opacity-70 group-hover:opacity-100" />
                                </button>
                            </td>
                            <td class="px-4 py-4 font-medium text-slate-500 max-w-[120px] truncate" :title="tenant.notes">
                                {{ tenant.notes }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <button class="text-blue-600 hover:text-blue-800 text-xs font-bold hover:underline transition-all">Detail</button>
                                    <button class="text-amber-600 hover:text-amber-800 text-xs font-bold hover:underline transition-all">Edit</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-slate-100 flex items-center justify-between text-sm font-medium text-slate-500 bg-slate-50">
                <p>Menampilkan 1 - 8 dari 32 data</p>
                <div class="flex items-center gap-1.5">
                    <button class="w-8 h-8 flex items-center justify-center border border-slate-200 rounded-md hover:bg-white text-slate-400 bg-transparent transition-colors">&lt;</button>
                    <button class="w-8 h-8 flex items-center justify-center bg-[#042B60] text-white rounded-md font-bold shadow-sm">1</button>
                    <button class="w-8 h-8 flex items-center justify-center border border-slate-200 rounded-md hover:bg-white hover:text-slate-700 bg-transparent transition-colors">2</button>
                    <button class="w-8 h-8 flex items-center justify-center border border-slate-200 rounded-md hover:bg-white hover:text-slate-700 bg-transparent transition-colors">3</button>
                    <button class="w-8 h-8 flex items-center justify-center border border-slate-200 rounded-md hover:bg-white hover:text-slate-700 bg-transparent transition-colors">4</button>
                    <button class="w-8 h-8 flex items-center justify-center border border-slate-200 rounded-md hover:bg-white text-slate-600 bg-transparent transition-colors">&gt;</button>
                </div>
            </div>
        </div>
    </div>
</template>
