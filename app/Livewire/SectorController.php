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
            'nombre.required'       => 'El nombre del sector es obligatorio.',
        ]);

        // ── Validar unicidad nombre + parroquia (case-insensitive) ────────────
        $nombreUpper = mb_strtoupper(trim($this->nombre), 'UTF-8');

        $duplicado = \Illuminate\Support\Facades\DB::table('sectores')
            ->whereRaw('UPPER(nombre) = ?', [$nombreUpper])
            ->where('parroquia_id', $this->parroquia_id)
            ->when($this->sector_id, fn($q) => $q->where('id', '!=', $this->sector_id))
            ->exists();

        if ($duplicado) {
            $this->addError('nombre', 'Ya existe un sector con ese nombre en la parroquia seleccionada.');
            return;
        }

        if ($this->sector_id) {
            Sector::findOrFail($this->sector_id)->update([
                'parroquia_id' => $this->parroquia_id,
                'nombre'       => mb_strtoupper(trim($this->nombre), 'UTF-8'),
            ]);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Sector actualizado exitosamente.']);
        } else {
            Sector::create([
                'parroquia_id' => $this->parroquia_id,
                'nombre'       => mb_strtoupper(trim($this->nombre), 'UTF-8'),
            ]);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Sector creado exitosamente.']);
        }

        $this->closeModal();
    }

    public function edit(int $id): void
    {
        $this->resetInputFields();
        $sector = Sector::with('parroquia.municipio')->findOrFail($id);

        $this->sector_id    = $sector->id;
        $this->nombre       = $sector->nombre;
        $this->parroquia_id = (string) $sector->parroquia_id;
        $this->municipio_id = (string) $sector->parroquia->municipio_id;

        // Cargar parroquias del municipio para el select
        $this->parroquiasFiltradas = Parroquia::where('municipio_id', $this->municipio_id)
            ->orderBy('nombre')
            ->get();

        $this->isModalOpen = true;
    }

    public function show(int $id): void
    {
        $sector = Sector::with('parroquia.municipio')->findOrFail($id);

        $this->view_nombre    = $sector->nombre;
        $this->view_parroquia = $sector->parroquia->nombre;
        $this->view_municipio = $sector->parroquia->municipio->nombre;

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
        $this->nombre              = '';
        $this->parroquiasFiltradas = [];
        $this->view_nombre         = '';
        $this->view_parroquia      = '';
        $this->view_municipio      = '';
        $this->resetValidation();
    }

    public function render()
    {
        $sectores = Sector::with(['parroquia', 'municipio'])
            ->whereHas('parroquia', function ($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%')
                  ->orWhereHas('municipio', function ($q2) {
                      $q2->where('nombre', 'like', '%' . $this->search . '%');
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
