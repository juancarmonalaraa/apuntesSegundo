# 02 · Ticket de compra: arrays y cálculos

## Objetivo
Generar con PHP un ticket de caja a partir de un **mapa asociativo** de productos/precios y un **array indexado** con las cantidades compradas.

## Actividad
1. Trabaja sobre `EJ1.php`. Puedes utilizar los cinco productos y las cantidades suministrados o modificar los datos para probar tu programa. Ambos arrays han de corresponderse por el orden de los productos.
2. Recorre el mapa de productos con `foreach`. Recupera en cada iteración la cantidad de la posición correspondiente y calcula `cantidad × precio`.
3. Genera filas HTML con cantidad, producto, precio unitario y subtotal. No muestres filas de productos con cantidad 0.
4. Acumula el **total de la compra** y añádelo al final de la tabla.
5. Desglosa la parte correspondiente al **IVA del 21 % ya incluido** en los precios: no debes sumar el IVA otra vez al total final.

## Comprobación
Con las cantidades `[2, 2, 1, 0, 0]`, deben aparecer **tres productos**. Comprueba el total manualmente y vuelve a ejecutar tras cambiar una cantidad.

**Pistas del temario:** arrays asociativos e indexados, `foreach`, acumuladores, operadores aritméticos y `round()`.
