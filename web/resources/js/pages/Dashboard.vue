<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { 
    Wallet, 
    Home, 
    BedDouble, 
    Clock, 
    ArrowUpRight,
    ArrowDownRight,
    MoreVertical,
    Download,
    TrendingUp,
    CheckCircle
} from 'lucide-vue-next';

const stats = ref([
    { key: 'pendapatan', title: 'Pendapatan Bulan Ini', value: '...', trend: '...', trendUp: true, icon: Wallet, color: 'text-blue-600', bg: 'bg-blue-50' },
    { key: 'terisi', title: 'Kamar Terisi', value: '...', trend: '...', trendUp: true, icon: Home, color: 'text-emerald-600', bg: 'bg-emerald-50' },
    { key: 'kosong', title: 'Kamar Kosong', value: '...', trend: '...', trendUp: false, icon: BedDouble, color: 'text-slate-600', bg: 'bg-slate-100' },
    { key: 'checkout', title: 'Akan Checkout', value: '...', trend: '...', trendUp: false, icon: Clock, color: 'text-amber-600', bg: 'bg-amber-50' }
]);

const recentActivities = ref([]);
const upcomingDue = ref([]);
const chartData = ref([]);

const fetchDashboardData = async () => {
    try {
        const response = await axios.get('/api/dashboard');
        if (response.data.status === 'success') {
            const data = response.data.data;
            
            // Map stats
            stats.value = stats.value.map(stat => ({
                ...stat,
                value: data.stats[stat.key].value,
                trend: data.stats[stat.key].trend,
                trendUp: data.stats[stat.key].trendUp
            }));
            
            upcomingDue.value = data.upcomingDue.slice(0, 3);
            recentActivities.value = data.recentActivities;
            chartData.value = data.chartData;
        }
    } catch (error) {
        console.error("Error fetching dashboard data:", error);
    }
};

onMounted(() => {
    fetchDashboardData();
});

const getBarColor = (index, value) => {
    if (index === 0) return { bg: 'bg-[#042B60]/40', hoverBg: 'group-hover:bg-[#042B60]/60', border: 'bg-[#042B60]' };
    
    const prevValue = chartData.value[index - 1].value;
    if (value > prevValue) {
        return { bg: 'bg-emerald-400', hoverBg: 'group-hover:bg-emerald-500', border: 'bg-emerald-600' };
    } else if (value < prevValue) {
        return { bg: 'bg-rose-400', hoverBg: 'group-hover:bg-rose-500', border: 'bg-rose-600' };
    }
    
    return { bg: 'bg-[#042B60]/40', hoverBg: 'group-hover:bg-[#042B60]/60', border: 'bg-[#042B60]' };
};
</script>

