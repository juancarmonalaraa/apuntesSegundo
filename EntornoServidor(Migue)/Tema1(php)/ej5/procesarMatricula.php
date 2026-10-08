<?php
require_once __DIR__ . '/horario.php'; // Datos de días, tramos horarios y asignaturas.

// EJERCICIO 05.
// TODO 1: acepta únicamente POST; recupera y valida asignaturas[].
// TODO 2: para cada asignatura elegida, muestra sus días e intervalos de clase.
// TODO 3: suma la duración semanal de TODOS sus intervalos (hay días con dos tramos).
// TODO 4 (ampliación): genera una tabla de lunes a viernes y colorea las celdas
//    de los tramos que correspondan a las asignaturas seleccionadas.
// En horario.php están los datos iniciales; la lógica debes escribirla aquí.
<?php

require_once "horario.php";

function calcularDuracionHoras($horaInicio, $horaFin){
    return (strtotime($horaFin) - strtotime($horaInicio)) / 3600;
}

function encontrarAsignatura($dia, $horaInicio){
    global $horario;

    $asignaturaEncontrada = "";
    $horaInicio = str_replace(":", "", $horaInicio);

    foreach ($horario as $asignatura => $horarioAsignatura) {

        if (isset($horarioAsignatura[$dia])) {

            $horas = $horarioAsignatura[$dia];

            // Recorremos las horas de dos en dos:
            // índice par = inicio
            // índice impar = fin
            for ($i = 0; $i + 1 < count($horas); $i += 2) {

                $horaInicioAsignatura = str_replace(":", "", $horas[$i]);
                $horaFinAsignatura = str_replace(":", "", $horas[$i + 1]);

                if (
                    $horaInicio >= $horaInicioAsignatura &&
                    $horaInicio < $horaFinAsignatura
                ) {
                    return $asignatura;
                }
            }
        }
    }

    return $asignaturaEncontrada;
}


/* -------------------------
   VALIDACIÓN DEL FORMULARIO
------------------------- */

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Envía el formulario mediante POST.');
}

$asignaturasMatriculadas = $_POST['asignaturas'] ?? [];

if (
    !is_array($asignaturasMatriculadas) ||
    $asignaturasMatriculadas === []
) {
    exit('Selecciona al menos una asignatura.');
}

foreach ($asignaturasMatriculadas as $asignatura) {

    if (
        !is_string($asignatura) ||
        !array_key_exists($asignatura, $horario)
    ) {
        exit('Se ha enviado una asignatura no válida.');
    }
}

$asignaturasMatriculadas = array_unique($asignaturasMatriculadas);


/* -------------------------
   MOSTRAR HORARIOS
------------------------- */

$horasSemanales = 0;

echo "<h1>Tu horario semanal</h1>";

foreach ($asignaturasMatriculadas as $asignatura) {

    echo "<h3>La asignatura $asignatura tiene este horario:</h3>";

    echo "<ul>";

    foreach ($horario[$asignatura] as $dia => $horas) {

        echo "<li>$dia: ";

        // Recorremos todos los tramos de dos en dos
        for ($i = 0; $i + 1 < count($horas); $i += 2) {

            $horaInicio = $horas[$i];
            $horaFin = $horas[$i + 1];

            $horasSemanales += calcularDuracionHoras(
                $horaInicio,
                $horaFin
            );

            echo "$horaInicio a $horaFin";

            // Si todavía quedan más tramos horarios
            if ($i + 2 < count($horas)) {
                echo " y ";
            }
        }

        echo "</li>";
    }

    echo "</ul>";
}

echo "<h3>Te has matriculado de $horasSemanales horas a la semana</h3>";

?>

<style>

    table {
        border-collapse: collapse;
        margin: 1rem 0;
        font-family: sans-serif;
    }

    th,
    td {
        border: 1px solid #8f9cae;
        padding: .5rem;
        text-align: center;
    }

    th {
        background: #203d70;
        color: #fff;
    }

    .DAW {
        background: #d9edff;
    }

    .DIW {
        background: #fce5cd;
    }

    .DWEC {
        background: #e2efda;
    }

    .DWES {
        background: #eadcf8;
    }

    .IPE_II {
        background: #fff2cc;
    }

    .Proyecto {
        background: #f8d8df;
    }

    .Ingles {
        background: #d2f0ed;
    }

    .Optativa {
        background: #e0e4e9;
    }

</style>


<table>

    <thead>

        <tr>
            <th></th>
            <th>Lunes</th>
            <th>Martes</th>
            <th>Miércoles</th>
            <th>Jueves</th>
            <th>Viernes</th>
        </tr>

    </thead>

    <tbody>

        <?php

        foreach ($horasHorario as $hora) {

            $horaInicio = $hora[0];
            $horaFin = $hora[1];

            echo "<tr>";

            echo "<td>$horaInicio - $horaFin</td>";

            foreach ($diasSemana as $dia) {

                $asignaturaQueToca = encontrarAsignatura(
                    $dia,
                    $horaInicio
                );

                if (
                    in_array(
                        $asignaturaQueToca,
                        $asignaturasMatriculadas
                    )
                ) {

                    echo "<td class='$asignaturaQueToca'>
                            $asignaturaQueToca
                          </td>";

                } else {

                    echo "<td></td>";
                }
            }

            echo "</tr>";
        }

        ?>

    </tbody>

</table>

echo 'Pendiente de implementar el ejercicio 05.';
