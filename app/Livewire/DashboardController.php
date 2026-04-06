<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class DashboardController extends Component
{
    public function render()
    {
        $nutritionist = Auth::user();

        $activePatientsCount = 0;
        $todayAppointments = collect();

        return view('livewire.dashboard.dashboard-index', [
            'activePatientsCount' => $activePatientsCount,
            'todayAppointments' => $todayAppointments,
        ])->title('Dashboard');
    }
}
