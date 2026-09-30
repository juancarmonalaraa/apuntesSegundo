# 05 · Matrícula y horario semanal

## Objetivo
Recoger varias asignaturas mediante casillas de verificación, consultar sus horarios y calcular la carga horaria semanal utilizando arrays multidimensionales.

## Actividad
1. En `matricula.html`, completa las casillas con `name="asignaturas[]"` y los valores `Ingles`, `DAW`, `DIW`, `DWEC`, `DWES`, `IPE_II`, `Proyecto` y `Optativa`. Envía por `POST` a `procesarMatricula.php`.
2. Estudia `horario.php`, que contiene los días de la semana, los tramos de media hora y el mapa de asignaturas con sus intervalos. **No hace falta inventar un horario nuevo**.
3. En `procesarMatricula.php`, recupera el array recibido y comprueba que se ha seleccionado al menos una asignatura y que todos sus nombres aparecen en los datos del horario.
4. Muestra para cada asignatura elegida sus días y horas. Algunas asignaturas tienen **dos intervalos en un mismo día**: ambos deben mostrarse y sumarse.
5. Calcula la suma de horas semanales de todas las asignaturas matriculadas. Evita contabilizar una misma asignatura más de una vez.

## Ampliación
Dibuja una tabla con los días como columnas y los tramos horarios como filas. Rellena únicamente las celdas correspondientes a asignaturas matriculadas y aplica un color distinto a cada asignatura. Puedes usar las matrices `$diasSemana` y `$horasHorario` ya suministradas.

## Prueba
Prueba con una asignatura, con varias, con `DWES` (tiene dos tramos el martes) y sin marcar ninguna casilla.

**Pistas del temario:** `$_POST`, arrays con `[]`, `foreach` anidados, `strtotime()` o conversión de horas a minutos, condicionales y tablas HTML.
