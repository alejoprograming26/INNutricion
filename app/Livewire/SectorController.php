<?php

namespace App\Livewire;

use App\Models\Municipio;
use App\Models\Parroquia;
use App\Models\Sector;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class SectorController extends Component
{
    use WithPagination;

    // Búsqueda
    public string $search = '';

    // Campos del formulario
    public ?int $sector_id    = null;
    public ?string $municipio_id = '';
    public ?string $parroquia_id = '';
    public ?string $comuna_id    = '';
    public string $nombre     = '';

    // Combos filtrados
    public $parroquiasFiltradas = [];
    public $comunasFiltradas    = [];

    // Control de modales
    public bool $isModalOpen     = false;
    public bool $isViewModalOpen = false;

    // Datos del modal "Ver"
    public string $view_nombre     = '';
    public string $view_comuna     = '';
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
        $this->comuna_id    = '';
        $this->comunasFiltradas = [];
        $this->parroquiasFiltradas = $value
            ? Parroquia::where('municipio_id', $value)->orderBy('nombre')->get()
            : [];
    }

    /**
     * Cuando cambia la parroquia, cargar sus comunas.
     */
    public function updatedParroquiaId($value): void
    {
        $this->comuna_id = '';
        $this->comunasFiltradas = $value
            ? Comuna::where('parroquia_id', $value)->orderBy('nombre')->get()
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
            'comuna_id'    => 'required|exists:comunas,id',
            'nombre'       => 'required|string|max:255',
        ], [
            'municipio_id.required' => 'Selecciona un municipio.',
            'parroquia_id.required' => 'Selecciona una parroquia.',
            'comuna_id.required'    => 'Selecciona una comuna.',
            'nombre.required'       => 'El nombre del sector es obligatorio.',
        ]);

        // ── Validar unicidad nombre + parroquia (case-insensitive) ────────────
        $nombreUpper = mb_strtoupper(trim($this->nombre), 'UTF-8');

        $duplicado = \Illuminate\Support\Facades\DB::table('sectores')
            ->whereRaw('UPPER(nombre) = ?', [$nombreUpper])
            ->where('comuna_id', $this->comuna_id)
            ->when($this->sector_id, fn($q) => $q->where('id', '!=', $this->sector_id))
            ->exists();

        if ($duplicado) {
            $this->addError('nombre', 'Ya existe un sector con ese nombre en la comuna seleccionada.');
            return;
        }

        if ($this->sector_id) {
            Sector::findOrFail($this->sector_id)->update([
                'comuna_id' => $this->comuna_id,
                'nombre'    => mb_strtoupper(trim($this->nombre), 'UTF-8'),
            ]);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Sector actualizado exitosamente.']);
        } else {
            Sector::create([
                'comuna_id' => $this->comuna_id,
                'nombre'    => mb_strtoupper(trim($this->nombre), 'UTF-8'),
            ]);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Sector creado exitosamente.']);
        }

        $this->closeModal();
    }

    public function edit(int $id): void
    {
        $this->resetInputFields();
        $sector = Sector::with('comuna.parroquia.municipio')->findOrFail($id);

        $this->sector_id    = $sector->id;
        $this->nombre       = $sector->nombre;
        $this->comuna_id    = (string) $sector->comuna_id;
        $this->parroquia_id = (string) $sector->comuna->parroquia_id;
        $this->municipio_id = (string) $sector->comuna->parroquia->municipio_id;

        // Cargar combos
        $this->parroquiasFiltradas = Parroquia::where('municipio_id', $this->municipio_id)->orderBy('nombre')->get();
        $this->comunasFiltradas    = Comuna::where('parroquia_id', $this->parroquia_id)->orderBy('nombre')->get();

        $this->isModalOpen = true;
    }

    public function show(int $id): void
    {
        $sector = Sector::with('comuna.parroquia.municipio')->findOrFail($id);

        $this->view_nombre    = $sector->nombre;
        $this->view_comuna    = $sector->comuna->nombre;
        $this->view_parroquia = $sector->comuna->parroquia->nombre;
        $this->view_municipio = $sector->comuna->parroquia->municipio->nombre;

        $this->isViewModalOpen = true;
    }

    public function delete(int $id): void
    {
        Sector::findOrFail($id)->delete();
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Sector eliminado correctamente.']);
    }

    public function closeModal(): void
    {
        $this->isModalOpen     = false;
        $this->isViewModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields(): void
    {
        $this->sector_id           = null;
        $this->municipio_id        = '';
        $this->parroquia_id        = '';
        $this->comuna_id           = '';
        $this->nombre              = '';
        $this->parroquiasFiltradas = [];
        $this->comunasFiltradas    = [];
        $this->view_nombre         = '';
        $this->view_comuna         = '';
        $this->view_parroquia      = '';
        $this->view_municipio      = '';
        $this->resetValidation();
    }

    public function render()
    {
        $sectores = Sector::with(['comuna.parroquia.municipio'])
            ->whereHas('comuna', function ($q) {
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

        return view('livewire.sector.sector-index', [
            'sectores'   => $sectores,
            'municipios' => Municipio::orderBy('nombre')->get(),
        ]);
    }
}
