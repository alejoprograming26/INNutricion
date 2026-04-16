# Guía de Estándares: Base de Datos y Relaciones

Este documento define la arquitectura y los estándares obligatorios para la creación de nuevas tablas, migraciones y relaciones en el sistema INNutricion. Seguir estas reglas es crítico para mantener la integridad de la **jerarquía visual** y el rendimiento de los **reportes**.

## 1. El Estándar de Jerarquía Geográfica

Cualquier tabla que registre datos transaccionales (como las 9 tablas futuras) **DEBE** seguir el patrón de almacenamiento cuádruple.

### Estructura de la Migración
Para garantizar que los reportes sean instantáneos, no guardes solo el nivel más bajo. Debes incluir los 4 niveles:

```php
$table->foreignId('municipio_id')->constrained('municipios')->onDelete('cascade');
$table->foreignId('parroquia_id')->constrained('parroquias')->onDelete('cascade');
$table->foreignId('comuna_id')->constrained('comunas')->onDelete('cascade');
$table->foreignId('sector_id')->constrained('sectores')->onDelete('cascade');

// Índices obligatorios para velocidad de búsqueda
$table->index('municipio_id');
$table->index('parroquia_id');
$table->index('comuna_id');
$table->index('sector_id');
```

> [!IMPORTANT]
> **¿Por qué guardamos los 4 IDs?**
> Para permitir reportes visuales con "Drill-down". Si quieres ver un gráfico del total por Municipio, la consulta solo lee `municipio_id` sin necesidad de unir (Join) 4 tablas. Esto hace que el sistema sea escalable a millones de registros.

---

## 2. Convenciones de Nomenclatura

*   **Tablas**: Plural en español (ej: `transcripciones`, `ajustes`, `metas`).
*   **Llaves Foráneas**: Singular del nombre de la tabla + `_id` (ej: `municipio_id`).
*   **Campos de Auditoría**: Siempre incluir `$table->timestamps()`.

---

## 3. Integridad Referencial (Foreign Keys)

1.  **Restricciones**: Todas las llaves foráneas deben usar `onDelete('cascade')` para asegurar que no queden datos huérfanos si se elimina una entidad superior.
2.  **Unicidad**: Usa `unique()` para evitar datos duplicados lógicos. 
    *   *Ejemplo en Metas:* `$table->unique(['meta_id', 'municipio_id']);` garantiza que un municipio no tenga dos metas para el mismo periodo.

---

## 4. Tipos de Datos y Performance

*   **Enums**: Úsalos para campos con opciones fijas (ej: `tipo`, `status`). Esto ayuda a la integridad y es más rápido que un String abierto.
*   **Cantidades**: Usa `integer` o `decimal(12,2)` según sea necesario. Siempre define `default(0)` para evitar errores en cálculos.
*   **Fechas**: Usa `$table->date('fecha')` para transacciones. Si necesitas filtrar por año o mes frecuentemente en reportes, considera añadir índices adicionales.

---

## 5. Preparación para Reportes Visuales

Al crear las próximas 9 tablas, asegúrate de que los campos numéricos que se van a sumar o promediar estén claramente identificados.

> [!TIP]
> Si una tabla requiere métricas temporales (Anual, Mensual, Semanal), asegúrate de que el campo `fecha` esté indexado (`$table->index('fecha')`).

---

## Checklist para nuevas Migraciones

- [ ] ¿Tiene los 4 niveles de jerarquía geográfica?
- [ ] ¿Están definidos los `onDelete('cascade')`?
- [ ] ¿Se añadieron los índices (`$table->index`) a las llaves foráneas?
- [ ] ¿La tabla sigue la nomenclatura en plural?
- [ ] ¿Existen restricciones de integridad (`unique`, `nullable`) adecuadas?

---

*Este documento es dinámico y debe actualizarse a medida que el sistema evolucione hacia los módulos de visualización avanzada.*
