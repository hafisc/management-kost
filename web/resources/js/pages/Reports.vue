<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { 
    Calendar,
    Building2,
    DoorClosed,
    Download,
    TrendingUp,
    TrendingDown,
    FileText,
    Wallet,
    Receipt,
    CreditCard,
    MoreVertical,
    Eye,
    ChevronLeft,
    ChevronRight,
    Search,
    Lightbulb,
    BedDouble,
    ArrowUpRight,
    ArrowDownRight,
    ChevronDown
} from 'lucide-vue-next';

const data = ref(null);
const isLoading = ref(true);
const activeTab = ref('Pemasukan');

const fetchReports = async () => {
    try {
        isLoading.value = true;
        const response = await axios.get('/api/reports');
        data.value = response.data;
    } catch (error) {
        console.error('Failed to fetch reports:', error);
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    fetchReports();
});

// SVG Line Chart Helpers
const maxChartValue = computed(() => {
    if (!data.value) return 0;
    const allVals = [...data.value.charts.line.pemasukan, ...data.value.charts.line.pengeluaran];
    return Math.max(...allVals) * 1.2; // 20% padding top
});

const getLinePoints = (dataArray) => {
    if (!dataArray || dataArray.length === 0) return '';
    const width = 600;
    const height = 150;
    const xStep = width / (dataArray.length - 1);
    
    return dataArray.map((val, index) => {
        const x = index * xStep;
        const y = height - ((val / maxChartValue.value) * height);
        return `${x},${y}`;
    }).join(' ');
};

// SVG Donut Chart Helpers
const donutCircumference = 2 * Math.PI * 40; // r=40
const getDonutSegments = computed(() => {
    if (!data.value) return [];
    let currentOffset = 0;
    const total = 100; // Since values are percentages summing to 100
    
    return data.value.charts.donut.map(segment => {
        const dashArray = (segment.value / total) * donutCircumference;
        const result = {
            ...segment,
            dashArray: `${dashArray} ${donutCircumference}`,
            dashOffset: -currentOffset
        };
        currentOffset += dashArray;
        return result;
    });
});

</script>

