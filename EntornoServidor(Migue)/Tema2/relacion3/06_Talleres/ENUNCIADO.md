# Ejercicio 6 · Talleres con lista de espera automática

**Objetivo.** Implementar una regla de negocio con aforo limitado, inscripciones duplicadas y promoción automática de personas en espera al cancelar.

## Preparación

Ejecuta `base/schema.sql`: crea `ud2_talleres`, la tabla `taller(id,titulo,plazas)` y la tabla `inscripcion(id,taller_id,nombre,email,estado,creado)`. `estado` puede ser `confirmada` o `espera`; la pareja `(taller_id,email)` es única. Hay dos talleres precargados: uno con dos plazas y otro con tres.

## Métodos requeridos

| Método | Operación y devolución |
|---|---|
| `listarTalleres(): array` | Devuelve cada taller con `plazas`, `confirmadas` y `en_espera`, mediante SQL y ordenados por título. |
| `listarInscripciones(): array` | Devuelve inscripciones junto al título del taller (`JOIN`), ordenadas por taller, confirmadas antes que espera e ID ascendente. |
| `inscribir(int $tallerId, string $nombre, string $email): string` | Inscribe y devuelve exactamente `confirmada` o `espera`. |
| `cancelar(int $inscripcionId): bool` | Cancela y, si estaba confirmada, promociona a la primera persona en espera del mismo taller. Devuelve `false` si no existe. |

## Requisitos

1. Muestra por taller las plazas totales, confirmadas y personas en espera. Añade un formulario con taller, nombre (2 a 80 caracteres) y correo válido. Normaliza el correo a minúsculas y sin espacios extremos.
2. Si hay plazas disponibles, inscribe con estado `confirmada`. Si el aforo está completo, guarda con estado `espera`. **No se rechaza** una inscripción solo por estar lleno.
3. Impide dos inscripciones del mismo correo en un taller, incluso si la primera está en espera. El correo sí puede inscribirse en otro taller distinto.
4. Muestra un listado con nombre, correo, taller, estado y botón **Cancelar** por `POST`.
5. Al cancelar una persona confirmada, asciende a `confirmada` la persona en espera del mismo taller con **menor ID**. Cancelar a una persona en espera no asciende a nadie.
6. `inscribir()` y `cancelar()` deben utilizar transacciones. Bloquea la fila del taller con `SELECT ... FOR UPDATE` antes de tomar decisiones sobre aforo. Para cancelar, identifica el taller, bloquéalo y vuelve a verificar la inscripción. El borrado y la posible promoción son **una sola operación atómica**.
7. Utiliza la lógica transaccional para que nunca haya más personas confirmadas que plazas. La restricción `UNIQUE` de MySQL impide los duplicados por taller y correo. Evita mostrar mensajes SQL literales y redirige tras cada cambio válido.

## Pruebas

- En el taller de dos plazas, las dos primeras personas quedan confirmadas; la tercera y cuarta, en espera.
- Si cancelas una de las dos confirmadas, asciende la **tercera**, no la cuarta.
- Cancelar a la cuarta (en espera) no cambia las confirmadas.
- El mismo correo no puede apuntarse dos veces al mismo taller, pero sí a otro taller.
- Sin lista de espera, cancelar una confirmada deja una plaza libre para el siguiente registro.
