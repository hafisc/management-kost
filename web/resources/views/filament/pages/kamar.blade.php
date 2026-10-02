<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Stats Summary --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                <div class="text-sm text-gray-500">Total Kamar</div>
                <div class="text-2xl font-bold text-blue-600">{{ $this->getStats()['total'] }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                <div class="text-sm text-gray-500">Terisi</div>
                <div class="text-2xl font-bold text-green-600">{{ $this->getStats()['occupied'] }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-gray-500">
                <div class="text-sm text-gray-500">Kosong</div>
                <div class="text-2xl font-bold text-gray-600">{{ $this->getStats()['available'] }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
                <div class="text-sm text-gray-500">Akan Checkout</div>
                <div class="text-2xl font-bold text-yellow-600">{{ $this->getStats()['expiring'] }}</div>
            </div>
        </div>

        {{-- Filter Tabs --}}
        <div class="bg-white rounded-lg shadow">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6" aria-label="Tabs">
                    <button 
                        wire:click="$set('selectedStatus', 'all')"
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $selectedStatus === 'all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                    >
                        Semua
                    </button>
                    <button 
                        wire:click="$set('selectedStatus', 'active')"
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $selectedStatus === 'active' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                    >
                        Terisi
                    </button>
                    <button 
                        wire:click="$set('selectedStatus', 'available')"
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $selectedStatus === 'available' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                    >
                        Kosong
                    </button>
                    <button 
                        wire:click="$set('selectedStatus', 'expiring')"
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $selectedStatus === 'expiring' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                    >
                        Akan Checkout
                    </button>
                </nav>
            </div>
        </div>

        {{-- Room Grid by Building and Floor --}}
        @foreach($this->getRoomsByBuilding() as $buildingName => $floors)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $buildingName }}</h3>
                </div>

                @foreach($floors as $floorNumber => $rooms)
                    <div class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                        <h4 class="text-sm font-medium text-gray-700 mb-4">Lantai {{ $floorNumber }}</h4>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                            @foreach($rooms as $room)
                                <button
                                    wire:click="selectRoom({{ $room->id }})"
                                    class="group relative rounded-lg border-2 p-4 transition-all hover:shadow-lg
                                        @if($room->occupancy_status === 'ACTIVE')
                                            bg-[#042B60] border-[#042B60] text-white hover:bg-[#03214a]
                                        @elseif($room->occupancy_status === 'EXPIRING')
                                            bg-[#FCB502] border-[#FCB502] text-gray-900 hover:bg-[#e3a402]
                                        @else
                                            bg-white border-gray-300 text-gray-700 hover:border-gray-400
                                        @endif
                                    "
                                >
                                    <div class="text-center">
                                        <div class="text-lg font-bold mb-1">{{ $room->room_number }}</div>
                                        <div class="text-xs opacity-80">
                                            @if($room->occupancy_status === 'ACTIVE')
                                                Terisi
                                            @elseif($room->occupancy_status === 'EXPIRING')
                                                Akan Checkout
                                            @else
                                                Kosong
                                            @endif
                                        </div>
                                        @if($room->current_tenant)
                                            <div class="text-xs mt-1 opacity-70 truncate">
                                                {{ Str::limit($room->current_tenant->full_name, 12) }}
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Capacity Badge --}}
                                    <div class="absolute top-1 right-1 text-xs px-1.5 py-0.5 rounded
                                        @if($room->occupancy_status === 'AVAILABLE')
                                            bg-gray-200 text-gray-700
                                        @else
                                            bg-white/20
                                        @endif
                                    ">
                                        {{ $room->capacity }}
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

        {{-- Room Detail Modal --}}
        @if($selectedRoom)
            <x-filament::modal id="room-detail" width="2xl">
                <x-slot name="heading">
                    Kamar {{ $selectedRoom->room_number }}
                </x-slot>

                <div class="space-y-6">
                    {{-- Room Info --}}
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <div class="text-gray-500">Gedung</div>
                            <div class="font-semibold">{{ $selectedRoom->building->name }}</div>
                        </div>
                        <div>
                            <div class="text-gray-500">Lantai</div>
                            <div class="font-semibold">{{ $selectedRoom->floor->floor_number }}</div>
                        </div>
                        <div>
                            <div class="text-gray-500">Kapasitas</div>
                            <div class="font-semibold">{{ $selectedRoom->capacity }} orang</div>
                        </div>
                        <div>
                            <div class="text-gray-500">Status</div>
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($selectedRoom->occupancy_status === 'ACTIVE')
                                        bg-blue-100 text-blue-800
                                    @elseif($selectedRoom->occupancy_status === 'EXPIRING')
                                        bg-yellow-100 text-yellow-800
                                    @else
                                        bg-gray-100 text-gray-800
                                    @endif
                                ">
                                    @if($selectedRoom->occupancy_status === 'ACTIVE')
                                        Terisi
                                    @elseif($selectedRoom->occupancy_status === 'EXPIRING')
                                        Akan Checkout
                                    @else
                                        Kosong
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($selectedRoom->current_tenant)
                        {{-- Tenant Info --}}
                        <div class="border-t pt-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Penghuni</h4>
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Nama</span>
                                    <span class="font-semibold">{{ $selectedRoom->current_tenant->full_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">WhatsApp</span>
                                    <span class="font-semibold">{{ $selectedRoom->current_tenant->whatsapp_number }}</span>
                                </div>
                                @if($selectedRoom->current_stay)
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Check-in</span>
                                        <span class="font-semibold">{{ \Carbon\Carbon::parse($selectedRoom->current_stay->start_at)->locale('id')->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Check-out</span>
                                        <span class="font-semibold">{{ \Carbon\Carbon::parse($selectedRoom->current_stay->end_at)->locale('id')->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Durasi</span>
                                        <span class="font-semibold">
                                            {{ $selectedRoom->current_stay->duration_value }} 
                                            {{ str_replace(['daily', 'weekly', 'monthly'], ['Hari', 'Minggu', 'Bulan'], $selectedRoom->current_stay->duration_type) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Payment Info --}}
                        @if($selectedRoom->current_stay && $selectedRoom->current_stay->payments->count() > 0)
                            <div class="border-t pt-4">
                                <h4 class="font-semibold text-gray-900 mb-3">Pembayaran Terakhir</h4>
                                @php
                                    $lastPayment = $selectedRoom->current_stay->payments->sortByDesc('payment_date')->first();
                                @endphp
                                <div class="bg-gray-50 rounded-lg p-4 space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Total</span>
                                        <span class="font-semibold text-green-600">Rp {{ number_format($lastPayment->amount, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Metode</span>
                                        <span class="font-semibold capitalize">{{ str_replace('_', ' ', $lastPayment->payment_method) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Tanggal</span>
                                        <span class="font-semibold">{{ \Carbon\Carbon::parse($lastPayment->payment_date)->locale('id')->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <p class="mt-2">Kamar ini sedang kosong</p>
                        </div>
                    @endif
                </div>

                <x-slot name="footerActions">
                    <x-filament::button color="gray" wire:click="closeModal">
                        Tutup
                    </x-filament::button>
                </x-slot>
            </x-filament::modal>
        @endif
    </div>
</x-filament-panels::page>
