<?php

namespace App\Livewire;

use App\Models\DetalleMeta;
use App\Models\Meta;
use App\Models\Municipio;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class MetaController extends Component
{
    use WithPagination;

    // ── Búsqueda ──────────────────────────────────────────────────────────────
    public string $search = '';

    // ── Campos del formulario ─────────────────────────────────────────────────
    public ?int  $meta_id = null;
    public string $ano    = '';

    /**
     * Array indexado por municipio_id → meta_anual (string para el input).
     * Ej.: [1 => '12000', 2 => '6000', ...]
     */
    public array $detalles = [];

    // ── Control de modales ────────────────────────────────────────────────────
    public bool $isModalOpen     = false;
    public bool $isViewModalOpen = false;

    // ── Datos del modal "Ver" ─────────────────────────────────────────────────
    public int    $view_ano   = 0;
    public int    $view_total = 0;
    public array  $view_detalles = []; // [['municipio' => '...', 'meta_anual' => x, 'meta_mensual' => y], ...]

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Calcula y retorna el total de meta_anual sumando todos los detalles del formulario.
     */
    public function getTotalProperty(): int
    {
        return (int) array_sum(array_map('intval', $this->detalles));
    }

    // ── CRUD ──────────────────────────────────────────────────────────────────

    public function create(): void
    {
        $this->resetInputFields();

        // Inicializar detalles con todos los municipios en 0
        foreach (Municipio::orderBy('nombre')->get() as $municipio) {
            $this->detalles[$municipio->id] = '0';
        }

        $this->isModalOpen = true;
    }

    public function store(): void
    {
        $this->validate([
            'ano' => [
                'required',
                'integer',
                'min:2000',
                'max:2100',
                $this->meta_id
                    ? \Illuminate\Validation\Rule::unique('metas', 'ano')->ignore($this->meta_id)
                    : \Illuminate\Validation\Rule::unique('metas', 'ano'),
            ],
        ], [
            'ano.required' => 'El año es obligatorio.',
            'ano.integer'  => 'El año debe ser un número entero.',
            'ano.min'      => 'El año debe ser mayor o igual a 2000.',
            'ano.max'      => 'El año debe ser menor o igual a 2100.',
            'ano.unique'   => 'Ya existe una meta registrada para el año ' . $this->ano . '.',
        ]);

        // ── Validar que todos los municipios tengan meta_anual > 0 ────────────
        $sinMeta = collect($this->detalles)->filter(fn($v) => (int) $v <= 0);
        if ($sinMeta->isNotEmpty()) {
            $this->addError(
                'detalles',
                'Todos los municipios deben tener una meta anual mayor a 0 antes de guardar.'
            );
            return;
        }

        // Calcular el total sumando todas las meta_anual
        $total = (int) array_sum(array_map('intval', $this->detalles));

        if ($this->meta_id) {
            // ── Editar ──────────────────────────────────────────────────────
            $meta = Meta::findOrFail($this->meta_id);
            $meta->update([
                'ano'   => (int) $this->ano,
                'total' => $total,
            ]);

            // Actualizar detalles
            foreach ($this->detalles as $municipio_id => $meta_anual) {
                $metaAnual    = (int) $meta_anual;
                $metaMensual  = (int) round($metaAnual / 12);

                DetalleMeta::updateOrCreate(
                    ['meta_id' => $meta->id, 'municipio_id' => (int) $municipio_id],
                    ['meta_anual' => $metaAnual, 'meta_mensual' => $metaMensual]
                );
            }

            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Meta actualizada exitosamente.']);
        } else {
            // ── Crear ────────────────────────────────────────────────────────
            $meta = Meta::create([
                'ano'   => (int) $this->ano,
                'total' => $total,
            ]);

            foreach ($this->detalles as $municipio_id => $meta_anual) {
                $metaAnual   = (int) $meta_anual;
                $metaMensual = (int) round($metaAnual / 12);

                DetalleMeta::create([
                    'meta_id'      => $meta->id,
                    'municipio_id' => (int) $municipio_id,
                    'meta_anual'   => $metaAnual,
                    'meta_mensual' => $metaMensual,
                ]);
            }

            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Meta creada exitosamente.']);
        }

        $this->closeModal();
    }

    public function edit(int $id): void
    {
        $this->resetInputFields();
        $meta = Meta::with(['detalles.municipio'])->findOrFail($id);

        $this->meta_id = $meta->id;
        $this->ano     = (string) $meta->ano;

        // Cargar todos los municipios primero con 0
        foreach (Municipio::orderBy('nombre')->get() as $municipio) {
            $this->detalles[$municipio->id] = '0';
        }

        // Sobreescribir con los valores guardados
        foreach ($meta->detalles as $detalle) {
            $this->detalles[$detalle->municipio_id] = (string) $detalle->meta_anual;
        }

        $this->isModalOpen = true;
    }

    public function show(int $id): void
    {
        $meta = Meta::with(['detalles.municipio'])->findOrFail($id);

        $this->view_ano   = $meta->ano;
        $this->view_total = $meta->total;

        $this->view_detalles = $meta->detalles
            ->sortBy('municipio.nombre')
            ->map(fn($d) => [
                'municipio'    => $d->municipio->nombre,
                'meta_anual'   => $d->meta_anual,
                'meta_mensual' => $d->meta_mensual,
            ])->values()->toArray();

        $this->isViewModalOpen = true;
    }

    public function delete(int $id): void
    {
        $meta = Meta::findOrFail($id);
        $meta->detalles()->delete(); // Eliminar los detalles primero
        $meta->delete();
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Meta eliminada correctamente.']);
    }

    public function closeModal(): void
    {
        $this->isModalOpen     = false;
        $this->isViewModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields(): void
    {
        $this->meta_id       = null;
        $this->ano           = '';
        $this->detalles      = [];
        $this->view_ano      = 0;
        $this->view_total    = 0;
        $this->view_detalles = [];
        $this->resetValidation();
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render()
    {
        $metas = Meta::where('ano', 'like', '%' . $this->search . '%')
            ->orderByDesc('ano')
            ->paginate(10);

        // Lista de municipios para la vista (usada en el modal)
        $municipios = Municipio::orderBy('nombre')->get();

        return view('livewire.metas.metas-index', [
            'metas'      => $metas,
            'municipios' => $municipios,
        ]);
    }
}