<template>
    <div class="w-full h-full flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-[#042B60] tracking-tight">Dashboard</h1>
                <p class="text-slate-500 mt-1 text-xs">Ringkasan performa dan aktivitas kost Anda.</p>
            </div>
            <button class="bg-white border-2 border-slate-200 hover:border-[#042B60] text-slate-600 hover:text-[#042B60] px-4 py-2 rounded-lg text-xs font-bold transition-colors flex items-center gap-2 shadow-sm">
                <Download class="w-4 h-4" /> Download Laporan
            </button>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div v-for="(stat, index) in stats" :key="index" class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-col justify-between group hover:border-[#042B60]/30 transition-colors">
                <div class="flex justify-between items-start mb-3">
                    <div :class="`w-12 h-12 rounded-xl ${stat.bg} ${stat.color} flex items-center justify-center`">
                        <component :is="stat.icon" class="w-6 h-6" />
                    </div>
                    <div v-if="stat.trend" class="flex items-center gap-1 text-xs font-bold" :class="stat.trendUp ? 'text-emerald-500' : 'text-slate-400'">
                        <component :is="stat.trendUp ? ArrowUpRight : ArrowDownRight" class="w-3.5 h-3.5" />
                        {{ stat.trend }}
                    </div>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 mb-1">{{ stat.title }}</p>
                    <p class="text-2xl font-extrabold text-[#042B60] leading-none">{{ stat.value }}</p>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 flex-1 min-h-0">
            <!-- Column 1: Chart -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex flex-col h-full">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-800">Grafik Pendapatan</h2>
                        <p class="text-sm text-slate-500 font-medium">6 Bulan Terakhir</p>
                    </div>
                </div>

                <!-- Mock Bar Chart -->
                <div class="w-full flex items-end justify-between gap-2 pt-6 pb-2 flex-1 relative">
                    <!-- Grid Lines -->
                    <div class="absolute inset-0 flex flex-col justify-between pointer-events-none opacity-10">
                        <div class="w-full h-px bg-slate-400" v-for="n in 5" :key="n"></div>
                    </div>
                    
                    <!-- Bars -->
                    <div v-for="(item, index) in chartData" :key="item.month" class="relative flex flex-col items-center flex-1 h-full justify-end group">
                        <div :class="`w-full max-w-[24px] ${getBarColor(index, item.value).bg} ${getBarColor(index, item.value).hoverBg} rounded-t-xl transition-all relative`" :style="{ height: `${item.value}%` }">
                            <div :class="`absolute top-0 inset-x-0 h-1 ${getBarColor(index, item.value).border} rounded-t-xl`"></div>
                            
                            <!-- Tooltip -->
                            <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] font-bold px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-20">
                                {{ item.value }}%
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-slate-500 mt-2">{{ item.month }}</span>
                    </div>
                </div>
            </div>

            <!-- Column 2: Upcoming Due -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex flex-col h-full">
                <div class="flex items-center justify-between mb-4 shrink-0">
                    <h2 class="text-base font-extrabold text-slate-800">Jatuh Tempo Terdekat</h2>
                    <button class="text-[#042B60] hover:text-[#042B60]/80">
                        <MoreVertical class="w-5 h-5" />
                    </button>
                </div>

                <div v-if="upcomingDue.length > 0" class="flex-1 overflow-y-auto space-y-4">
                    <div v-for="due in upcomingDue" :key="due.id" class="flex items-start gap-3 p-3 rounded-xl border border-amber-100 bg-amber-50/30">
                        <img :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${due.name}`" alt="Avatar" class="w-10 h-10 rounded-full shrink-0 border border-slate-200 bg-white" />
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-800 truncate">{{ due.name }}</p>
                            <p class="text-xs font-medium text-slate-500 mt-0.5"><span class="text-amber-600 font-bold">H-{{ due.days }}</span></p>
                            <p class="text-sm font-extrabold text-slate-800 mt-1">{{ due.amount }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Empty State -->
                <div v-else class="flex-1 flex flex-col items-center justify-center text-center opacity-60 px-4">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-3">
                        <CheckCircle class="w-8 h-8 text-emerald-500" />
                    </div>
                    <p class="text-sm font-bold text-slate-800">Semua Tagihan Aman</p>
                    <p class="text-xs text-slate-500 mt-1">Tidak ada penghuni yang mendekati jatuh tempo dalam 7 hari ini.</p>
                </div>
                <button class="w-full mt-auto pt-4 py-2 text-sm font-bold text-[#042B60] hover:bg-[#042B60]/5 rounded-lg transition-colors border border-transparent hover:border-[#042B60]/10 shrink-0">
                    Lihat Semua Tagihan
                </button>
            </div>

            <!-- Column 3: Recent Activity -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex flex-col h-full">
                <h2 class="text-base font-extrabold text-slate-800 mb-4 shrink-0">Aktivitas Terbaru</h2>
                
                <div class="flex-1 overflow-y-auto space-y-4">
                    <div v-for="activity in recentActivities" :key="activity.id" class="flex gap-3 relative">
                        <!-- Timeline line -->
                        <div class="absolute top-8 bottom-[-16px] left-[15px] w-px bg-slate-200 last:hidden"></div>
                        
                        <img :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${activity.name}`" alt="Avatar" class="w-8 h-8 rounded-full shrink-0 border-2 border-white z-10 relative bg-slate-100" />
                        <div class="pb-1">
                            <p class="text-sm font-medium text-slate-600 leading-tight">
                                <span class="font-bold text-slate-800 block">{{ activity.name }}</span> 
                                {{ activity.action }} <span class="font-bold text-[#042B60]">{{ activity.room }}</span>
                            </p>
                            <div class="flex items-center gap-2 mt-1.5">
                                <p class="text-[11px] font-medium text-slate-400">{{ activity.time }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button class="w-full mt-auto pt-4 py-2 text-sm font-bold text-[#042B60] hover:bg-[#042B60]/5 rounded-lg transition-colors border border-transparent hover:border-[#042B60]/10 shrink-0">
                    Lihat Semua Aktivitas
                </button>
            </div>
        </div>
    </div>
</template>
