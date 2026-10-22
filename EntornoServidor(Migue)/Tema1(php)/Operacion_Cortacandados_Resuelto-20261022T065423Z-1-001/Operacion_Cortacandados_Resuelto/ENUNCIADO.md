# EXAMEN PRÁCTICO · DWES · UD1: INTRODUCCIÓN A PHP
## OPERACIÓN CORTACANDADOS · Expedientes de bicicletas robadas

**Duración:** 120 minutos  
**Entorno:** PHP y Apache (XAMPP)

### Situación

Una unidad de investigación recibe denuncias por robos de bicicletas. Cada expediente incluye información de la bicicleta y una selección aleatoria de pistas: algunas son fiables y otras no. Hay que programar una aplicación que **genere un expediente para revisarlo** y, después, **analice las decisiones del investigador**.

El recorrido debe tener tres pantallas consecutivas:

`denuncia.html` → `generarExpediente.php` → `analizarExpediente.php`

El formulario inicial y el catálogo `datosRobo.php` se entregan completos. **Los dos archivos PHP que debes desarrollar son `generarExpediente.php` y `analizarExpediente.php`.**

### 1. Generación del expediente — `generarExpediente.php`

`denuncia.html` envía por **POST**: nombre de la persona denunciante, barrio, modelo de bicicleta, antigüedad (de 0 a 5 años) y número de pistas que desea examinar (de 3 a 5).

En `generarExpediente.php`:

1. Recoge los datos del formulario y consulta los arrays suministrados en `datosRobo.php`.
2. Escoge **aleatoriamente** el número solicitado de pistas **sin repetir ninguna**, entre las ocho disponibles.
3. Muestra una nueva página HTML con los datos del expediente, el modelo y el barrio.
4. Para cada pista seleccionada, presenta su descripción y dos opciones mediante botones de tipo `radio`: **Investigar** o **Descartar**. Las opciones de una pista deben compartir su nombre y ser independientes de las demás.
5. Añade un botón **Analizar expediente**, que enviará las decisiones por **POST** a `analizarExpediente.php`. Deben enviarse también los datos del expediente y las claves de **todas las pistas que se han mostrado**, incluso las que no tengan una respuesta seleccionada.

**Importante:** en esta primera pantalla no se debe revelar si una pista es fiable ni el valor de su aportación. No es necesario guardar información en archivos, bases de datos o sesiones.

### 2. Análisis de las decisiones — `analizarExpediente.php`

Este archivo recibe las decisiones del formulario anterior y vuelve a consultar `datosRobo.php`.

- Recorre todas las pistas mostradas, comprueba su fiabilidad en el catálogo y determina cuál habría sido la decisión recomendada: **Investigar** una pista fiable o **Descartar** una pista no fiable.
- Para cada pista, informa de la decisión tomada, la recomendada y si coincide con ella. Si no se ha seleccionado ninguna opción, indica **Sin decidir** y no la cuenta como coincidencia.
- Cuenta cuántas decisiones coinciden con las recomendadas y cuántas pistas se mostraron en total.
- Suma el **valor de investigación** únicamente de las pistas fiables que se hayan marcado como **Investigar**. Las pistas descartadas, las no fiables y las no respondidas no aportan valor.
- Determina la prioridad del expediente según el valor total:

| Valor total | Prioridad |
|---|---|
| De 0 a 2 | BAJA |
| De 3 a 5 | MEDIA |
| 6 o más | ALTA |

- Si una de las pistas investigadas es la del **GPS** y es fiable, muestra el aviso: **«Revisar la ubicación del rastreador GPS»**.

### 3. Valor económico de la bicicleta

Usa el precio de origen de la bicicleta indicado en `datosRobo.php`. Por cada año de antigüedad se descuenta un **10 % del precio original**. Se trata de una depreciación **lineal**, no compuesta.

`descuento = precio original × antigüedad × 0,10`  
`valor actual = precio original − descuento`

En el informe final deben aparecer el precio original, los años, el porcentaje de depreciación, el importe descontado y el valor actual estimado. Las cantidades en euros se mostrarán con dos decimales.

### 4. Informe de investigación

`analizarExpediente.php` generará una página HTML con:

- Nombre de quien denuncia, barrio y modelo de bicicleta.
- Valoración económica detallada.
- Tabla con **cada pista mostrada**, su decisión, la recomendación y el resultado de la comparación.
- Número de decisiones coincidentes respecto del total de pistas mostradas.
- Valor de investigación acumulado, prioridad y posible aviso GPS.
- Enlace para volver a `denuncia.html` y abrir un expediente nuevo.

Incluye en alguno de los dos PHP **dos funciones propias**: una para formatear cantidades en euros y otra para determinar la prioridad. Ambas deben **devolver un valor; no deben imprimir HTML ni texto**.

### Ejemplo de ejecución

**Pantalla 1 — `denuncia.html`:** la persona introduce *Ana*, *Centro*, *bicicleta de carretera*, *2 años* y *3 pistas*. Pulsa **Generar expediente**.

**Pantalla 2 — `generarExpediente.php`:** aparecen **3 pistas aleatorias**. Supongamos que salen una cámara, un GPS y un rumor de chat. La persona marca *Investigar* en cámara y GPS, y *Descartar* en rumor. Pulsa **Analizar expediente**.

**Pantalla 3 — `analizarExpediente.php`:** muestra las tres decisiones, **3 coincidencias de 3**, **7 de valor de investigación**, prioridad **ALTA**, precio original **950,00 €**, descuento **190,00 €** y valor estimado **760,00 €**. También muestra el aviso GPS.

*Como las pistas se eligen al azar, cada ejecución puede generar un expediente diferente.*

### Más casos para comprobar el programa

- **Bicicleta urbana de 5 años:** si aparecen solo rumor, foto y mensaje anónimo y los tres se descartan, el valor de investigación es **0**, la prioridad **BAJA** y el valor actual **120,00 €**.
- **Bicicleta de montaña de 1 año:** si aparecen testigo, candado y foto, y se investiga a los dos primeros y se descarta la fotografía, el valor de investigación es **3**, la prioridad **MEDIA** y el valor actual **495,00 €**.

*Los ejemplos dependen de qué pistas aparezcan en la selección aleatoria.*

### Comprobación final y entrega

Cuando hayas terminado la funcionalidad, revisa la **validación de los datos de entrada**, las situaciones en que falta alguna decisión y la presentación segura de los textos. No se deben aceptar claves de pistas que no existan en el catálogo ni depender únicamente de los controles del navegador. Prueba el programa con diferentes combinaciones de pistas.

Trabaja sobre los archivos proporcionados. No cambies las claves, importes ni criterios de `datosRobo.php`. **No uses CSS, JavaScript, bases de datos ni sesiones.** Entrega la carpeta completa con los dos PHP funcionando.
