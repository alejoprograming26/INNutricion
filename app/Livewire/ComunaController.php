<?php

namespace App\Livewire;

use App\Models\Comuna;
use App\Models\Municipio;
use App\Models\Parroquia;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class ComunaController extends Component
{
    use WithPagination;

    // Búsqueda
    public string $search = '';

    // Campos del formulario
    public ?int $comuna_id    = null;
    public ?string $municipio_id = '';
    public ?string $parroquia_id = '';
    public string $nombre     = '';

    // Parroquias filtradas según municipio seleccionado
    public $parroquiasFiltradas = [];

    // Control de modales
    public bool $isModalOpen     = false;
    public bool $isViewModalOpen = false;

    // Datos del modal "Ver"
    public string $view_nombre     = '';
    public string $view_parroquia  = '';
    public string $view_municipio  = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Cuando cambia el municipio en el formulario, cargar sus parroquias.
     */
    public function updatedMunicipioId($value): void
    {
        $this->parroquia_id = '';
        $this->parroquiasFiltradas = $value
            ? Parroquia::where('municipio_id', $value)->orderBy('nombre')->get()
            : [];
    }

    public function create(): void
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function store(): void
    {
        $this->validate([
            'municipio_id' => 'required|exists:municipios,id',
            'parroquia_id' => 'required|exists:parroquias,id',
            'nombre'       => 'required|string|max:255',
        ], [
            'municipio_id.required' => 'Selecciona un municipio.',
            'parroquia_id.required' => 'Selecciona una parroquia.',
            'nombre.required'       => 'El nombre de la comuna es obligatorio.',
        ]);

        // ── Validar unicidad nombre + parroquia (case-insensitive) ────────────
        $nombreUpper = mb_strtoupper(trim($this->nombre), 'UTF-8');

        $duplicado = \Illuminate\Support\Facades\DB::table('comunas')
            ->whereRaw('UPPER(nombre) = ?', [$nombreUpper])
            ->where('parroquia_id', $this->parroquia_id)
            ->when($this->comuna_id, fn($q) => $q->where('id', '!=', $this->comuna_id))
            ->exists();

        if ($duplicado) {
            $this->addError('nombre', 'Ya existe una comuna con ese nombre en la parroquia seleccionada.');
            return;
        }


        if ($this->comuna_id) {
            Comuna::findOrFail($this->comuna_id)->update([
                'parroquia_id' => $this->parroquia_id,
                'nombre'       => mb_strtoupper($this->nombre, 'UTF-8'),
            ]);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Comuna actualizada exitosamente.']);
        } else {
            Comuna::create([
                'parroquia_id' => $this->parroquia_id,
                'nombre'       => mb_strtoupper($this->nombre, 'UTF-8'),
            ]);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Comuna creada exitosamente.']);
        }

        $this->closeModal();
    }

    public function edit(int $id): void
    {
        $this->resetInputFields();
        $comuna = Comuna::with('parroquia.municipio')->findOrFail($id);

        $this->comuna_id    = $comuna->id;
        $this->nombre       = $comuna->nombre;
        $this->parroquia_id = (string) $comuna->parroquia_id;
        $this->municipio_id = (string) $comuna->parroquia->municipio_id;

        // Cargar parroquias del municipio para el select
        $this->parroquiasFiltradas = Parroquia::where('municipio_id', $this->municipio_id)
            ->orderBy('nombre')
            ->get();

        $this->isModalOpen = true;
    }

    public function show(int $id): void
    {
        $comuna = Comuna::with('parroquia.municipio')->findOrFail($id);

        $this->view_nombre    = $comuna->nombre;
        $this->view_parroquia = $comuna->parroquia->nombre;
        $this->view_municipio = $comuna->parroquia->municipio->nombre;

        $this->isViewModalOpen = true;
    }

    public function delete(int $id): void
    {
        Comuna::findOrFail($id)->delete();
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Comuna eliminada correctamente.']);
    }

    public function closeModal(): void
    {
        $this->isModalOpen     = false;
        $this->isViewModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields(): void
    {
        $this->comuna_id           = null;
        $this->municipio_id        = '';
        $this->parroquia_id        = '';
        $this->nombre              = '';
        $this->parroquiasFiltradas = [];
        $this->view_nombre         = '';
        $this->view_parroquia      = '';
        $this->view_municipio      = '';
        $this->resetValidation();
    }

    public function render()
    {
        $comunas = Comuna::with('parroquia.municipio')
            ->whereHas('parroquia', function ($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%')
                  ->orWhereHas('municipio', function ($q2) {
                      $q2->where('nombre', 'like', '%' . $this->search . '%');
                  });
            })
            ->orWhere('nombre', 'like', '%' . $this->search . '%')
            ->orderBy('id')
            ->paginate(10);

        return view('livewire.comuna.comuna-index', [
            'comunas'    => $comunas,
            'municipios' => Municipio::orderBy('nombre')->get(),
        ]);
    }
}
