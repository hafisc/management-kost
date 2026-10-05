<script setup>
import { 
    LayoutDashboard, 
    Users, 
    Bed, 
    Receipt, 
    BarChart3, 
    BadgeDollarSign,
    PlugZap,
    Landmark,
    MessageSquare,
    ShieldCheck,
    Menu,
    Search,
    Bell,
    ChevronDown,
    LogOut
} from 'lucide-vue-next';
import { useRoute } from 'vue-router';

const route = useRoute();

const navigation = [
    { name: 'Dashboard', href: '/', icon: LayoutDashboard },
    { name: 'Penghuni', href: '/tenants', icon: Users },
    { name: 'Kamar', href: '/rooms', icon: Bed },
    { name: 'Pembayaran', href: '/payments', icon: Receipt },
    { name: 'Laporan', href: '/reports', icon: BarChart3 },
];

const settingsNav = [
    { name: 'Harga Kost', href: '/settings/price', icon: BadgeDollarSign },
    { name: 'Biaya Tambahan', href: '/settings/charges', icon: PlugZap },
    { name: 'Pembayaran', href: '/settings/payment', icon: Landmark },
    { name: 'WhatsApp', href: '/settings/whatsapp', icon: MessageSquare },
    { name: 'Admin', href: '/settings/admin', icon: ShieldCheck },
];
</script>

<template>
    <div class="flex h-screen bg-[#F8FAFC] text-slate-900 font-sans">
        <!-- Sidebar -->
        <div class="w-64 flex flex-col bg-[#042B60] text-white h-full z-20">
            <!-- Logo Area -->
            <div class="h-20 flex items-center px-6 gap-3">
                <img src="/logo.png" alt="Logo" class="w-11 h-11 object-contain rounded-full bg-white" />
                <div>
                    <h1 class="text-base font-bold tracking-wide leading-tight">Management Kost</h1>
                    <p class="text-xs text-[#FCB502]">Kost Sumber Rezeki</p>
                </div>
            </div>
            
            <nav class="flex-1 overflow-y-auto py-4 px-4 custom-scrollbar">
                <!-- Main Menu -->
                <div class="space-y-1 mb-8">
                    <router-link
                        v-for="item in navigation"
                        :key="item.name"
                        :to="item.href"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-sm"
                        :class="[
                            route.path === item.href 
                                ? 'bg-[#FCB502] text-[#042B60] shadow-md' 
                                : 'text-slate-300 hover:text-white hover:bg-white/10'
                        ]"
                    >
                        <component :is="item.icon" class="w-5 h-5" />
                        {{ item.name }}
                    </router-link>
                </div>

                <!-- Pengaturan -->
                <div>
                    <p class="px-4 text-xs font-semibold text-slate-400 mb-3 tracking-wider uppercase">Pengaturan</p>
                    <div class="space-y-1">
                        <router-link
                            v-for="item in settingsNav"
                            :key="item.name"
                            :to="item.href"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-sm text-slate-300 hover:text-white hover:bg-white/10"
                        >
                            <component :is="item.icon" class="w-5 h-5" />
                            {{ item.name }}
                        </router-link>
                    </div>
                </div>
            </nav>

            <!-- Bottom Area -->
            <div class="p-4 mt-auto border-t border-white/10">
                <button class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-sm text-red-400 hover:text-red-300 hover:bg-red-500/10">
                    <LogOut class="w-5 h-5" />
                    Logout
                </button>
            </div>
        </div>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Top Navbar -->
            <header class="bg-white border-b border-slate-200 h-20 flex items-center justify-between px-6 z-10 shrink-0">
                <div class="flex items-center gap-4">
                    
                    
                    <div class="relative w-96 hidden md:block">
                        <Search class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" />
                        <input 
                            type="text" 
                            placeholder="Cari nama, nomor WhatsApp, kamar, atau penghuni..." 
                            class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#042B60]/20 focus:border-[#042B60]/50 transition-all"
                        >
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <button class="relative p-2 text-slate-500 hover:bg-slate-100 rounded-lg transition-colors">
                        <Bell class="w-5 h-5" />
                        <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>
                    
                    <div class="h-8 w-px bg-slate-200 mx-2"></div>
                    
                    <button class="flex items-center gap-3 hover:bg-slate-50 p-1.5 rounded-lg transition-colors text-left">
                        <img 
                            src="https://api.dicebear.com/9.x/micah/svg?seed=Admin&backgroundColor=f1f5f9" 
                            alt="Admin Avatar" 
                            class="w-10 h-10 rounded-full shadow-sm border border-slate-200"
                        />
                        <div class="hidden sm:block">
                            <p class="text-sm font-bold text-slate-800 leading-tight">Admin</p>
                            <p class="text-xs text-slate-500">Admin Kost</p>
                        </div>
                        <ChevronDown class="w-4 h-4 text-slate-400" />
                    </button>
                </div>
            </header>
            
            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto bg-[#F8FAFC] p-6 lg:p-8">
                <router-view v-slot="{ Component }">
                    <transition name="fade" mode="out-in">
                        <component :is="Component" />
                    </transition>
                </router-view>
            </main>
        </div>
    </div>
</template>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.15s ease, transform 0.15s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(4px);
}

.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.1);
    border-radius: 4px;
}
.custom-scrollbar:hover::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.2);
}
</style>
