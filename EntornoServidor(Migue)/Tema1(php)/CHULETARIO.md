# Chuletario PHP para el examen

Basado en los ejercicios de repaso: agenda electoral, pedido, escrutinio y pactómetro. Pensado para consultar rápido.

## 1. Esqueleto de cualquier ejercicio (el orden se repite SIEMPRE)

1. `require_once` de los datos y de las funciones.
2. Comprobar el método (`POST` o `GET`). Si no coincide: `http_response_code(405); exit('mensaje');`
3. Leer los datos con `??`.
4. Validar cada dato (un `if` + `exit` por dato).
5. Calcular (bucles, acumuladores, fórmulas).
6. Preparar las variables que necesita la vista.
7. `require` de la vista (sin `echo` sueltos ni TODO de relleno).

## 2. Lo que más se ha repetido en los ejercicios

| Patrón | Aparece en |
|---|---|
| Comprobar método con `$_SERVER['REQUEST_METHOD']` | todos |
| Leer con `$_POST['x'] ?? null` | todos |
| Validar texto contra una lista (`in_array`) o un catálogo (`array_key_exists`) | agenda, pedido, pactómetro |
| Validar enteros con `filter_var` + `=== false` | agenda, pedido, escrutinio |
| Checkboxes: array, validar cada uno, quitar repetidos, contar | pedido, pactómetro |
| `foreach` con acumulador (`+=`) | pedido, escrutinio, pactómetro, agenda |
| Filtrar con varias condiciones (`&&`, `\|\|`) | agenda, pactómetro |
| Ordenar (`arsort`) y recortar (`array_slice`) | agenda, escrutinio |
| Booleano para decidir qué mensaje mostrar | pedido, escrutinio, pactómetro |
| Funciones auxiliares (`escapar`, `euros`, `calcularMayoria`) | pedido, pactómetro |
| Vista con `foreach` / `if` / `<?= ?>` | todos |

## 3. Tipos de array

### Lista simple (sin `=>`)

```php
$partidos = ['PSOE', 'PP', 'Vox'];
$partidos[0];           // 'PSOE'
$partidos[] = 'Sumar';  // añadir al final
foreach ($partidos as $p) { }
```

Comprobar un VALOR: `in_array('PP', $partidos, true)`.

### Asociativo (clave => valor)

```php
$escanos = ['PSOE' => 14, 'PP' => 12];
$escanos['PSOE'];        // 14
$escanos['Vox'] = 7;     // añadir o cambiar
foreach ($escanos as $partido => $n) { }
```

Comprobar una CLAVE: `array_key_exists('PSOE', $escanos)`.

### Array de arrays (lista de registros), como `$actos`

```php
$actos = [
    ['fecha' => '2026-11-02', 'aforo' => 250],
    ['fecha' => '2026-11-03', 'aforo' => 120],
];
$actos[0]['aforo'];                 // 250
foreach ($actos as $acto) { $acto['aforo']; }
```

En el bucle, `$acto` (singular) es UNA ficha y `$actos` (plural) es la lista entera.

### Catálogo (asociativo de arrays), como `$packs`

```php
$packs = ['basico' => ['nombre' => 'Kit básico', 'precio' => 35]];
$packs['basico']['precio'];         // 35
array_key_exists($pack, $packs);    // validar el pack que llega
$packs[$pack]['precio'];            // precio del pack elegido
```

### Anidado, como `$escenarios`

```php
$escenarios['sierra']['partidos']['PSOE'];   // 14
$partidosEscenario = $escenarios[$escenario]['partidos'];  // truco: guardarlo en una variable
```

### Regla para elegir entre `in_array` y `array_key_exists`

Mira el array y pregunta: el texto que me llega, ¿está a la izquierda o a la derecha de la `=>`?

| Situación | Función |
|---|---|
| Lista simple (sin `=>`) o el texto es un VALOR | `in_array($texto, $array, true)` |
| El texto es una CLAVE (a la izquierda de `=>`) | `array_key_exists($texto, $array)` |

## 4. Funciones de arrays

