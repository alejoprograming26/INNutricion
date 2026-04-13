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
    use WithFileUploads;

    public $ajuste_id;
    
    // Campos del formulario
    public $nombre;
    public $descripcion;
    public $sucursal;
    public $direccion;
    public $telefonos;
    public $email;
    public $pagina_web;

    // Imágenes (Uploads)
    public $logo;
    public $imagen_login;
    
    // Rutas de imágenes actuales
    public $logo_actual;
    public $imagen_login_actual;

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
            
            $this->logo_actual = $ajuste->logo;
            $this->imagen_login_actual = $ajuste->imagen_login;
        }
    }

    public function save()
    {
        $rules = [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'sucursal' => 'required|string|max:255',
            'direccion' => 'required|string',
            'telefonos' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'pagina_web' => 'nullable|url|max:255',
        ];

        if (is_object($this->logo)) {
            $rules['logo'] = 'image|max:2048'; // máx 2MB
        }
        if (is_object($this->imagen_login)) {
            $rules['imagen_login'] = 'image|max:4096'; // máx 4MB
        }

        $this->validate($rules);

        $data = [
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'sucursal' => $this->sucursal,
            'direccion' => $this->direccion,
            'telefonos' => $this->telefonos,
            'email' => $this->email,
            'pagina_web' => $this->pagina_web,
        ];

        // Guardar imágenes en public/storage/ajustes
        if (is_object($this->logo)) {
            if ($this->logo_actual) {
                Storage::disk('public')->delete($this->logo_actual);
            }
            $data['logo'] = $this->logo->store('ajustes', 'public');
            $this->logo_actual = $data['logo'];
        }

        if (is_object($this->imagen_login)) {
            if ($this->imagen_login_actual) {
                Storage::disk('public')->delete($this->imagen_login_actual);
            }
            $data['imagen_login'] = $this->imagen_login->store('ajustes', 'public');
            $this->imagen_login_actual = $data['imagen_login'];
        }

        if ($this->ajuste_id) {
            Ajuste::find($this->ajuste_id)->update($data);
        } else {
            $nuevoAjuste = Ajuste::create($data);
            $this->ajuste_id = $nuevoAjuste->id;
        }
        
        \Illuminate\Support\Facades\Cache::forget('global_ajuste');
        
        // Limpiar propiedades de subida para que se refleje la imagen validada y no se quede pegado el obj temporal
        $this->reset(['logo', 'imagen_login']);

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
