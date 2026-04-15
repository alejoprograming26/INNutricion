<?php

namespace App\Livewire;

use App\Models\Comuna;
use App\Models\Municipio;
use App\Models\Parroquia;
use App\Models\Sector;
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
    public ?string $sector_id    = '';
    public string $nombre     = '';

    // Filtros en cascada
    public $parroquiasFiltradas = [];
    public $sectoresFiltrados = [];

    // Control de modales
    public bool $isModalOpen     = false;
    public bool $isViewModalOpen = false;

    // Datos del modal "Ver"
    public string $view_nombre     = '';
    public string $view_sector     = '';
    public string $view_parroquia  = '';
    public string $view_municipio  = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Cuando cambia el municipio, cargar parroquias y limpiar selects hijos.
     */
    public function updatedMunicipioId($value): void
    {
        $this->parroquia_id = '';
        $this->sector_id    = '';
        $this->sectoresFiltrados = [];

        $this->parroquiasFiltradas = $value
            ? Parroquia::where('municipio_id', $value)->orderBy('nombre')->get()
            : [];
    }

    /**
     * Cuando cambia la parroquia, cargar sectores.
     */
    public function updatedParroquiaId($value): void
    {
        $this->sector_id = '';

        $this->sectoresFiltrados = $value
            ? Sector::where('parroquia_id', $value)->orderBy('nombre')->get()
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
            'sector_id'    => 'required|exists:sectores,id',
            'nombre'       => 'required|string|max:255',
        ], [
            'municipio_id.required' => 'Selecciona un municipio.',
            'parroquia_id.required' => 'Selecciona una parroquia.',
            'sector_id.required'    => 'Selecciona un sector.',
            'nombre.required'       => 'El nombre de la comuna es obligatorio.',
        ]);

        // ── Validar unicidad nombre + sector (case-insensitive) ────────────
        $nombreUpper = mb_strtoupper(trim($this->nombre), 'UTF-8');

        $duplicado = \Illuminate\Support\Facades\DB::table('comunas')
            ->whereRaw('UPPER(nombre) = ?', [$nombreUpper])
            ->where('sector_id', $this->sector_id)
            ->when($this->comuna_id, fn($q) => $q->where('id', '!=', $this->comuna_id))
            ->exists();

        if ($duplicado) {
            $this->addError('nombre', 'Ya existe una comuna con ese nombre en el sector seleccionado.');
            return;
        }

        if ($this->comuna_id) {
            Comuna::findOrFail($this->comuna_id)->update([
                'sector_id' => $this->sector_id,
                'nombre'    => mb_strtoupper(trim($this->nombre), 'UTF-8'),
            ]);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Comuna actualizada exitosamente.']);
        } else {
            Comuna::create([
                'sector_id' => $this->sector_id,
                'nombre'    => mb_strtoupper(trim($this->nombre), 'UTF-8'),
            ]);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Comuna creada exitosamente.']);
        }

        $this->closeModal();
    }

    public function edit(int $id): void
    {
        $this->resetInputFields();
        $comuna = Comuna::with('sector.parroquia.municipio')->findOrFail($id);

        $this->comuna_id    = $comuna->id;
        $this->nombre       = $comuna->nombre;
        $this->sector_id    = (string) $comuna->sector_id;
        $this->parroquia_id = (string) $comuna->sector->parroquia_id;
        $this->municipio_id = (string) $comuna->sector->parroquia->municipio_id;

        // Cargar combos
        $this->parroquiasFiltradas = Parroquia::where('municipio_id', $this->municipio_id)
            ->orderBy('nombre')
            ->get();
            
        $this->sectoresFiltrados = Sector::where('parroquia_id', $this->parroquia_id)
            ->orderBy('nombre')
            ->get();

        $this->isModalOpen = true;
    }

    public function show(int $id): void
    {
        $comuna = Comuna::with('sector.parroquia.municipio')->findOrFail($id);

        $this->view_nombre    = $comuna->nombre;
        $this->view_sector    = $comuna->sector->nombre;
        $this->view_parroquia = $comuna->sector->parroquia->nombre;
        $this->view_municipio = $comuna->sector->parroquia->municipio->nombre;

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
        $this->sector_id           = '';
        $this->nombre              = '';
        $this->parroquiasFiltradas = [];
        $this->sectoresFiltrados   = [];
        $this->view_nombre         = '';
        $this->view_sector         = '';
        $this->view_parroquia      = '';
        $this->view_municipio      = '';
        $this->resetValidation();
    }

    public function render()
    {
        $comunas = Comuna::with(['sector', 'parroquia.municipio'])
            ->whereHas('sector', function ($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%')
                  ->orWhereHas('parroquia', function ($q2) {
                      $q2->where('nombre', 'like', '%' . $this->search . '%')
                         ->orWhereHas('municipio', function ($q3) {
                             $q3->where('nombre', 'like', '%' . $this->search . '%');
                         });
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
