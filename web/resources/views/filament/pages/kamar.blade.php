<x-filament-panels::page>
    <style>
        .room-card {
            min-height: 90px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .room-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .room-occupied {
            background: linear-gradient(135deg, #042B60 0%, #03214a 100%);
            color: white;
            border: 2px solid #042B60;
        }
        .room-expiring {
            background: linear-gradient(135deg, #FCB502 0%, #e3a402 100%);
            color: #1a1a1a;
            border: 2px solid #FCB502;
        }
        .room-available {
            background: white;
            color: #374151;
            border: 2px solid #d1d5db;
        }
        .room-available:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }
    </style>

    <div style="display: flex; flex-direction: column; gap: 24px;">
        {{-- Stats Summary --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            @php $stats = $this->getStats(); @endphp
            
            <div style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #3b82f6;">
                <div style="font-size: 14px; color: #6b7280; margin-bottom: 8px;">Total Kamar</div>
                <div style="font-size: 32px; font-weight: bold; color: #3b82f6;">{{ $stats['total'] }}</div>
            </div>

            <div style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #10b981;">
                <div style="font-size: 14px; color: #6b7280; margin-bottom: 8px;">Terisi</div>
                <div style="font-size: 32px; font-weight: bold; color: #10b981;">{{ $stats['occupied'] }}</div>
            </div>

            <div style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #6b7280;">
                <div style="font-size: 14px; color: #6b7280; margin-bottom: 8px;">Kosong</div>
                <div style="font-size: 32px; font-weight: bold; color: #6b7280;">{{ $stats['available'] }}</div>
            </div>

            <div style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #f59e0b;">
                <div style="font-size: 14px; color: #6b7280; margin-bottom: 8px;">Akan Checkout</div>
                <div style="font-size: 32px; font-weight: bold; color: #f59e0b;">{{ $stats['expiring'] }}</div>
            </div>
        </div>

        {{-- Filter Tabs --}}
        <div style="background: white; border-radius: 8px; padding: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="display: flex; gap: 32px; padding: 0 24px; border-bottom: 1px solid #e5e7eb;">
                <button 
                    wire:click="$set('selectedStatus', 'all')"
                    style="padding: 16px 4px; border-bottom: 2px solid {{ $selectedStatus === 'all' ? '#3b82f6' : 'transparent' }}; color: {{ $selectedStatus === 'all' ? '#3b82f6' : '#6b7280' }}; font-weight: 500; background: none; border-left: none; border-right: none; border-top: none; cursor: pointer;"
                >
                    Semua
                </button>
                <button 
                    wire:click="$set('selectedStatus', 'active')"
                    style="padding: 16px 4px; border-bottom: 2px solid {{ $selectedStatus === 'active' ? '#3b82f6' : 'transparent' }}; color: {{ $selectedStatus === 'active' ? '#3b82f6' : '#6b7280' }}; font-weight: 500; background: none; border-left: none; border-right: none; border-top: none; cursor: pointer;"
                >
                    Terisi
                </button>
                <button 
                    wire:click="$set('selectedStatus', 'available')"
                    style="padding: 16px 4px; border-bottom: 2px solid {{ $selectedStatus === 'available' ? '#3b82f6' : 'transparent' }}; color: {{ $selectedStatus === 'available' ? '#3b82f6' : '#6b7280' }}; font-weight: 500; background: none; border-left: none; border-right: none; border-top: none; cursor: pointer;"
                >
                    Kosong
                </button>
                <button 
                    wire:click="$set('selectedStatus', 'expiring')"
                    style="padding: 16px 4px; border-bottom: 2px solid {{ $selectedStatus === 'expiring' ? '#3b82f6' : 'transparent' }}; color: {{ $selectedStatus === 'expiring' ? '#3b82f6' : '#6b7280' }}; font-weight: 500; background: none; border-left: none; border-right: none; border-top: none; cursor: pointer;"
                >
                    Akan Checkout
                </button>
            </div>
        </div>

        {{-- Room Grid by Building and Floor --}}
        @foreach($this->getRoomsByBuilding() as $buildingName => $floors)
            <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
                <div style="background: linear-gradient(135deg, #4b5563 0%, #374151 100%); padding: 16px 24px;">
                    <h3 style="font-size: 18px; font-weight: bold; color: white; margin: 0;">{{ strtoupper($buildingName) }}</h3>
                </div>

                @foreach($floors as $floorNumber => $rooms)
                    <div style="padding: 24px; border-bottom: 1px solid #f3f4f6;">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                            <span style="background: #f3f4f6; padding: 6px 12px; border-radius: 6px; font-weight: bold; font-size: 13px; color: #374151;">
                                LANTAI {{ $floorNumber }}
                            </span>
                            <span style="font-size: 13px; color: #6b7280;">{{ $rooms->count() }} kamar</span>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(90px, 1fr)); gap: 12px;">
                            @foreach($rooms as $room)
                                @php
                                    $roomClass = $room->occupancy_status === 'ACTIVE' ? 'room-occupied' : 
                                                ($room->occupancy_status === 'EXPIRING' ? 'room-expiring' : 'room-available');
                                @endphp

                                <button
                                    wire:click="selectRoom({{ $room->id }})"
                                    class="room-card {{ $roomClass }}"
                                    style="position: relative; border-radius: 8px; padding: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"
                                >
                                    {{-- Capacity Badge --}}
                                    <div style="position: absolute; top: 4px; right: 4px; font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: 600;
                                        {{ $room->occupancy_status === 'AVAILABLE' ? 'background: #e5e7eb; color: #374151;' : 'background: rgba(255,255,255,0.3); color: inherit;' }}">
                                        {{ $room->capacity }}x
                                    </div>

                                    <div style="text-align: center;">
                                        <div style="font-size: 18px; font-weight: bold; margin-bottom: 4px;">{{ $room->room_number }}</div>
                                        <div style="font-size: 10px; font-weight: 600; text-transform: uppercase; opacity: 0.9;">
                                            @if($room->occupancy_status === 'ACTIVE')
                                                Terisi
                                            @elseif($room->occupancy_status === 'EXPIRING')
                                                Checkout
                                            @else
                                                Kosong
                                            @endif
                                        </div>
                                        @if($room->current_tenant)
                                            <div style="font-size: 9px; margin-top: 4px; opacity: 0.8; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                {{ Str::limit($room->current_tenant->full_name, 10) }}
                                            </div>
                                        @endif
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

                <div style="display: flex; flex-direction: column; gap: 24px;">
                    {{-- Room Info --}}
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; font-size: 14px;">
                        <div>
                            <div style="color: #6b7280; margin-bottom: 4px;">Gedung</div>
                            <div style="font-weight: 600;">{{ $selectedRoom->building->name }}</div>
                        </div>
                        <div>
                            <div style="color: #6b7280; margin-bottom: 4px;">Lantai</div>
                            <div style="font-weight: 600;">{{ $selectedRoom->floor->floor_number }}</div>
                        </div>
                        <div>
                            <div style="color: #6b7280; margin-bottom: 4px;">Kapasitas</div>
                            <div style="font-weight: 600;">{{ $selectedRoom->capacity }} orang</div>
                        </div>
                        <div>
                            <div style="color: #6b7280; margin-bottom: 4px;">Status</div>
                            <div>
                                @php
                                    $statusColor = $selectedRoom->occupancy_status === 'ACTIVE' ? '#10b981' :
                                                  ($selectedRoom->occupancy_status === 'EXPIRING' ? '#f59e0b' : '#6b7280');
                                    $statusBg = $selectedRoom->occupancy_status === 'ACTIVE' ? '#d1fae5' :
                                               ($selectedRoom->occupancy_status === 'EXPIRING' ? '#fef3c7' : '#f3f4f6');
                                @endphp
                                <span style="display: inline-block; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 500; background: {{ $statusBg }}; color: {{ $statusColor }};">
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
                        <div style="border-top: 1px solid #e5e7eb; padding-top: 16px;">
                            <h4 style="font-weight: 600; margin-bottom: 12px; font-size: 16px;">Penghuni</h4>
                            <div style="background: #f9fafb; border-radius: 8px; padding: 16px;">
                                <div style="display: flex; flex-direction: column; gap: 12px; font-size: 14px;">
                                    <div style="display: flex; justify-content: space-between;">
                                        <span style="color: #6b7280;">Nama</span>
                                        <span style="font-weight: 600;">{{ $selectedRoom->current_tenant->full_name }}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between;">
                                        <span style="color: #6b7280;">WhatsApp</span>
                                        <span style="font-weight: 600;">{{ $selectedRoom->current_tenant->whatsapp_number }}</span>
                                    </div>
                                    @if($selectedRoom->current_stay)
                                        <div style="display: flex; justify-content: space-between;">
                                            <span style="color: #6b7280;">Check-in</span>
                                            <span style="font-weight: 600;">{{ \Carbon\Carbon::parse($selectedRoom->current_stay->start_at)->locale('id')->format('d M Y') }}</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between;">
                                            <span style="color: #6b7280;">Check-out</span>
                                            <span style="font-weight: 600;">{{ \Carbon\Carbon::parse($selectedRoom->current_stay->end_at)->locale('id')->format('d M Y') }}</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between;">
                                            <span style="color: #6b7280;">Durasi</span>
                                            <span style="font-weight: 600;">
                                                {{ $selectedRoom->current_stay->duration_value }} 
                                                {{ str_replace(['daily', 'weekly', 'monthly'], ['Hari', 'Minggu', 'Bulan'], $selectedRoom->current_stay->duration_type) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Payment Info --}}
                        @if($selectedRoom->current_stay && $selectedRoom->current_stay->payments->count() > 0)
                            <div style="border-top: 1px solid #e5e7eb; padding-top: 16px;">
                                <h4 style="font-weight: 600; margin-bottom: 12px; font-size: 16px;">Pembayaran Terakhir</h4>
                                @php
                                    $lastPayment = $selectedRoom->current_stay->payments->sortByDesc('payment_date')->first();
                                @endphp
                                <div style="background: #f9fafb; border-radius: 8px; padding: 16px;">
                                    <div style="display: flex; flex-direction: column; gap: 12px; font-size: 14px;">
                                        <div style="display: flex; justify-content: space-between;">
                                            <span style="color: #6b7280;">Total</span>
                                            <span style="font-weight: bold; color: #10b981;">Rp {{ number_format($lastPayment->amount, 0, ',', '.') }}</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between;">
                                            <span style="color: #6b7280;">Metode</span>
                                            <span style="font-weight: 600; text-transform: capitalize;">{{ str_replace('_', ' ', $lastPayment->payment_method) }}</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between;">
                                            <span style="color: #6b7280;">Tanggal</span>
                                            <span style="font-weight: 600;">{{ \Carbon\Carbon::parse($lastPayment->payment_date)->locale('id')->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div style="text-align: center; padding: 32px; color: #6b7280;">
                            <svg style="margin: 0 auto; width: 48px; height: 48px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <p style="margin-top: 8px;">Kamar ini sedang kosong</p>
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
