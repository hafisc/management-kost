<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with(['stays.room'])->get();

        $data = $tenants->map(function ($tenant) {
            $stay = $tenant->stays->sortByDesc('id')->first();
            
            $room = $stay ? $stay->room : null;
            $buildingName = $room ? str_replace('Gedung ', '', $room->building) : '-';
            $roomNumberPrefix = $room ? ($room->building === 'Gedung Putri' ? 'P' : 'L') : '';
            $roomName = $room ? $roomNumberPrefix . '-' . $room->number : '-';

            $paymentType = 'secondary';
            if ($stay) {
                $paymentType = match($stay->payment_status) {
                    'Lunas' => 'success',
                    'Pending' => 'info',
                    'Verifikasi' => 'warning',
                    default => 'secondary'
                };
            }

            $statusType = match($tenant->status) {
                'Aktif' => 'success',
                'Akan Checkout' => 'warning',
                'Berakhir' => 'warning',
                'Checkout' => 'secondary',
                default => 'secondary'
            };

            return [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'occupants' => $stay ? $stay->occupancy_type : 1,
                'whatsapp' => $tenant->whatsapp,
                'building' => $buildingName,
                'floor' => $room ? $room->floor : '-',
                'room' => $roomName,
                'check_in' => $stay ? \Carbon\Carbon::parse($stay->check_in)->translatedFormat('d M Y') : '-',
                'check_out' => $stay && $stay->check_out ? \Carbon\Carbon::parse($stay->check_out)->translatedFormat('d M Y') : '-',
                'duration' => $stay ? $stay->duration : '-',
                'total' => 'Rp 600.000',
                'payment' => [
                    'status' => $stay ? $stay->payment_status : 'Pending',
                    'type' => $paymentType
                ],
                'status' => [
                    'text' => $tenant->status,
                    'type' => $statusType
                ],
                'notes' => $tenant->notes ?? '-'
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