<template>
    <div class="w-full h-full transition-all duration-300">
        <!-- Main Layout with Right Sidebar -->
        <div class="flex flex-col xl:flex-row gap-6">
            <!-- Left Main Content -->
            <div class="flex-1 min-w-0">
                <!-- Header -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8">
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-800">Laporan</h1>
                        <p class="text-sm text-slate-500 mt-1">Pantau pemasukan, pengeluaran, dan rekap data kost secara lengkap.</p>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-700 cursor-pointer hover:bg-slate-50">
                            <Calendar class="w-4 h-4 text-slate-400" />
                            1 Sep 2025 - 30 Sep 2025
                            <ChevronDown class="w-4 h-4 text-slate-400 ml-1" />
                        </div>
                        
                        <div class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-700 cursor-pointer hover:bg-slate-50">
                            Semua Gedung
                            <ChevronDown class="w-4 h-4 text-slate-400 ml-1" />
                        </div>
                        
                        <div class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-700 cursor-pointer hover:bg-slate-50">
                            Semua Kamar
                            <ChevronDown class="w-4 h-4 text-slate-400 ml-1" />
                        </div>
                        
                        <button class="flex items-center gap-2 px-4 py-2 bg-[#042B60] hover:bg-[#042B60]/90 text-white rounded-lg text-sm font-bold transition-colors">
                            <Download class="w-4 h-4" /> Export Laporan
                            <ChevronDown class="w-4 h-4 ml-1 opacity-70" />
                        </button>
                    </div>
                </div>

                <div v-if="!isLoading && data">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <!-- Pemasukan -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-200">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                                    <Wallet class="w-6 h-6 text-emerald-600" />
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-500 mb-1">Total Pemasukan</p>
                                    <p class="text-lg font-extrabold text-slate-800">{{ data.stats.pemasukan }}</p>
                                    <p class="text-[11px] font-medium text-emerald-600 mt-1 flex items-center gap-1">
                                        <TrendingUp class="w-3 h-3" /> {{ data.stats.pemasukan_trend }} <span class="text-slate-400">dari bulan lalu</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Pengeluaran -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-200">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center shrink-0">
                                    <Receipt class="w-6 h-6 text-rose-600" />
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-500 mb-1">Total Pengeluaran</p>
                                    <p class="text-lg font-extrabold text-slate-800">{{ data.stats.pengeluaran }}</p>
                                    <p class="text-[11px] font-medium text-rose-600 mt-1 flex items-center gap-1">
                                        <TrendingUp class="w-3 h-3" /> {{ data.stats.pengeluaran_trend }} <span class="text-slate-400">dari bulan lalu</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Saldo -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-200">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                                    <CreditCard class="w-6 h-6 text-blue-600" />
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-500 mb-1">Saldo Bersih</p>
                                    <p class="text-lg font-extrabold text-slate-800">{{ data.stats.saldo }}</p>
                                    <p class="text-[11px] font-medium text-emerald-600 mt-1 flex items-center gap-1">
                                        <TrendingUp class="w-3 h-3" /> {{ data.stats.saldo_trend }} <span class="text-slate-400">dari bulan lalu</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Transaksi -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-200">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center shrink-0">
                                    <FileText class="w-6 h-6 text-purple-600" />
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-500 mb-1">Jumlah Transaksi</p>
                                    <p class="text-lg font-extrabold text-slate-800">{{ data.stats.transaksi }}</p>
                                    <p class="text-[11px] font-medium text-emerald-600 mt-1 flex items-center gap-1">
                                        <TrendingUp class="w-3 h-3" /> {{ data.stats.transaksi_trend }} <span class="text-slate-400">dari bulan lalu</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
                        <!-- Line Chart -->
                        <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col h-[320px]">
                            <div class="flex items-center justify-between mb-6 shrink-0">
                                <h2 class="font-extrabold text-slate-800">Grafik Pemasukan & Pengeluaran</h2>
                                <div class="flex items-center gap-4 text-xs font-bold text-slate-500">
                                    <div class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-full bg-blue-500"></div> Pemasukan</div>
                                    <div class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-full bg-orange-400"></div> Pengeluaran</div>
                                </div>
                            </div>
                            
                            <div class="flex-1 relative w-full h-full">
                                <!-- Y Axis Labels -->
                                <div class="absolute left-0 top-0 bottom-6 flex flex-col justify-between text-[10px] font-medium text-slate-400 text-right pr-2">
                                    <span>Rp 25.000.000</span>
                                    <span>Rp 20.000.000</span>
                                    <span>Rp 15.000.000</span>
                                    <span>Rp 10.000.000</span>
                                    <span>Rp 5.000.000</span>
                                    <span>Rp 0</span>
                                </div>
                                
                                <!-- Chart Area -->
                                <div class="absolute left-20 right-0 top-1 bottom-6 border-b border-slate-200">
                                    <!-- Grid lines -->
                                    <div class="absolute inset-0 flex flex-col justify-between">
                                        <div class="w-full h-px bg-slate-100" v-for="i in 5" :key="i"></div>
                                    </div>
                                    
                                    <!-- SVG Lines -->
                                    <svg class="absolute inset-0 w-full h-full overflow-visible" preserveAspectRatio="none" viewBox="0 0 600 150">
                                        <!-- Pemasukan Line -->
                                        <polyline 
                                            fill="none" 
                                            stroke="#3b82f6" 
                                            stroke-width="2" 
                                            :points="getLinePoints(data.charts.line.pemasukan)" 
                                        />
                                        <!-- Pengeluaran Line -->
                                        <polyline 
                                            fill="none" 
                                            stroke="#f97316" 
                                            stroke-width="2" 
                                            :points="getLinePoints(data.charts.line.pengeluaran)" 
                                        />
                                        
                                        <!-- Points -->
                                        <circle v-for="(pt, i) in getLinePoints(data.charts.line.pemasukan).split(' ')" :key="'p'+i"
                                            :cx="pt.split(',')[0]" :cy="pt.split(',')[1]" r="4" fill="#3b82f6" stroke="white" stroke-width="2" />
                                        
                                        <circle v-for="(pt, i) in getLinePoints(data.charts.line.pengeluaran).split(' ')" :key="'e'+i"
                                            :cx="pt.split(',')[0]" :cy="pt.split(',')[1]" r="4" fill="#f97316" stroke="white" stroke-width="2" />
                                    </svg>
                                </div>
                                
                                <!-- X Axis Labels -->
                                <div class="absolute left-20 right-0 bottom-0 flex justify-between text-[10px] font-medium text-slate-400 px-2 pt-2">
                                    <span v-for="lbl in data.charts.line.labels" :key="lbl">{{ lbl }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Donut Chart -->
                        <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col h-[320px]">
                            <h2 class="font-extrabold text-slate-800 mb-6 shrink-0">Rekap Pengeluaran</h2>
                            
                            <div class="flex-1 flex items-center justify-center gap-8">
                                <!-- SVG Donut -->
                                <div class="relative w-48 h-48 shrink-0">
                                    <svg viewBox="0 0 100 100" class="w-full h-full transform -rotate-90">
                                        <circle cx="50" cy="50" r="40" fill="transparent" stroke="#f1f5f9" stroke-width="20"></circle>
                                        <circle 
                                            v-for="(seg, i) in getDonutSegments" 
                                            :key="i"
                                            cx="50" cy="50" r="40" 
                                            fill="transparent" 
                                            :stroke="seg.color" 
                                            stroke-width="20"
                                            :stroke-dasharray="seg.dashArray"
                                            :stroke-dashoffset="seg.dashOffset"
                                        ></circle>
                                    </svg>
                                    <!-- Center Text -->
                                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                                        <p class="text-sm font-extrabold text-slate-800">{{ data.stats.pengeluaran }}</p>
                                        <p class="text-[10px] font-medium text-slate-400">Total Pengeluaran</p>
                                    </div>
                                </div>
                                
                                <!-- Legend -->
                                <div class="flex-1 space-y-3">
                                    <div v-for="seg in data.charts.donut" :key="seg.label" class="flex items-center justify-between text-sm">
                                        <div class="flex items-center gap-2">
                                            <div class="w-3 h-3 rounded-full" :style="{ backgroundColor: seg.color }"></div>
                                            <span class="font-bold text-slate-700">{{ seg.label }}</span>
                                        </div>
                                        <span class="text-slate-400 font-medium w-10 text-right">{{ seg.value }}%</span>
                                        <span class="text-blue-600 font-bold w-24 text-right">{{ seg.amount }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="bg-white rounded-2xl border border-slate-200 flex flex-col">
                        <div class="p-2 border-b border-slate-100 flex items-center gap-2">
                            <button 
                                v-for="tab in ['Pemasukan', 'Pengeluaran', 'Rincian Transaksi']" 
                                :key="tab"
                                @click="activeTab = tab"
                                :class="[
                                    'px-4 py-2 rounded-lg text-sm font-bold transition-colors',
                                    activeTab === tab ? 'bg-[#042B60] text-white' : 'text-slate-500 hover:bg-slate-50'
                                ]"
                            >
                                {{ tab }}
                            </button>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left min-w-[800px]">
                                <thead>
                                    <tr class="bg-slate-50/50 border-b border-slate-100">
                                        <th class="py-4 px-6 text-xs font-bold text-slate-800">No</th>
                                        <th class="py-4 px-4 text-xs font-bold text-slate-800">Tanggal</th>
                                        <th class="py-4 px-4 text-xs font-bold text-slate-800">Keterangan</th>
                                        <th class="py-4 px-4 text-xs font-bold text-slate-800">Nama Penghuni</th>
                                        <th class="py-4 px-4 text-xs font-bold text-slate-800">Metode</th>
                                        <th class="py-4 px-4 text-xs font-bold text-slate-800">Jumlah</th>
                                        <th class="py-4 px-4 text-xs font-bold text-slate-800">Status</th>
                                        <th class="py-4 px-6 text-xs font-bold text-slate-800 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr v-for="trx in data.transactions" :key="trx.id" class="hover:bg-slate-50/50 transition-colors">
                                        <td class="py-4 px-6 text-sm text-slate-600 font-medium">{{ trx.id }}</td>
                                        <td class="py-4 px-4 text-sm text-blue-600 font-medium">{{ trx.date }}</td>
                                        <td class="py-4 px-4 text-sm text-slate-600">{{ trx.desc }}</td>
                                        <td class="py-4 px-4 text-sm font-bold text-slate-800">{{ trx.name }}</td>
                                        <td class="py-4 px-4 text-sm text-slate-600">{{ trx.method }}</td>
                                        <td class="py-4 px-4 text-sm font-extrabold text-slate-800">{{ trx.amount }}</td>
                                        <td class="py-4 px-4">
                                            <span class="px-2.5 py-1 rounded-md bg-emerald-100 text-emerald-700 text-[11px] font-bold flex items-center w-fit gap-1.5">
                                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div> {{ trx.status }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-center flex items-center justify-center gap-1">
                                            <button class="p-1.5 text-slate-400 hover:text-blue-600 bg-white border border-slate-200 hover:border-blue-200 rounded transition-colors shadow-sm">
                                                <Eye class="w-4 h-4" />
                                            </button>
                                            <button class="p-1.5 text-slate-400 hover:text-[#042B60] bg-white border border-slate-200 hover:border-[#042B60]/20 rounded transition-colors shadow-sm">
                                                <MoreVertical class="w-4 h-4" />
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="p-4 border-t border-slate-100 flex items-center justify-between">
                            <p class="text-sm text-slate-500 font-medium">Menampilkan 1 - 6 dari 32 data</p>
                            <div class="flex items-center gap-1">
                                <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:bg-slate-50">
                                    <ChevronLeft class="w-4 h-4" />
                                </button>
                                <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#042B60] text-white font-bold text-sm">1</button>
                                <button class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 text-slate-600 font-bold text-sm">2</button>
                                <button class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 text-slate-600 font-bold text-sm">3</button>
                                <button class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 text-slate-600 font-bold text-sm">4</button>
                                <button class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 text-slate-600 font-bold text-sm">5</button>
                                <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50">
                                    <ChevronRight class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="w-full xl:w-[320px] shrink-0 space-y-6" v-if="!isLoading && data">
                <!-- Filter Laporan -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6">
                    <h2 class="font-extrabold text-slate-800 mb-4">Filter Laporan</h2>
                    
                    <div class="space-y-4">
                        <div class="relative">
                            <div class="flex items-center gap-2 w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-700">
                                <Calendar class="w-4 h-4 text-slate-400 shrink-0" />
                                1 Sep 2025 - 30 Sep 2025
                            </div>
                        </div>
                        
                        <div class="relative">
                            <select class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-700 appearance-none focus:outline-none focus:border-[#042B60]/50">
                                <option>Semua Gedung</option>
                            </select>
                            <ChevronDown class="w-4 h-4 text-slate-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none" />
                        </div>
                        
                        <div class="relative">
                            <select class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-700 appearance-none focus:outline-none focus:border-[#042B60]/50">
                                <option>Semua Kamar</option>
                            </select>
                            <ChevronDown class="w-4 h-4 text-slate-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none" />
                        </div>
                        
                        <div class="relative">
                            <select class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-700 appearance-none focus:outline-none focus:border-[#042B60]/50">
                                <option>Semua Metode Pembayaran</option>
                            </select>
                            <ChevronDown class="w-4 h-4 text-slate-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none" />
                        </div>
                        
                        <button class="w-full py-2.5 bg-[#042B60] hover:bg-[#042B60]/90 text-white rounded-lg text-sm font-bold transition-colors flex items-center justify-center gap-2 mt-2">
                            <Search class="w-4 h-4" /> Terapkan Filter
                        </button>
                    </div>
                </div>

                <!-- Ringkasan Cepat -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6">
                    <h2 class="font-extrabold text-slate-800 mb-6">Ringkasan Cepat</h2>
                    
                    <div class="space-y-6">
                        <!-- Pemasukan Terbanyak -->
                        <div class="flex gap-4">
                            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
                                <ArrowUpRight class="w-5 h-5 text-blue-600" />
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-bold text-slate-500">Pemasukan Terbanyak</p>
                                <p class="text-sm font-extrabold text-slate-800 mt-0.5">{{ data.summary.pemasukan_top.name }}</p>
                                <p class="text-xs font-bold text-emerald-600 mt-1 flex items-center gap-1">
                                    <ArrowUpRight class="w-3 h-3" /> {{ data.summary.pemasukan_top.amount }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Pengeluaran Terbesar -->
                        <div class="flex gap-4">
                            <div class="w-10 h-10 rounded-full bg-rose-50 flex items-center justify-center shrink-0">
                                <ArrowDownRight class="w-5 h-5 text-rose-600" />
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-bold text-slate-500">Pengeluaran Terbesar</p>
                                <p class="text-sm font-extrabold text-slate-800 mt-0.5">{{ data.summary.pengeluaran_top.name }}</p>
                                <p class="text-xs font-bold text-rose-600 mt-1 flex items-center gap-1">
                                    <ArrowDownRight class="w-3 h-3" /> {{ data.summary.pengeluaran_top.amount }}
                                </p>
                            </div>
                        </div>

                        <!-- Kamar Terisi -->
                        <div class="flex gap-4">
                            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
                                <BedDouble class="w-5 h-5 text-blue-600" />
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-bold text-slate-500">Kamar Terisi</p>
                                <div class="flex justify-between items-end mt-0.5">
                                    <p class="text-sm font-extrabold text-slate-800">{{ data.summary.kamar.terisi }} <span class="text-xs font-medium text-slate-500">dari {{ data.summary.kamar.total }} kamar</span></p>
                                    <span class="text-xs font-bold text-blue-600">{{ data.summary.kamar.terisi_pct }}%</span>
                                </div>
                                <div class="w-full h-2 bg-slate-100 rounded-full mt-2 overflow-hidden">
                                    <div class="h-full bg-blue-600 rounded-full" :style="`width: ${data.summary.kamar.terisi_pct}%`"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Kamar Kosong -->
                        <div class="flex gap-4">
                            <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center shrink-0">
                                <DoorClosed class="w-5 h-5 text-amber-500" />
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-bold text-slate-500">Kamar Kosong</p>
                                <div class="flex justify-between items-end mt-0.5">
                                    <p class="text-sm font-extrabold text-slate-800">{{ data.summary.kamar.kosong }} <span class="text-xs font-medium text-slate-500">kamar</span></p>
                                    <span class="text-xs font-bold text-slate-500">{{ data.summary.kamar.kosong_pct }}%</span>
                                </div>
                                <div class="w-full h-2 bg-slate-100 rounded-full mt-2 overflow-hidden">
                                    <div class="h-full bg-amber-400 rounded-full" :style="`width: ${data.summary.kamar.kosong_pct}%`"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tips -->
                <div class="bg-blue-50/50 rounded-2xl border border-blue-100 p-5 flex gap-3">
                    <Lightbulb class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" />
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Tips</h3>
                        <p class="text-xs text-blue-600/80 font-medium leading-relaxed mt-1">
                            Gunakan filter untuk melihat laporan pada periode tertentu atau berdasarkan gedung dan metode pembayaran.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
