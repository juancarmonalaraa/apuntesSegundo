<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mi Coviran</title>
</head>

<body>

    <table border="1">
        <thead>
            <tr>
                <th>Cantidad</th>
                <th>Producto</th>
                <th>Precio ud.</th>
                <th>Subtotal</th>
            </tr>
        </thead>

        <?php
        $productos = ["Maritoñi" => 2.5, "Puleva de fresa" => 1.75, "Pipas granaínas" => 0.75, "Alhambra roja" => 1.25, "Salailla" => 0.5];

        $cantidadesCompradas = [2, 2, 1, 0, 0];

        $i = 0;
        $total = 0;

        foreach ($productos as $producto => $precio) {
            $cantidad = $cantidadesCompradas[$i];
            if ($cantidad > 0) {
                $subtotal = $cantidad * $precio;
                $total += $subtotal;

                echo "<tr><td>$cantidad</td><td>$producto</td><td>$precio</td><td>$subtotal</td></tr>";
            }

            $i++;
        }

        $IVA = round($total - ($total / 1.21), 2);

        echo "<tr><td colspan=\"3\">TOTAL</td><td>$total</td></tr>";
        echo "<tr><td colspan=\"3\">IVA</td><td>$IVA</td></tr>";

        ?>

    </table>

</body>

</html>