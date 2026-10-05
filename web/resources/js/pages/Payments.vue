<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { 
    FileText, 
    Wallet, 
    Clock, 
    ArrowDownRight,
    Search,
    Filter,
    MoreVertical,
    ChevronLeft,
    ChevronRight,
    ChevronDown,
    X,
    User,
    Download,
    CheckCircle2
} from 'lucide-vue-next';

const payments = ref([]);
const stats = ref({});
const isLoading = ref(true);

const activeTab = ref('Semua');
const tabs = ['Semua', 'Pending', 'Paid', 'Failed', 'Expired', 'Cancelled', 'Refunded'];

const showDetail = ref(false);
const selectedPayment = ref(null);

const fetchPayments = async () => {
    try {
        isLoading.value = true;
        const { data } = await axios.get('/api/payments');
        payments.value = data.invoices;
        stats.value = data.stats;
    } catch (error) {
        console.error('Failed to fetch payments:', error);
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    fetchPayments();
});

const openDetail = (payment) => {
    selectedPayment.value = payment;
    showDetail.value = true;
};

const closeDetail = () => {
    showDetail.value = false;
    selectedPayment.value = null;
};

const getStatusColor = (status) => {
    switch (status.toLowerCase()) {
        case 'paid': return 'bg-emerald-100 text-emerald-700';
        case 'pending': return 'bg-amber-100 text-amber-700';
        case 'failed': return 'bg-rose-100 text-rose-700';
        case 'expired': return 'bg-slate-100 text-slate-700';
        default: return 'bg-slate-100 text-slate-700';
    }
};
</script>

