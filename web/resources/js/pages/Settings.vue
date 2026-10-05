<template>
    <div>
        <h1 class="text-2xl font-bold text-primary mb-6">Pengaturan</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <!-- Settings Sidebar -->
            <div class="md:col-span-3">
                <nav class="space-y-1">
                    <a href="#" class="block px-4 py-2 bg-primary/5 text-primary font-medium rounded-lg">Harga Kost</a>
                    <a href="#" class="block px-4 py-2 text-text-muted hover:bg-surface hover:text-text-main rounded-lg">Biaya Tambahan</a>
                    <a href="#" class="block px-4 py-2 text-text-muted hover:bg-surface hover:text-text-main rounded-lg">Pembayaran</a>
                    <a href="#" class="block px-4 py-2 text-text-muted hover:bg-surface hover:text-text-main rounded-lg">WhatsApp</a>
                    <a href="#" class="block px-4 py-2 text-text-muted hover:bg-surface hover:text-text-main rounded-lg">Admin</a>
                </nav>
            </div>
            
            <!-- Settings Content -->
            <div class="md:col-span-9">
                <div class="bg-surface rounded-xl shadow-sm border border-border-main p-6">
                    <h2 class="text-lg font-semibold text-text-main mb-6">Harga Kost Dasar</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-sm font-medium text-text-muted mb-3 uppercase tracking-wider">Gedung Putri</h3>
                            <div class="grid gap-4">
                                <div class="flex justify-between items-center p-4 border border-border-main rounded-lg">
                                    <span class="font-medium">1 Orang</span>
                                    <span class="font-semibold text-primary">Rp600.000</span>
                                </div>
                                <div class="flex justify-between items-center p-4 border border-border-main rounded-lg">
                                    <span class="font-medium">2 Orang (Sharing)</span>
                                    <span class="font-semibold text-primary">Rp350.000 <span class="text-sm text-text-muted font-normal">/ orang</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
