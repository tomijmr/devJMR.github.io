<?php
include 'dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;
    $id_deposito_origen = isset($_POST['id_deposito_origen']) ? intval($_POST['id_deposito_origen']) : 0;
    $id_deposito_destino = isset($_POST['id_deposito_destino']) ? intval($_POST['id_deposito_destino']) : 0;
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 0;

    echo '<style>
        .message {
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }
        .success { background-color: #d4edda; color: #155724; border: 2px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 2px solid #f5c6cb; }
        .button-container { text-align: center; margin-top: 20px; }
        .button {
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            display: inline-block;
            margin: 5px;
        }
        .btn-success { background-color: #28a745; color: white; border: 2px solid #218838; }
        .btn-error { background-color: #dc3545; color: white; border: 2px solid #c82333; }
        .button:hover { opacity: 0.8; }
    </style>';

    if ($id_producto > 0 && $id_deposito_origen > 0 && $id_deposito_destino > 0 && $cantidad > 0) {
        // Iniciar transacción para evitar inconsistencias
        mysqli_begin_transaction($conn);

        // Verificar stock en el depósito de origen
        $query_stock = "SELECT cantidad FROM stock WHERE id_producto = ? AND id_deposito = ?";
        $stmt_stock = mysqli_prepare($conn, $query_stock);
        mysqli_stmt_bind_param($stmt_stock, "ii", $id_producto, $id_deposito_origen);
        mysqli_stmt_execute($stmt_stock);
        $result_stock = mysqli_stmt_get_result($stmt_stock);
        $row = mysqli_fetch_assoc($result_stock);
        mysqli_stmt_close($stmt_stock);

        if ($row && $row['cantidad'] >= $cantidad) {
            // Restar stock del depósito de origen
            $query_restar = "UPDATE stock SET cantidad = cantidad - ? WHERE id_producto = ? AND id_deposito = ?";
            $stmt_restar = mysqli_prepare($conn, $query_restar);
            mysqli_stmt_bind_param($stmt_restar, "iii", $cantidad, $id_producto, $id_deposito_origen);
            $restar_result = mysqli_stmt_execute($stmt_restar);
            mysqli_stmt_close($stmt_restar);

            // Sumar stock al depósito de destino
            $query_sumar = "INSERT INTO stock (id_producto, id_deposito, cantidad)
                            VALUES (?, ?, ?)
                            ON DUPLICATE KEY UPDATE cantidad = cantidad + ?";
            $stmt_sumar = mysqli_prepare($conn, $query_sumar);
            mysqli_stmt_bind_param($stmt_sumar, "iiii", $id_producto, $id_deposito_destino, $cantidad, $cantidad);
            $sumar_result = mysqli_stmt_execute($stmt_sumar);
            mysqli_stmt_close($stmt_sumar);

            // Guardar el movimiento en la tabla movimientos_stock
            $query_movimiento = "INSERT INTO movimientos_stock (id_producto, id_deposito_origen, id_deposito_destino, cantidad, fecha) 
                                VALUES (?, ?, ?, ?, NOW())";
            $stmt_movimiento = mysqli_prepare($conn, $query_movimiento);
            mysqli_stmt_bind_param($stmt_movimiento, "iiii", $id_producto, $id_deposito_origen, $id_deposito_destino, $cantidad);
            $movimiento_result = mysqli_stmt_execute($stmt_movimiento);
            mysqli_stmt_close($stmt_movimiento);

            if ($restar_result && $sumar_result && $movimiento_result) {
                mysqli_commit($conn);
                echo "<div class='message success'>✅ Movimiento realizado con éxito.</div>";
                echo "<div class='button-container'><a class='button btn-success' href='historial_movimientos.php'>Ver Movimientos</a></div>";
            } else {
                mysqli_rollback($conn);
                echo "<div class='message error'>❌ Error al realizar el movimiento.</div>";
                echo "<div class='button-container'><a class='button btn-error' href='mover_stock.php'>Atrás</a></div>";
            }
        } else {
            echo "<div class='message error'>⚠️ Error: Stock insuficiente en el depósito de origen.</div>";
            echo "<div class='button-container'><a class='button btn-error' href='mover_stock.php'>Atrás</a></div>";
        }
    } else {
        echo "<div class='message error'>❌ Error: Datos inválidos.</div>";
        echo "<div class='button-container'><a class='button btn-error' href='mover_stock.php'>Atrás</a></div>";
    }
} else {
    echo "<div class='message error'>🚫 Acceso no permitido.</div>";
    echo "<div class='button-container'><a class='button btn-error' href='mover_stock.php'>Atrás</a></div>";
}
?>