<template>
    <div class="w-full h-full transition-all duration-300 relative" :class="showDetail ? 'pr-[400px]' : ''">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-800">Pembayaran</h1>
                    <p class="text-sm text-slate-500 mt-1">Kelola semua pembayaran kost, cek status, dan verifikasi pembayaran.</p>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-700 cursor-pointer hover:bg-slate-50">
                        <Clock class="w-4 h-4 text-slate-400" />
                        1 Sep 2025 - 30 Sep 2025
                        <ChevronDown class="w-4 h-4 text-slate-400 ml-1" />
                    </div>
                    
                    <div class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-700 cursor-pointer hover:bg-slate-50">
                        Semua Gedung
                        <ChevronDown class="w-4 h-4 text-slate-400 ml-1" />
                    </div>
                    
                    <button class="flex items-center gap-2 px-4 py-2 bg-[#042B60] hover:bg-[#042B60]/90 text-white rounded-lg text-sm font-bold transition-colors">
                        <span>+</span> Pembayaran Manual
                    </button>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-2xl p-5 border border-slate-200 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                        <FileText class="w-6 h-6 text-blue-600" />
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 mb-1">Total Tagihan</p>
                        <p class="text-lg font-extrabold text-slate-800">{{ stats.total_tagihan || 'Rp 0' }}</p>
                        <p class="text-[11px] font-medium text-slate-400 mt-1">dari {{ stats.total_invoices || 0 }} invoice</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-slate-200 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                        <Wallet class="w-6 h-6 text-emerald-600" />
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 mb-1">Sudah Dibayar</p>
                        <p class="text-lg font-extrabold text-slate-800">{{ stats.sudah_dibayar || 'Rp 0' }}</p>
                        <p class="text-[11px] font-medium text-slate-400 mt-1">{{ stats.sudah_dibayar_pct || 0 }}% dari total</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-slate-200 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                        <Clock class="w-6 h-6 text-amber-600" />
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 mb-1">Pending</p>
                        <p class="text-lg font-extrabold text-slate-800">{{ stats.pending || 'Rp 0' }}</p>
                        <p class="text-[11px] font-medium text-slate-400 mt-1">{{ stats.pending_pct || 0 }}% dari total</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-slate-200 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center shrink-0">
                        <ArrowDownRight class="w-6 h-6 text-rose-600" />
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 mb-1">Gagal / Expired</p>
                        <p class="text-lg font-extrabold text-slate-800">{{ stats.gagal || 'Rp 0' }}</p>
                        <p class="text-[11px] font-medium text-slate-400 mt-1">{{ stats.gagal_pct || 0 }}% dari total</p>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col">
                <!-- Table Toolbar -->
                <div class="p-4 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <!-- Tabs -->
                    <div class="flex items-center gap-1 overflow-x-auto pb-2 lg:pb-0 hide-scrollbar">
                        <button 
                            v-for="tab in tabs" 
                            :key="tab"
                            @click="activeTab = tab"
                            :class="[
                                'px-4 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-colors',
                                activeTab === tab 
                                    ? 'bg-[#042B60] text-white' 
                                    : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700'
                            ]"
                        >
                            {{ tab }}
                        </button>
                    </div>

                    <!-- Search & Filter -->
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <Search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
                            <input 
                                type="text" 
                                placeholder="Cari invoice, nama, atau kamar..."
                                class="pl-9 pr-4 py-2 w-full lg:w-72 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#042B60]/20 focus:border-[#042B60] transition-all"
                            />
                        </div>
                        <button class="flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-lg text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors">
                            <Filter class="w-4 h-4" />
                            Filter
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[1000px]">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="py-4 pl-6 pr-4 w-12">
                                    <input type="checkbox" class="rounded border-slate-300 text-[#042B60] focus:ring-[#042B60]" />
                                </th>
                                <th class="py-4 px-4 text-xs font-bold text-slate-800 uppercase tracking-wider">Invoice</th>
                                <th class="py-4 px-4 text-xs font-bold text-slate-800 uppercase tracking-wider">Penghuni</th>
                                <th class="py-4 px-4 text-xs font-bold text-slate-800 uppercase tracking-wider">Kamar</th>
                                <th class="py-4 px-4 text-xs font-bold text-slate-800 uppercase tracking-wider">Periode</th>
                                <th class="py-4 px-4 text-xs font-bold text-slate-800 uppercase tracking-wider">Total</th>
                                <th class="py-4 px-4 text-xs font-bold text-slate-800 uppercase tracking-wider">Metode</th>
                                <th class="py-4 px-4 text-xs font-bold text-slate-800 uppercase tracking-wider">Status</th>
                                <th class="py-4 px-4 text-xs font-bold text-slate-800 uppercase tracking-wider">Tanggal</th>
                                <th class="py-4 pr-6 pl-4 text-xs font-bold text-slate-800 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody v-if="!isLoading" class="divide-y divide-slate-100">
                            <tr 
                                v-for="payment in payments" 
                                :key="payment.id"
                                @click="openDetail(payment)"
                                class="hover:bg-slate-50/80 transition-colors cursor-pointer group"
                            >
                                <td class="py-4 pl-6 pr-4">
                                    <input type="checkbox" @click.stop class="rounded border-slate-300 text-[#042B60] focus:ring-[#042B60]" />
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-sm font-medium text-slate-500">{{ payment.invoice }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <p class="text-sm font-bold text-slate-800">{{ payment.tenant.name }}</p>
                                    <p class="text-[11px] font-medium text-slate-500 mt-0.5">{{ payment.tenant.phone }}</p>
                                </td>
                                <td class="py-4 px-4">
                                    <p class="text-sm font-bold text-slate-800">{{ payment.room.number }}</p>
                                    <p class="text-[11px] font-medium text-slate-500 mt-0.5">{{ payment.room.desc }}</p>
                                </td>
                                <td class="py-4 px-4">
                                    <p class="text-sm font-medium text-slate-800">{{ payment.period.date }}</p>
                                    <p class="text-[11px] font-medium text-slate-500 mt-0.5">{{ payment.period.duration }}</p>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-sm font-bold text-slate-800">{{ payment.total }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded bg-blue-50 flex items-center justify-center">
                                            <Wallet class="w-3.5 h-3.5 text-blue-600" />
                                        </div>
                                        <span class="text-sm font-medium text-slate-600">{{ payment.method }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span :class="['px-2.5 py-1 rounded-md text-[11px] font-bold flex items-center w-fit gap-1.5', getStatusColor(payment.status)]">
                                        <CheckCircle2 v-if="payment.status === 'Paid'" class="w-3 h-3" />
                                        <Clock v-else-if="payment.status === 'Pending'" class="w-3 h-3" />
                                        <X v-else class="w-3 h-3" />
                                        {{ payment.status }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <p class="text-sm font-medium text-slate-800">{{ payment.date }}</p>
                                    <p class="text-[11px] font-medium text-slate-500 mt-0.5">{{ payment.time }}</p>
                                </td>
                                <td class="py-4 pr-6 pl-4 text-center">
                                    <button @click.stop class="p-1.5 text-slate-400 hover:text-[#042B60] hover:bg-slate-100 rounded-lg transition-colors">
                                        <MoreVertical class="w-4 h-4" />
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="10" class="py-12 text-center text-slate-500 text-sm">
                                    Memuat data...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-4 border-t border-slate-100 flex items-center justify-between">
                    <p class="text-sm text-slate-500 font-medium">Menampilkan 1 - 8 dari 32 data</p>
                    <div class="flex items-center gap-1">
                        <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:bg-slate-50 disabled:opacity-50">
                            <ChevronLeft class="w-4 h-4" />
                        </button>
                        <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#042B60] text-white font-bold text-sm">1</button>
                        <button class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 text-slate-600 font-bold text-sm transition-colors">2</button>
                        <button class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 text-slate-600 font-bold text-sm transition-colors">3</button>
                        <button class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 text-slate-600 font-bold text-sm transition-colors">4</button>
                        <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
                            <ChevronRight class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>
            
        <!-- Sidebar Detail Pembayaran -->
        <div 
            class="fixed top-0 right-0 bottom-0 w-[400px] bg-white border-l border-slate-200 shadow-2xl transform transition-transform duration-300 z-50 flex flex-col"
            :class="showDetail ? 'translate-x-0' : 'translate-x-full'"
            v-if="selectedPayment"
        >
            <div class="flex items-center justify-between p-6 border-b border-slate-100">
                <h2 class="text-lg font-extrabold text-slate-800">Detail Pembayaran</h2>
                <button @click="closeDetail" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                    <X class="w-5 h-5" />
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 space-y-6">
                <!-- Header Status -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                            <FileText class="w-5 h-5 text-blue-600" />
                        </div>
                        <h3 class="font-bold text-slate-800">{{ selectedPayment.invoice }}</h3>
                    </div>
                    <span :class="['px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5', getStatusColor(selectedPayment.status)]">
                        <CheckCircle2 v-if="selectedPayment.status === 'Paid'" class="w-3.5 h-3.5" />
                        <Clock v-else-if="selectedPayment.status === 'Pending'" class="w-3.5 h-3.5" />
                        <X v-else class="w-3.5 h-3.5" />
                        {{ selectedPayment.status }}
                    </span>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-y-4 text-sm">
                    <div>
                        <p class="text-slate-500 font-medium mb-1">Total Tagihan</p>
                        <p class="font-extrabold text-slate-800 text-lg">{{ selectedPayment.total }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 font-medium mb-1">Metode Pembayaran</p>
                        <p class="font-bold text-slate-800">{{ selectedPayment.method }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 font-medium mb-1">Tanggal Pembayaran</p>
                        <p class="font-bold text-slate-800">{{ selectedPayment.date }} {{ selectedPayment.time }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 font-medium mb-1">Dibayar Oleh</p>
                        <p class="font-bold text-slate-800">{{ selectedPayment.tenant.name }}</p>
                    </div>
                </div>

                <!-- Informasi Penghuni -->
                <div>
                    <h4 class="text-sm font-extrabold text-slate-800 mb-3 flex items-center gap-2">
                        <User class="w-4 h-4 text-[#042B60]" />
                        Informasi Penghuni
                    </h4>
                    <div class="bg-slate-50 rounded-xl border border-slate-200 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <img :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${selectedPayment.tenant.name}`" alt="Avatar" class="w-10 h-10 rounded-full border-2 border-white shadow-sm bg-white" />
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ selectedPayment.tenant.name }}</p>
                                    <p class="text-[11px] font-medium text-slate-500 mt-0.5">{{ selectedPayment.tenant.phone }}</p>
                                </div>
                            </div>
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold">{{ selectedPayment.room.number }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-medium text-slate-500">{{ selectedPayment.room.desc }}</p>
                            <button class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50 transition-colors">Lihat Detail</button>
                        </div>
                    </div>
                </div>

                <!-- Rincian Tagihan -->
                <div>
                    <h4 class="text-sm font-extrabold text-slate-800 mb-3 flex items-center gap-2">
                        <FileText class="w-4 h-4 text-[#042B60]" />
                        Rincian Tagihan
                    </h4>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <p class="text-slate-600 font-medium">Sewa Kost (1 bulan)</p>
                            <p class="font-bold text-slate-800">Rp 600.000</p>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-slate-600 font-medium">Biaya Tambahan (Magicom)</p>
                            <p class="font-bold text-slate-800">Rp 25.000</p>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-slate-600 font-medium">Biaya Tambahan (Catokan)</p>
                            <p class="font-bold text-slate-800">Rp 15.000</p>
                        </div>
                        <div class="pt-3 border-t border-slate-200 flex items-center justify-between">
                            <p class="font-bold text-slate-800">Total</p>
                            <p class="font-extrabold text-[#042B60] bg-blue-50 px-3 py-1 rounded-lg">Rp 640.000</p>
                        </div>
                    </div>
                </div>

                <!-- Bukti Pembayaran -->
                <div>
                    <h4 class="text-sm font-extrabold text-slate-800 mb-3 flex items-center gap-2">
                        <Wallet class="w-4 h-4 text-[#042B60]" />
                        Bukti Pembayaran
                    </h4>
                    <div class="flex gap-4 p-4 bg-slate-50 border border-slate-200 rounded-xl">
                        <div class="w-16 h-20 bg-slate-200 rounded shadow-sm border border-slate-300 overflow-hidden shrink-0">
                            <!-- Placeholder image -->
                            <div class="w-full h-full bg-slate-300 flex items-center justify-center">
                                <FileText class="w-6 h-6 text-slate-400" />
                            </div>
                        </div>
                        <div class="flex flex-col justify-center">
                            <p class="text-sm font-bold text-slate-800">IMG_20250901_082400.jpg</p>
                            <p class="text-[11px] font-medium text-slate-500 mt-1">2.4 MB</p>
                            <button class="text-xs font-bold text-blue-600 mt-2 hover:underline flex items-center gap-1 w-fit">
                                <Search class="w-3 h-3" /> Lihat Gambar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50">
                <button class="w-full py-3 bg-white border border-slate-200 text-[#042B60] font-bold text-sm rounded-xl hover:bg-slate-50 transition-colors flex items-center justify-center gap-2">
                    <CheckCircle2 class="w-4 h-4" /> Verifikasi Ulang
                </button>
            </div>
        </div>
        
        <!-- Backdrop for mobile/smaller screens when sidebar is open -->
        <div 
            v-if="showDetail" 
            @click="closeDetail"
            class="fixed inset-0 bg-slate-900/20 z-40 lg:hidden"
        ></div>
    </div>
</template>
