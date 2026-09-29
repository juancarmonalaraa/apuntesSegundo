<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Matrícula</title></head>
<body>
    <h1>Selecciona las asignaturas</h1>
    <form action="procesarMatricula.php" method="POST">
        <label><input type="checkbox" name="asignaturas[]" value="Ingles"> Inglés</label>
        <label><input type="checkbox" name="asignaturas[]" value="DAW"> DAW</label>
        <!-- TODO: añade casillas para DIW, DWEC, DWES, IPE_II, Proyecto y Optativa. -->
        <br><button type="submit">Ver matrícula y horario</button>
    </form>
</body>
</html>