| Función | Dentro del paréntesis | Qué hace / devuelve | Ejemplo |
|---|---|---|---|
| `count` | `($array)` | Número de elementos | `count($votos)` |
| `in_array` | `($valor, $array, true)` | `true` si el VALOR está (`true` = estricto) | `in_array('PP', $partidos, true)` |
| `array_key_exists` | `($clave, $array)` | `true` si la CLAVE está | `array_key_exists('basico', $packs)` |
| `isset` | `($array['k'])` | `true` si existe y no es `null` | `isset($_POST['x'])` |
| `empty` | `($variable)` | `true` si está vacío (array vacío, 0, '', null) | `empty($extras)` |
| `is_array` | `($x)` | `true` si es array | `is_array($socios)` |
| `array_sum` | `($array)` | Suma de los valores | `array_sum($votos)` |
| `array_unique` | `($array)` | Quita valores repetidos | `array_unique($socios)` |
| `array_slice` | `($array, $desde, $cuantos)` | Porción del array (no modifica el original) | `array_slice($lista, 0, $limite)` |
| `array_keys` | `($array)` | Lista con las claves | `array_keys($votos)` |
| `array_values` | `($array)` | Lista con los valores, reindexada 0, 1, 2... | `array_values($ordenados)` |
| `array_key_first` | `($array)` | Primera clave | `array_key_first($ordenados)` |
| `array_key_last` | `($array)` | Última clave | `array_key_last($ordenados)` |
| `array_column` | `($array, 'columna')` | Lista con esa columna de cada registro | `array_column($actos, 'aforo')` |
| `array_flip` | `($array)` | Intercambia claves y valores (solo textos o enteros) | `array_flip($elegidos)` |
| `array_search` | `($valor, $array)` | Clave donde está el valor, o `false` | `array_search('PP', $partidos)` |
| `array_merge` | `($a, $b)` | Une arrays | `array_merge($a, $b)` |
| `array_intersect_key` | `($a, $b)` | Entradas de `$a` cuyas claves están en `$b` | `array_intersect_key($extras, array_flip($elegidos))` |
| `array_rand` | `($array, $n)` | Clave(s) al azar. Con `$n` = 1 devuelve una clave; con más, un array (`(array)` lo unifica) | `(array) array_rand($preguntas, $n)` |
| `array_filter` | `($array, función)` | Se queda con los que cumplen | `array_filter($a, fn($x) => $x > 5)` |
| `array_map` | `(función, $array)` | Transforma cada elemento | `array_map('trim', $a)` |
| `max` / `min` | `($array)` | Mayor / menor valor | `max($votos)` |
| `implode` | `(', ', $array)` | Array a texto | `implode(', ', $socios)` |
| `explode` | `(',', $texto)` | Texto a array | `explode(',', 'a,b')` |
| `array_push` | `($array, $valor)` | Añade al final (igual que `$array[] = $valor`) | `array_push($lista, 5)` |

### Ordenar (modifican el propio array: NO se asignan a una variable)

| Función | Orden | ¿Conserva las claves? |
|---|---|---|
| `sort($a)` | menor a mayor por valor | no |
| `rsort($a)` | mayor a menor por valor | no |
| `asort($a)` | menor a mayor por valor | sí |
| `arsort($a)` | mayor a menor por valor | sí |
| `ksort($a)` / `krsort($a)` | por clave (menor a mayor / mayor a menor) | sí |
| `usort($a, función)` | orden personalizado | no |

```php
arsort($ordenados);   // correcto
$b = sort($a);        // ERROR: sort devuelve true, no el array
usort($actos, fn($a, $b) => $b['aforo'] <=> $a['aforo']);   // de mayor a menor aforo
```

Para un ranking de partido => votos usa `arsort` (conserva los nombres).

## 5. Formulario HTML

```html
<form method="post" action="procesar.php">
    <select name="partido" id="partido" required>
        <option value="PSOE">PSOE</option>
    </select>

    <fieldset>
        <legend>Pack</legend>
        <label><input type="radio" name="pack" value="basico" required> básico</label>
        <label><input type="radio" name="pack" value="movil"> móvil</label>
    </fieldset>

    <fieldset>
        <legend>Extras</legend>
        <label><input type="checkbox" name="extras[]" value="chapas"> chapas</label>
    </fieldset>

    <input type="number" name="unidades" min="1" max="8" value="1" required>
    <input type="number" name="votos[PSOE]" min="0" value="0" required>
    <input type="text" name="codigo">
    <button type="submit">Enviar</button>
</form>
```

- `name="x[]"` llega a PHP como array (`$_POST['x']`). `name="votos[PSOE]"` llega como array con nombres.
- Los radios y checkboxes que no se marcan NO se envían: usa `?? null` en radios y `?? []` en checkboxes.
- `required`, `min` y `max` NO bastan: el PHP siempre vuelve a validar.
- `<legend>` solo va dentro de un `<fieldset>`. `for` es de `<label>`, no de `<legend>`.
- Los `value` del HTML deben coincidir EXACTAMENTE con lo que valida el PHP (mayúsculas y nombre del archivo del `action` también).

## 6. Validación de datos

