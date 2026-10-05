<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import axios from 'axios';
import { 
    Plus,
    BedDouble,
    UserCheck,
    Bed,
    Clock,
    Wrench,
    User,
    ArrowLeft,
    ImageIcon
} from 'lucide-vue-next';

const rooms = ref([]);

const activeBuilding = ref('Gedung Putri');
const activeFloor = ref('Lantai 1');
const selectedRoom = ref(null);

const fetchRooms = async () => {
    try {
        const response = await axios.get('/api/rooms');
        if (response.data.status === 'success') {
            rooms.value = response.data.data.map(room => {
                let type = 'kosong';
                let status = 'Kosong';
                
                if (!room.is_active) {
                    type = 'maintenance';
                    status = 'Maintenance';
                } else if (room.active_stay) {
                    if (room.active_stay.status === 'Aktif') {
                        type = 'terisi';
                        status = 'Terisi';
                    } else if (room.active_stay.status === 'Akan Checkout') {
                        type = 'checkout';
                        status = 'Akan Checkout';
                    }
                }

                return {
                    ...room,
                    floor: `Lantai ${room.floor}`,
                    type,
                    status
                };
            });
            
            const firstRoom = rooms.value.find(r => r.building === activeBuilding.value && r.floor === activeFloor.value);
            if (firstRoom) selectedRoom.value = firstRoom;
        }
    } catch (error) {
        console.error("Error fetching rooms:", error);
    }
};

onMounted(() => {
    fetchRooms();
});

const stats = computed(() => {
    const total = rooms.value.length;
    if (total === 0) return { total: 0, terisi: 0, terisiPercent: 0, kosong: 0, kosongPercent: 0, checkout: 0, checkoutPercent: 0, maintenance: 0, maintenancePercent: 0 };
    
    const terisi = rooms.value.filter(r => r.type === 'terisi').length;
    const kosong = rooms.value.filter(r => r.type === 'kosong').length;
    const checkout = rooms.value.filter(r => r.type === 'checkout').length;
    const maintenance = rooms.value.filter(r => r.type === 'maintenance').length;
    
    return {
        total,
        terisi,
        terisiPercent: Math.round((terisi / total) * 100),
        kosong,
        kosongPercent: Math.round((kosong / total) * 100),
        checkout,
        checkoutPercent: Math.round((checkout / total) * 100),
        maintenance,
        maintenancePercent: Math.round((maintenance / total) * 100)
    };
});

const filteredRooms = computed(() => {
    return rooms.value.filter(room => room.building === activeBuilding.value && room.floor === activeFloor.value);
});



watch(activeBuilding, (newBuilding) => {
    activeFloor.value = 'Lantai 1'; // Reset ke Lantai 1 tiap ganti gedung
    const firstRoom = rooms.value.find(r => r.building === newBuilding && r.floor === 'Lantai 1');
    if (firstRoom) selectedRoom.value = firstRoom;
});

const selectRoom = (room) => {
    selectedRoom.value = room;
};
</script>

