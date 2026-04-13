<?php

namespace App\Livewire;

use App\Models\Ajuste;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
class AjusteController extends Component
{
    public $ajuste_id;
    
    // Campos del formulario
    public $nombre;
    public $descripcion;
    public $sucursal;
    public $direccion;
    public $telefonos;
    public $email;
    public $pagina_web;

    public function mount()
    {
        // Cargar primera configuración
        $ajuste = Ajuste::first();
        if ($ajuste) {
            $this->ajuste_id = $ajuste->id;
            $this->nombre = $ajuste->nombre;
            $this->descripcion = $ajuste->descripcion;
            $this->sucursal = $ajuste->sucursal;
            $this->direccion = $ajuste->direccion;
            $this->telefonos = $ajuste->telefonos;
            $this->email = $ajuste->email;
            $this->pagina_web = $ajuste->pagina_web;
        }
    }

    public function save()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'sucursal' => 'required|string|max:255',
            'direccion' => 'required|string',
            'telefonos' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'pagina_web' => 'nullable|url|max:255',
        ]);

        $data = [
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'sucursal' => $this->sucursal,
            'direccion' => $this->direccion,
            'telefonos' => $this->telefonos,
            'email' => $this->email,
            'pagina_web' => $this->pagina_web,
        ];

        if ($this->ajuste_id) {
            Ajuste::find($this->ajuste_id)->update($data);
        } else {
            $nuevoAjuste = Ajuste::create($data);
            $this->ajuste_id = $nuevoAjuste->id;
        }
        
        \Illuminate\Support\Facades\Cache::forget('global_ajuste');
        
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Configuración guardada correctamente.'
        ]);
    }

    public function render()
    {
        return view('livewire.ajuste.ajuste-index');
    }
}