```php
$x = $_POST['x'] ?? null;          // valor suelto
$lista = $_POST['lista'] ?? [];    // checkboxes
```

`$_GET` en lugar de `$_POST` si el formulario es `method="get"`.

| Qué valido | Cómo |
|---|---|
| Texto contra una lista | `!is_string($x) \|\| !in_array($x, $lista, true)` |
| Texto contra un catálogo con claves | `!is_string($x) \|\| !array_key_exists($x, $catalogo)` |
| Texto que admite 'Todos' (filtros) | `!is_string($x) \|\| ($x !== 'Todos' && !in_array($x, $lista, true))` |
| Entero en rango | `filter_var` (ver abajo) |
| Entero >= 0 | `filter_var` con solo `min_range` 0 |
| Array de checkboxes | `is_array`, `foreach` validando cada uno, rechazar repetidos, `count` entre mínimo y máximo |
| Texto opcional (código) | `is_string`, luego `trim` |

```php
$n = filter_var($n, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 10]]);
if ($n === false) {
    exit('Número no válido.');
}
```

- Se compara con `=== false` (tres signos) porque el 0 puede ser un valor válido.
- `filter_var` devuelve ya el número convertido a entero: reasígnalo a la variable.
- `filter_var` solo valida valores sueltos, nunca un array entero (valida cada elemento con un `foreach`).
- `trim` solo quita espacios del principio y del final, no los del medio.
- Rechazar repetidos: `count(array_unique($a)) !== count($a)`.

## 7. Operadores y funciones útiles

| Símbolo / función | Significado |
|---|---|
| `&&` / `\|\|` / `!` | y / o / no |
| `===` / `!==` | igual / distinto estricto (mismo valor y tipo) |
| `??` | valor por defecto si no existe o es null |
| `$a ? 'x' : 'y'` | ternario (if en una línea) |
| `+=`, `-=`, `++` | sumar a la variable, restar, sumar 1 |
| `/` | división con decimales (41 / 2 = 20.5) |
| `intdiv($a, $b)` | división entera (intdiv(41, 2) = 20) |
| `%` | resto de la división |
| `round($n, 2)` | redondea a 2 decimales (no rellena ceros) |
| `number_format($n, 2, ',', '.')` | formato 1.234,50 (siempre 2 decimales) |
| `abs($n)` | valor absoluto (quita el signo) |
| `htmlspecialchars((string) $x, ENT_QUOTES, 'UTF-8')` | escapa texto para mostrarlo en HTML |
| `strtoupper`, `ucfirst`, `strlen` | mayúsculas, primera en mayúscula, longitud |
| Decimales | con PUNTO: `0.21`, nunca `0,21` |

## 8. Fórmulas que han salido

| Qué | Fórmula |
|---|---|
| Porcentaje | `$parte / $total * 100` |
| Descuento en % | `$importe * $descuento / 100` |
| IVA | `$base * $ivaPorcentaje / 100`; total = `$base + $iva` |
| Mayoría absoluta | `intdiv($total, 2) + 1` |
| Abstención | `$censo - $emitidos` |
| Participación | `$emitidos / $censo * 100` |
| Válidos / emitidos | válidos = candidaturas + blancos; emitidos = válidos + nulos |
| Acumulador | `$total = 0;` antes del bucle y `$total += $x;` dentro |
| Contador | `$n = 0;` antes y `$n++;` dentro |
| El menor / el mejor | variable en `null` antes del bucle; dentro, si es `null` o mejora, te lo quedas |
| Empate en el primero | tras `arsort`, `array_values($ordenados)[0] === array_values($ordenados)[1]` |

Orden típico en pedidos: mercancía, descuento, más transporte (sin descontarlo) = base, IVA sobre la base, total.

## 9. Vista (sintaxis alterna)

```php
<?= $variable ?>                         <!-- igual que <?php echo $variable; ?> -->

<?php foreach ($lista as $clave => $valor): ?>
    ...
<?php endforeach; ?>

<?php if ($condicion): ?>
    ...
<?php elseif ($otra): ?>
    ...
<?php else: ?>
    ...
<?php endif; ?>
```

- Dos puntos `:` y `endif;` / `endforeach;` en lugar de llaves.
- La vista NO calcula nada: solo muestra las variables que le deja el archivo de lógica.
- Se carga con `require` al FINAL, y ve todas las variables del archivo que la carga.
- Comentario en una vista: `<?php // texto ?>` (el `?>` es obligatorio) o `<!-- texto -->`.
- Si el enunciado prohíbe `echo`, CSS o JavaScript, no los uses.

## 10. Errores típicos

