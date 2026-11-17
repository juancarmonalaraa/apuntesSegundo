# Ejercicio 7 · Catálogo de equipos

**Objetivo.** Crea un catálogo de material del centro con PHP y PDO. Los equipos se almacenan en una sola tabla con su nombre y el número de unidades disponibles.

## Preparación

Importa schema.sql en phpMyAdmin. Se creará la base de datos ud2_catalogo y una tabla equipo(id, nombre, unidades) con tres registros iniciales. Trabaja con los archivos config.php, EquipoRepositorio.php e index.php.

## Métodos requeridos

| Método | Operación |
|---|---|
| `listar(): array` | Devuelve los equipos ordenados alfabéticamente por nombre mediante SELECT y fetchAll(). |
| `crear(string $nombre, int $unidades): void` | Comprueba los datos e inserta un equipo mediante una consulta preparada INSERT. |
| `actualizarUnidades(int $id, int $unidades): void` | Comprueba que el equipo existe y actualiza su número de unidades mediante UPDATE preparado. |
| `eliminar(int $id): void` | Comprueba que el equipo existe y lo elimina mediante DELETE preparado. |

## Requisitos

1. En index.php, muestra una tabla con ID, Nombre y Unidades de todos los equipos.
2. Añade un formulario para registrar un equipo nuevo: nombre de 2 a 80 caracteres y unidades como número entero mayor o igual que cero.
3. Permite cambiar el número de unidades de cada equipo utilizando un formulario que envíe su ID y las nuevas unidades por POST.
4. Incluye un botón Eliminar para cada fila. Elimina únicamente el equipo cuyo ID se ha enviado.
5. Comprueba los datos en el servidor y utiliza consultas preparadas para INSERT, UPDATE y DELETE. No construyas el SQL concatenando entradas del formulario.
6. Después de una modificación correcta, redirige a index.php. El repositorio no debe imprimir HTML. Escapa los nombres antes de mostrarlos en la página.

## Pruebas

- Se muestran los tres equipos iniciales.
- Crear un equipo válido hace que aparezca en el listado.
- Cambiar sus unidades a cero es válido; un valor negativo no lo es.
- Después de eliminar un equipo, deja de aparecer en la tabla.
