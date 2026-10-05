<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Stay;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $rooms = Room::with(['activeStay.tenant'])->get();
        
        $totalRooms = $rooms->count();
        $terisi = 0;
        $kosong = 0;
        
        $pendapatanBulanIni = 0;
        $akanCheckoutCount = 0;
        
        $upcomingDue = [];
        $today = Carbon::now();

        foreach ($rooms as $room) {
            if ($room->activeStay) {
                $terisi++;
                $price = $room->price ?? 600000;
                $pendapatanBulanIni += $price;
                
                $endDate = Carbon::parse($room->activeStay->end_date ?? $room->activeStay->check_out);
                $daysUntilCheckout = $today->diffInDays($endDate, false);
                
                if ($daysUntilCheckout >= 0 && $daysUntilCheckout <= 7) {
                    $akanCheckoutCount++;
                    $roomPrefix = str_contains(strtolower($room->building), 'putri') ? 'P-' : 'L-';
                    $upcomingDue[] = [
                        'id' => $room->activeStay->id,
                        'name' => $room->activeStay->tenant->name,
                        'room' => $roomPrefix . str_pad($room->number, 2, '0', STR_PAD_LEFT),
                        'due' => $endDate->format('d M Y'),
                        'amount' => 'Rp ' . number_format($price, 0, ',', '.'),
                        'days' => (int) $daysUntilCheckout
                    ];
                }
            } else {
                $kosong++;
            }
        }
        
        // Sort upcoming due by days
        usort($upcomingDue, function($a, $b) {
            return $a['days'] <=> $b['days'];
        });

        // Use latest stay as one of the activities, then mock others for variety
        $latestStay = Stay::with(['tenant', 'room'])->latest()->first();
        $recentActivities = [];
        
        if ($latestStay) {
            $room = $latestStay->room;
            $roomPrefix = $room ? (str_contains(strtolower($room->building), 'putri') ? 'P-' : 'L-') : '';
            $roomName = $room ? $roomPrefix . str_pad($room->number, 2, '0', STR_PAD_LEFT) : '-';
            
            $recentActivities[] = [
                'id' => 1,
                'name' => $latestStay->tenant->name ?? 'Penghuni',
                'action' => 'memulai masa sewa di',
                'room' => $roomName,
                'time' => $latestStay->created_at ? $latestStay->created_at->diffForHumans() : '1 jam yang lalu',
                'amount' => 'Rp ' . number_format($price, 0, ',', '.')
            ];
        }

        $recentActivities = array_merge($recentActivities, [
            ['id' => 2, 'name' => 'Bot WhatsApp', 'action' => 'mengirim pengingat tagihan ke', 'room' => '3 Penghuni', 'time' => '2 jam yang lalu', 'amount' => null],
            ['id' => 3, 'name' => 'Admin', 'action' => 'mengonfirmasi pembayaran dari', 'room' => 'P-05', 'time' => '4 jam yang lalu', 'amount' => null],
            ['id' => 4, 'name' => 'Sistem', 'action' => 'mencadangkan database', 'room' => 'Otomatis', 'time' => '1 hari yang lalu', 'amount' => null],
        ]);
        
        // Take only 4 items max
        $recentActivities = array_slice($recentActivities, 0, 4);

        // Chart Data (Mocking past 6 months)
        $chartData = [
            ['month' => $today->copy()->subMonths(5)->format('M'), 'value' => 60],
            ['month' => $today->copy()->subMonths(4)->format('M'), 'value' => 75],
            ['month' => $today->copy()->subMonths(3)->format('M'), 'value' => 85],
            ['month' => $today->copy()->subMonths(2)->format('M'), 'value' => 70],
            ['month' => $today->copy()->subMonths(1)->format('M'), 'value' => 90],
            ['month' => $today->format('M'), 'value' => 100],
        ];

        return response()->json([
            'status' => 'success',
            'data' => [
                'stats' => [
                    'pendapatan' => [
                        'value' => 'Rp ' . number_format($pendapatanBulanIni, 0, ',', '.'),
                        'trend' => '+12.5%',
                        'trendUp' => true
                    ],
                    'terisi' => [
                        'value' => $terisi,
                        'trend' => $totalRooms > 0 ? round(($terisi / $totalRooms) * 100) . '% dari total' : '0%',
                        'trendUp' => true
                    ],
                    'kosong' => [
                        'value' => $kosong,
                        'trend' => $totalRooms > 0 ? round(($kosong / $totalRooms) * 100) . '% dari total' : '0%',
                        'trendUp' => false
                    ],
                    'checkout' => [
                        'value' => $akanCheckoutCount,
                        'trend' => 'Dalam 7 hari',
                        'trendUp' => false
                    ]
                ],
                'upcomingDue' => array_slice($upcomingDue, 0, 4),
                'recentActivities' => $recentActivities,
                'chartData' => $chartData
            ]
        ]);
    }
}