| Mensaje o síntoma | Causa más probable | Solución |
|---|---|---|
| `Parse error: syntax error` | Falta un `;` o una llave | Revisa la línea indicada y la anterior |
| `Undefined variable $x` | Variable no creada, mal escrita, o plural y singular mezclados | Crearla antes o corregir el nombre |
| `Undefined array key "x"` | La clave no existe | Usa `??` o comprueba con `array_key_exists` |
| `Array to string conversion` | Imprimes un array, o se lo pasas a una función que espera texto | Recorre el array o usa `implode` |
| `array_key_exists(): Argument #2 must be of type array, null given` | El array que consultas no existe | Variable mal nombrada, o falta el `require_once` |
| `count(): Argument must be Countable` | `count` sobre un texto o un número | `count` es solo para arrays |
| `Call to undefined function x()` | Función sin definir o `funciones.php` sin cargar | Definirla antes de usarla |
| El navegador descarga el `.php` | No se ejecuta por un servidor PHP | Usa XAMPP o `php -S localhost:8000` |
| Sale 'Pendiente de implementar' | `echo` sin borrar o `require` comentado | Borra el `echo` y quita el `//` |
| Todo se rechaza | HTML y PHP no usan los mismos valores (`Todas` y `TodasProvincias`) | Unificar valores |
| Se pisa el catálogo | Misma variable para el catálogo y lo elegido (`$extras`) | Usar `$extrasElegidos` |
| Rechaza el valor 0 | Comprobaste con `!$x` | Usa `=== false` |

## 11. Plantilla general (lógica)

```php
<?php
require_once __DIR__ . '/datos.php';        // arrays de datos o catálogo
require_once __DIR__ . '/funciones.php';    // escapar(), euros(), ...

// 1. MÉTODO (cambia 'POST' por 'GET' si el formulario es GET)
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Envía el formulario.');
}

// 2. LEER (valor suelto: ?? null | checkboxes: ?? [] | texto opcional: ?? '')
$opcion = $_POST['opcion'] ?? null;
$numero = $_POST['numero'] ?? null;
$elegidos = $_POST['elegidos'] ?? [];
$codigo = $_POST['codigo'] ?? '';

// 3. VALIDAR
// 3a. texto contra una LISTA simple
if (!is_string($opcion) || !in_array($opcion, $lista, true)) {
    exit('Opción no válida.');
}
// 3b. texto contra un CATÁLOGO con claves
// if (!is_string($opcion) || !array_key_exists($opcion, $catalogo)) { exit('...'); }
// 3c. filtro que admite 'Todos'
// if (!is_string($x) || ($x !== 'Todos' && !in_array($x, $lista, true))) { exit('...'); }

// 3d. entero en rango
$numero = filter_var($numero, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 10]]);
if ($numero === false) {
    exit('Número no válido.');
}

// 3e. checkboxes: array, cada valor válido, sin repetidos, cantidad entre mínimo y máximo
if (!is_array($elegidos)) {
    exit('Selección no válida.');
}
foreach ($elegidos as $elegido) {
    if (!is_string($elegido) || !array_key_exists($elegido, $catalogo)) {
        exit('Elemento no válido.');
    }
}
if (count(array_unique($elegidos)) !== count($elegidos)) {
    exit('Hay elementos repetidos.');
}
if (count($elegidos) < 1 || count($elegidos) > 3) {
    exit('Debes elegir entre 1 y 3.');
}

// 3f. texto opcional (código): si no está en el catálogo, simplemente no se aplica
if (!is_string($codigo)) {
    exit('Código no válido.');
}
$codigo = trim($codigo);

// 4. CALCULAR
$total = 0;                  // acumulador
$coincidencias = [];         // lista de resultados
foreach ($datos as $fila) {
    if (($filtro1 === 'Todos' || $fila['campo1'] === $filtro1) &&
        ($filtro2 === 'Todos' || $fila['campo2'] === $filtro2)) {
        $coincidencias[] = $fila;
        $total += $fila['importe'];
    }
}
$cantidad = count($coincidencias);      // contar ANTES de recortar

// 5. ORDENAR Y RECORTAR
// arsort($ranking);                                  // mayor a menor conservando claves
$visibles = array_slice($coincidencias, 0, $limite);  // los primeros $limite

// 6. BANDERAS PARA LA VISTA
$superaMaximo = $total > $maximo;

// 7. VISTA (al final, sin echo suelto)
require __DIR__ . '/vistas/resultado.php';
```

### `funciones.php` (las tres que han salido)

