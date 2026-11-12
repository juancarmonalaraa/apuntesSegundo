# Ejercicio 5 · Préstamos de equipos con PDO

**Objetivo.** Programar operaciones con dos tablas relacionadas, `JOIN`, cálculos de disponibilidad y una transacción que evite prestar unidades inexistentes.

## Preparación

Ejecuta `base/schema.sql`: crea `ud2_prestamos`, las tablas `equipo(id,nombre,unidades)` y `prestamo(id,equipo_id,persona,fecha_prestamo,fecha_devolucion)` y tres equipos de muestra. Configura PDO en `config.php` e implementa `EquipoRepositorio.php` e `index.php`.

**La disponibilidad no se almacena:** es `equipo.unidades` menos los préstamos que tienen `fecha_devolucion IS NULL`.

## Métodos requeridos

| Método | Operación y devolución |
|---|---|
| `listarEquipos(): array` | Devuelve `id`, `nombre`, `unidades` y `disponibles` de cada equipo, ordenados por nombre. Calcula disponibilidad en SQL sin ejecutar una consulta por cada equipo. |
| `listarPrestamos(): array` | Devuelve los préstamos con nombre del equipo (`JOIN`), poniendo primero los abiertos y luego los cerrados. |
| `prestar(int $equipoId, string $persona): int` | Crea un préstamo abierto y devuelve su ID si hay disponibilidad; de lo contrario, lanza `DomainException`. |
| `devolver(int $prestamoId): bool` | Registra `CURDATE()` **solo en préstamos abiertos**. Devuelve si se modificó una fila. |

## Requisitos

1. Muestra una tabla **Equipo / Unidades / Disponibles** y un formulario para prestar: `equipo_id` y `persona` (2 a 80 caracteres sin espacios extremos). Valida IDs positivos, nombre y existencia del equipo.
2. Muestra otra tabla de préstamos: ID, equipo, persona, fecha de préstamo y fecha de devolución. Los abiertos tienen un botón **Devolver** que envía `POST`; los cerrados no.
3. Al prestar, comienza una transacción, ejecuta `SELECT ... FOR UPDATE` sobre el equipo para bloquearlo, calcula cuántos préstamos siguen abiertos, comprueba la disponibilidad e inserta el préstamo con `CURDATE()` y fecha de devolución `NULL`. Usa `commit()` y `rollBack()` según corresponda.
4. Si no quedan unidades, muestra **«No quedan unidades disponibles.»** sin insertar nada. Si se intenta devolver dos veces, conserva la primera fecha de devolución.
5. Las operaciones válidas redirigen a `index.php`. No imprimas resultados dentro del repositorio ni expongas errores técnicos de PDO.

## Pruebas

- Un equipo con una unidad admite un préstamo, pero no dos simultáneos.
- Al devolverlo, vuelve a quedar disponible una unidad.
- Una segunda devolución no cambia la fecha ya guardada.
- Los equipos con dos unidades permiten dos préstamos, pero no tres.
