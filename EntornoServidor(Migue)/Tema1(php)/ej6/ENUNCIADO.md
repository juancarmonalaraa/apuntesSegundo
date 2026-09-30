# 06 · Configurador de coches y descuentos

## Objetivo
Desarrollar un configurador que valide las opciones de un vehículo, calcule su precio y desglose el descuento y el IVA.

## Datos de partida
`componentes.php` ya contiene el mapa con las **opciones y precios sin IVA** de modelos (Compacto, Sedán, SUV), motores (Gasolina 1.6L, Diesel 2.0L, Eléctrico 75 kWh), colores (Blanco, Negro, Azul Metálico, Rojo), llantas (16" Aleación, 18" Aleación, 20" Deportivo), equipamientos (Básico, Confort, Premium), ocho accesorios opcionales y los códigos de descuento. Utiliza esos datos sin cambiarlos.

## Actividad
1. Completa `formularioConcesionario.html` con cinco grupos de **radio obligatorios**: `Modelo`, `Motor`, `Color`, `Llantas` y `Equipamiento`. Cada valor HTML debe coincidir con una clave del mapa correspondiente de `componentes.php`.
2. Añade los **ocho accesorios** como checkbox con `name="Accesorios[]"`. Añade la cantidad de vehículos (`name="cantidad"`, entero de 1 a 5) y el código opcional (`name="codigo_descuento"`). Envía por POST a `procesarConcesionario.php`.
3. En PHP, comprueba que las cinco selecciones obligatorias son válidas; admite que no se elija ningún accesorio y evita contabilizar duplicados. Valida la cantidad y no aceptes opciones que no aparezcan en el mapa inicial.
4. Suma el precio de todos los componentes y accesorios para calcular el **precio por vehículo sin IVA**. Multiplica por el número de vehículos.
5. Si se ha introducido un código válido, aplica el porcentaje indicado en el mapa. Si el código no existe, informa al usuario y continúa sin descuento.
6. Sobre la **base resultante después del descuento**, calcula el IVA (21 %) y el total con IVA. Muestra un desglose claro: componentes, accesorios, unidades, precio unitario, base inicial, descuento, base tras descuento, IVA y total.

## Prueba
Prueba una compra sin accesorios ni descuento; otra con varios accesorios y código válido; otra con código incorrecto y otra enviando un formulario con un grupo obligatorio ausente.

**Pistas del temario:** `require_once`, arrays asociativos, checkbox, validaciones, porcentajes, `foreach` y salida HTML segura.