```php
<?php
function escapar($texto)
{
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}

function euros($numero)
{
    return number_format($numero, 2, ',', '.');
}

function calcularMayoria($totalEscanos)
{
    return intdiv($totalEscanos, 2) + 1;
}
```

## 12. Plantilla de la vista

```php
<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><title>Resultado</title></head>
<body>
<h1>Título</h1>

<p>Dato: <?= escapar($dato) ?></p>
<p>Importe: <?= euros($importe) ?> €</p>

<?php if (empty($visibles)): ?>
    <p>La búsqueda no tiene resultados.</p>
<?php else: ?>
    <table border="1">
        <tr><th>Campo 1</th><th>Campo 2</th></tr>
        <?php foreach ($visibles as $fila): ?>
            <tr>
                <td><?= escapar($fila['campo1']) ?></td>
                <td><?= escapar($fila['campo2']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php if ($superaMaximo): ?>
    <p>El pedido supera el máximo.</p>
<?php else: ?>
    <p>El pedido está dentro del máximo.</p>
<?php endif; ?>

<p><a href="formulario.html">Volver al formulario</a></p>
</body></html>
```

## 13. Ejemplo completo: `analizarPacto.php`

```php
<?php
require_once __DIR__ . '/datosEscenarios.php';
require_once __DIR__ . '/funciones.php';

// TODO 1: exigir POST, validar escenario y socios[] (array de 1 a 3 partidos).
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Envía el formulario del pactómetro');
}
$escenario = $_POST['escenario'] ?? null;
$sociosElegidos = $_POST['socios'] ?? [];

if (!is_string($escenario) || !array_key_exists($escenario, $escenarios)) {
    exit('Escenario no válido.');
}
if (!is_array($sociosElegidos)) {
    exit('Socios no válidos.');
}

$partidosEscenario = $escenarios[$escenario]['partidos'];   // partidos y escaños del escenario

// TODO 2: rechazar socios repetidos y nombres ajenos a la lista del escenario.
foreach ($sociosElegidos as $socio) {
    if (!is_string($socio) || !array_key_exists($socio, $partidosEscenario)) {
        exit('Socio no válido.');
    }
}
if (count(array_unique($sociosElegidos)) !== count($sociosElegidos)) {
    exit('Hay socios repetidos.');
}
$cantidadSocios = count($sociosElegidos);
if ($cantidadSocios < 1 || $cantidadSocios > 3) {
    exit('La cantidad de socios debe ser entre 1 y 3');
}

// TODO 3: sumar escaños y calcular mayoría absoluta (mitad + 1).
$escanosPacto = 0;
foreach ($sociosElegidos as $socio) {
    $escanosPacto += $partidosEscenario[$socio];
}
$mayoriaAbsoluta = calcularMayoria($escenarios[$escenario]['escanos']);

// TODO 4: mayoría sí/no, y cuántos sobran o faltan
$hayMayoria = $escanosPacto >= $mayoriaAbsoluta;
$diferencia = $escanosPacto - $mayoriaAbsoluta;   // positivo: sobran; negativo: faltan

// TODO 5: coalición con cada partido que NO está en el pacto
$coaliciones = [];
foreach ($partidosEscenario as $partido => $escanos) {
    if (!in_array($partido, $sociosElegidos, true)) {
        $coaliciones[$partido] = $escanosPacto + $escanos;
    }
}

// TODO 6: el partido que llega a la mayoría aportando menos escaños
$mejorSocio = null;
if (!$hayMayoria) {
    foreach ($coaliciones as $partido => $totalCoalicion) {
        if ($totalCoalicion >= $mayoriaAbsoluta) {
            if ($mejorSocio === null) {
                $mejorSocio = $partido;
            } elseif ($partidosEscenario[$partido] < $partidosEscenario[$mejorSocio]) {
                $mejorSocio = $partido;
            }
        }
    }
}

// TODO 7: construir el informe en vistas/informe.php.
require __DIR__ . '/vistas/informe.php';
```

## 14. Antes de entregar: lista de comprobación

- [ ] ¿Todas las líneas terminan en `;`?
- [ ] ¿Los nombres de variable son los mismos en toda la lógica y la vista (singular y plural)?
- [ ] ¿Los `value` del HTML coinciden con lo que valida el PHP?
- [ ] ¿Cada array se consulta con `in_array` o `array_key_exists` según sea lista o catálogo?
- [ ] ¿Todo valor que llega del usuario se valida antes de usarlo?
- [ ] ¿Los totales se calculan ANTES de recortar con `array_slice`?
- [ ] ¿Está borrado el `echo 'Pendiente...'` y descomentado el `require` de la vista?
- [ ] ¿Probé todos los casos del enunciado, incluidos los de error?