<template>
    <div class="w-full h-full flex flex-col">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-[#042B60] tracking-tight">Kamar</h1>
                <p class="text-slate-500 mt-1.5 text-sm">Kelola dan pantau status semua kamar kost.</p>
            </div>
            <div class="flex items-center gap-4">
                <select class="border border-slate-200 bg-white rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#042B60]/20 shadow-sm">
                    <option>Semua Gedung</option>
                    <option>Gedung Putri</option>
                    <option>Gedung Putra</option>
                </select>
                <button class="bg-[#042B60] hover:bg-[#042B60]/90 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2 shadow-sm">
                    <Plus class="w-4 h-4" /> Tambah Kamar
                </button>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col justify-between">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <BedDouble class="w-6 h-6" />
                    </div>
                    <p class="text-xs font-bold text-slate-500">Total Kamar</p>
                </div>
                <p class="text-3xl font-extrabold text-[#042B60] leading-none">{{ stats.total }}</p>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col justify-between">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <UserCheck class="w-6 h-6" />
                    </div>
                    <p class="text-xs font-bold text-slate-500">Terisi</p>
                </div>
                <div class="flex items-end justify-between">
                    <p class="text-3xl font-extrabold text-[#042B60] leading-none">{{ stats.terisi }}</p>
                    <p class="text-xs font-bold text-slate-400">{{ stats.terisiPercent }}%</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col justify-between">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                        <Bed class="w-6 h-6" />
                    </div>
                    <p class="text-xs font-bold text-slate-500">Kosong</p>
                </div>
                <div class="flex items-end justify-between">
                    <p class="text-3xl font-extrabold text-[#042B60] leading-none">{{ stats.kosong }}</p>
                    <p class="text-xs font-bold text-slate-400">{{ stats.kosongPercent }}%</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col justify-between">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                        <Clock class="w-6 h-6" />
                    </div>
                    <p class="text-xs font-bold text-slate-500">Akan Checkout</p>
                </div>
                <div class="flex items-end justify-between">
                    <p class="text-3xl font-extrabold text-[#042B60] leading-none">{{ stats.checkout }}</p>
                    <p class="text-xs font-bold text-slate-400">{{ stats.checkoutPercent }}%</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col justify-between">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                        <Wrench class="w-6 h-6" />
                    </div>
                    <p class="text-xs font-bold text-slate-500">Maintenance</p>
                </div>
                <div class="flex items-end justify-between">
                    <p class="text-3xl font-extrabold text-[#042B60] leading-none">{{ stats.maintenance }}</p>
                    <p class="text-xs font-bold text-slate-400">{{ stats.maintenancePercent }}%</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 flex-1 min-h-0">
            <!-- Left Side: Grid Layout -->
            <div class="flex-1 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col min-h-[500px]">
                <!-- Building Tabs -->
                <div class="flex items-center gap-2 p-4 border-b border-slate-100">
                    <button 
                        @click="activeBuilding = 'Gedung Putri'"
                        :class="['px-5 py-2.5 rounded-xl text-sm font-bold transition-all', activeBuilding === 'Gedung Putri' ? 'bg-[#042B60] text-white shadow-md' : 'text-slate-500 hover:bg-slate-100']"
                    >
                        Gedung Putri
                    </button>
                    <button 
                        @click="activeBuilding = 'Gedung Putra'"
                        :class="['px-5 py-2.5 rounded-xl text-sm font-bold transition-all', activeBuilding === 'Gedung Putra' ? 'bg-[#042B60] text-white shadow-md' : 'text-slate-500 hover:bg-slate-100']"
                    >
                        Gedung Putra
                    </button>
                </div>
                
                <!-- Floor Tabs -->
                <div class="flex items-center gap-8 px-6 py-3 border-b border-slate-100">
                    <button 
                        @click="activeFloor = 'Lantai 1'"
                        :class="['text-sm font-bold transition-colors border-b-2 pb-3 -mb-[13px]', activeFloor === 'Lantai 1' ? 'border-[#042B60] text-[#042B60]' : 'border-transparent text-slate-400 hover:text-slate-600']"
                    >
                        Lantai 1
                    </button>
                    <button 
                        v-if="activeBuilding === 'Gedung Putri'"
                        @click="activeFloor = 'Lantai 2'"
                        :class="['text-sm font-bold transition-colors border-b-2 pb-3 -mb-[13px]', activeFloor === 'Lantai 2' ? 'border-[#042B60] text-[#042B60]' : 'border-transparent text-slate-400 hover:text-slate-600']"
                    >
                        Lantai 2
                    </button>
                    <button 
                        v-if="activeBuilding === 'Gedung Putri'"
                        @click="activeFloor = 'Lantai 3'"
                        :class="['text-sm font-bold transition-colors border-b-2 pb-3 -mb-[13px]', activeFloor === 'Lantai 3' ? 'border-[#042B60] text-[#042B60]' : 'border-transparent text-slate-400 hover:text-slate-600']"
                    >
                        Lantai 3
                    </button>
                </div>

                <!-- Rooms Grid -->
                <div class="p-6 overflow-y-auto bg-slate-50 flex-1">
                    <h3 class="text-sm font-bold text-slate-800 mb-4">{{ activeBuilding }} - {{ activeFloor }}</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-4">
                        <button 
                            v-for="room in filteredRooms" 
                            :key="room.id"
                            @click="selectRoom(room)"
                            class="relative p-4 rounded-xl flex flex-col justify-between h-28 text-left transition-all hover:scale-[1.02] active:scale-[0.98]"
                            :class="[
                                room.type === 'terisi' ? 'bg-emerald-50 text-emerald-900 border border-emerald-200 shadow-sm' : '',
                                room.type === 'kosong' ? 'bg-sky-50 text-sky-900 border border-sky-200 shadow-sm' : '',
                                room.type === 'checkout' ? 'bg-amber-50 text-amber-900 border border-amber-200 shadow-sm' : '',
                                room.type === 'maintenance' ? 'bg-red-50 text-red-900 border border-red-200 shadow-sm' : '',
                                selectedRoom.id === room.id ? 'ring-2 ring-offset-2 ring-[#042B60]' : ''
                            ]"
                        >
                            <div class="flex items-center gap-2">
                                <User v-if="room.type !== 'maintenance'" class="w-4 h-4 opacity-80" />
                                <Wrench v-else class="w-4 h-4 opacity-80" />
                                <span class="font-extrabold tracking-wide">No. {{ room.number }}</span>
                            </div>
                            
                            <!-- Badges -->
                            <div class="mt-auto">
                                <span v-if="room.type === 'terisi'" class="inline-flex items-center px-2.5 py-1 rounded bg-emerald-500 text-white text-[10px] font-bold tracking-wide">
                                    TERISI
                                </span>
                                <span v-else-if="room.type === 'kosong'" class="inline-flex items-center px-2.5 py-1 rounded bg-sky-500 text-white text-[10px] font-bold tracking-wide">
                                    KOSONG
                                </span>
                                <span v-else-if="room.type === 'checkout'" class="inline-flex items-center px-2.5 py-1 rounded bg-amber-500 text-white text-[10px] font-bold tracking-wide">
                                    AKAN CHECKOUT
                                </span>
                                <span v-else-if="room.type === 'maintenance'" class="inline-flex items-center px-2.5 py-1 rounded bg-red-500 text-white text-[10px] font-bold tracking-wide">
                                    MAINTENANCE
                                </span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Side: Sidebar Info -->
            <div v-if="selectedRoom" class="w-full lg:w-[380px] bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col shrink-0 overflow-y-auto">
                <div class="p-6 flex flex-col gap-8 h-full">
                    <!-- Room Header & Specs -->
                    <div>
                        <button class="flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-[#042B60] transition-colors mb-6">
                            <ArrowLeft class="w-4 h-4" /> Kembali ke Daftar Kamar
                        </button>
                        
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-[#042B60]/5 text-[#042B60] flex items-center justify-center">
                                    <BedDouble class="w-7 h-7" />
                                </div>
                                <h2 class="text-3xl font-extrabold text-slate-800">{{ activeBuilding === 'Gedung Putri' ? 'P' : 'L' }}-{{ selectedRoom.number }}</h2>
                            </div>
                            <span 
                                class="px-3 py-1.5 rounded-lg text-xs font-bold"
                                :class="{
                                    'bg-emerald-100 text-emerald-700': selectedRoom.type === 'terisi',
                                    'bg-sky-100 text-sky-700': selectedRoom.type === 'kosong',
                                    'bg-amber-100 text-amber-700': selectedRoom.type === 'checkout',
                                    'bg-red-100 text-red-700': selectedRoom.type === 'maintenance',
                                }"
                            >
                                {{ selectedRoom.status }}
                            </span>
                        </div>

                        <!-- Specs -->
                        <div class="space-y-4 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-slate-500">Gedung</span>
                                <span class="font-bold text-slate-800">{{ activeBuilding }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-slate-500">Lantai</span>
                                <span class="font-bold text-slate-800">{{ activeFloor.split(' ')[1] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-slate-500">Kapasitas</span>
                                <span class="font-bold text-slate-800">{{ selectedRoom.capacity }} orang</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-slate-500">Status</span>
                                <span 
                                    class="px-2.5 py-1 rounded text-[10px] font-bold uppercase"
                                    :class="{
                                        'bg-emerald-500 text-white': selectedRoom.type === 'terisi',
                                        'bg-sky-500 text-white': selectedRoom.type === 'kosong',
                                        'bg-amber-500 text-white': selectedRoom.type === 'checkout',
                                        'bg-red-500 text-white': selectedRoom.type === 'maintenance',
                                    }"
                                >
                                    {{ selectedRoom.status }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-slate-500">Catatan</span>
                                <span class="font-bold text-slate-800">{{ selectedRoom.notes || '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Occupants Section -->
                    <div class="border-t border-slate-100 pt-8">
                        <h3 class="text-sm font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <UserCheck class="w-4 h-4 text-slate-400" /> Penghuni Saat Ini
                        </h3>
                        
                        <div v-if="selectedRoom.type === 'terisi' || selectedRoom.type === 'checkout'" class="flex flex-col">
                            <!-- Tenant Details -->
                            <div class="flex items-center gap-4 mb-6">
                                <img :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${selectedRoom.active_stay?.tenant?.name || 'TN'}`" alt="Avatar" class="w-12 h-12 rounded-full border border-slate-200 bg-slate-50 shrink-0" />
                                <div>
                                    <p class="font-bold text-slate-800 text-base">{{ selectedRoom.active_stay?.tenant?.name }}</p>
                                    <p class="text-sm text-slate-500 font-medium mt-0.5">{{ selectedRoom.active_stay?.tenant?.whatsapp }}</p>
                                </div>
                            </div>
                            
                            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 text-sm space-y-3 mb-6">
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500 font-medium">Mulai Kost</span>
                                    <span class="font-bold text-slate-700">{{ selectedRoom.active_stay?.check_in }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500 font-medium">Tenggat Tagihan</span>
                                    <span class="font-bold text-slate-700">{{ selectedRoom.active_stay?.check_out }}</span>
                                </div>
                            </div>
                            
                            <button class="w-full bg-white border-2 border-slate-200 hover:border-[#042B60] text-slate-600 hover:text-[#042B60] px-5 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2">
                                Lihat Profil Lengkap
                            </button>
                        </div>

                        <div v-else class="flex flex-col items-center justify-center text-center opacity-70 py-4">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center text-slate-300 mb-4">
                                <User class="w-8 h-8" />
                            </div>
                            <p class="text-sm font-bold text-slate-600 mb-1">Belum ada penghuni</p>
                            <p class="text-xs font-medium text-slate-400 max-w-[200px]">Kamar ini kosong dan siap ditempati.</p>
                            
                            <button v-if="selectedRoom.type === 'kosong'" class="w-full mt-6 bg-[#042B60] hover:bg-[#042B60]/90 text-white px-5 py-3 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 shadow-sm">
                                <User class="w-4 h-4" /> Tambah Penghuni
                            </button>
                        </div>
                    </div>

                    <!-- Photo Placeholder -->
                    <div class="relative h-40 rounded-xl bg-slate-100 border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400 group overflow-hidden cursor-pointer hover:bg-slate-50 transition-colors mt-auto">
                        <ImageIcon class="w-8 h-8 mb-2 opacity-50 group-hover:scale-110 transition-transform" />
                        <span class="text-xs font-bold">Lihat Foto Kamar</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
