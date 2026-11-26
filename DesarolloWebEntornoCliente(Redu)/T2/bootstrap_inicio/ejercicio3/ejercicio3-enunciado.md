# Ejercicio 3 · Landing completa "Biciclo" con Tailwind CSS

Maqueta la landing de las capturas `ejercicio3-escritorio.png` (1280 px) y `ejercicio3-movil.png` (390 px) usando **solo clases de Tailwind CSS** (sin CSS propio).

## Secciones obligatorias
1. Navbar fija (`sticky`) con menú de hamburguesa en móvil (sin JavaScript: checkbox + `peer`)
2. Hero: texto, dos botones e imagen
3. Servicios: 6 tarjetas (1 / 2 / 3 columnas según pantalla)
4. Cómo funciona: 3 pasos numerados
5. Bloque destacado: imagen + texto + lista con checks
6. Banner de llamada a la acción
7. Contacto: datos + formulario (input, email, select, textarea, checkbox)
8. Footer de 4 columnas

## Recursos (carpeta `assets/`)
Todas las imágenes son SVG y se usan con `<img>`. Los iconos de servicios van sobre un cuadro `bg-blue-50 rounded-xl`.

| Archivo | Uso |
|---|---|
| `logo-claro.svg` | Logo para fondos oscuros (navbar y footer) |
| `logo-oscuro.svg` | Logo para fondos claros (opcional) |
| `favicon.svg` | Icono de la pestaña (`<link rel="icon">`) |
| `hero-bici.svg` | Imagen del hero |
| `taller.svg` | Imagen del bloque destacado |
| `icono-reparacion.svg`, `icono-revision.svg`, `icono-medida.svg`, `icono-electrica.svg`, `icono-accesorios.svg`, `icono-alquiler.svg` | Iconos de las 6 tarjetas de servicios |
| `icono-check.svg` | Check de la lista del bloque destacado |
| `icono-menu.svg` | Botón de hamburguesa del móvil |

## Requisitos
- Responsive con prefijos `sm:`, `md:` y `lg:` (móvil primero)
- Fuente DM Sans (Google Fonts) y paleta `slate`, `blue-700` y `yellow-300`
- Estados `hover:` y `focus:` en enlaces, botones y campos
- HTML semántico: `header`, `nav`, `main`, `section`, `footer`, `label` con `for`
- Sin desbordamiento horizontal en 390 px

## Entrega
`index.html` (con la carpeta `assets/`) + dos capturas propias (`escritorio` y `movil`, página completa).

## Criterios de evaluación (10 puntos)
| Criterio | Puntos |
|---|---|
| Fidelidad al diseño en escritorio | 2 |
| Fidelidad al diseño en móvil | 2 |
| Responsive correcto (grids, menú, tamaños) | 2 |
| Formulario y accesibilidad | 1,5 |
| Hover / focus / transiciones | 1 |
| HTML semántico y limpio | 1,5 |
