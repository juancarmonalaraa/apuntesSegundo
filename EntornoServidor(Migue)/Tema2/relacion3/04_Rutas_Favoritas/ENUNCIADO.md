# Ejercicio 4 · Tres rutas favoritas guardadas en cookies

**Objetivo.** Gestionar una lista ordenada de favoritos persistida en una cookie JSON. No hay carrito, cantidades ni precios.

## Material inicial

En `base/rutas.php` hay cinco rutas con código, nombre, kilómetros y dificultad. Implementa `index.php`, `accion.php` y `vaciar.php`. No uses base de datos ni sesiones.

## Requisitos

1. `index.php` muestra las cinco rutas. Cada una tiene un botón **Añadir** o **Quitar** según se encuentre entre las favoritas. Estos botones envían `POST` a `accion.php` con los campos `accion` (`anadir` o `quitar`) y `codigo`.
2. Guarda las favoritas en **una sola cookie** llamada `rutas_favoritas`, con un JSON como `["R1","R3"]`, duración de **30 días**, ruta `/`, `httponly=true` y `samesite=Lax`.
3. Se permiten **como máximo tres** rutas, **sin duplicados**, y se conserva el orden en el que se han añadido. Añadir una ruta ya seleccionada o quitar una que no existe no debe alterar las restantes.
4. Si el usuario intenta añadir la cuarta, no cambies las favoritas y muestra el aviso **«Solo puedes guardar tres rutas.»**. Puedes redirigir a `index.php?aviso=limite`.
5. Si la cookie no existe, no contiene JSON válido, o no contiene un array, comienza con favoritos vacíos. Si hay códigos desconocidos o repetidos, ignóralos y conserva, por orden, los **tres primeros códigos válidos distintos**.
6. Muestra debajo del catálogo el listado ordenado de favoritas y la **suma de kilómetros**, calculada **exclusivamente con los datos de `rutas.php`**, nunca con datos de la cookie. Si la lista está vacía, muestra **«No tienes rutas favoritas.»**.
7. **Vaciar favoritos** envía `POST` a `vaciar.php`, que caduca la cookie y redirige. `accion.php` y `vaciar.php` responden con `405` ante `GET`.

## Pruebas

- Añadir R1 y R3 mantiene ese orden y suma sus kilómetros.
- Añadir R1 otra vez no la duplica. Quitar R1 conserva R3.
- Una cuarta ruta no se incorpora y aparece el aviso.
- Si la cookie contiene `["R3","X99","R3","R1"]`, solo aparecen R3 y R1.
- Vaciar favoritas deja la lista vacía tras redirigir.
