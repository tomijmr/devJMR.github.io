<?php
// Incluir el autoload de Composer para PhpSpreadsheet
require '../vendor/autoload.php';

// Importar las clases necesarias
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Verificar si se ha enviado un formulario con un archivo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        $fileTmpPath = $_FILES["file"]["tmp_name"];
        
        // Cargar el archivo Excel utilizando PhpSpreadsheet
        $spreadsheet = IOFactory::load($fileTmpPath);
        $sheet = $spreadsheet->getActiveSheet();

        // Conectar a la base de datos
        include("dbconnect.php");

        // Recorrer las filas del archivo Excel y obtener los datos
        $rowCount = 0;
        foreach ($sheet->getRowIterator() as $row) {
            $rowIndex = $row->getRowIndex();
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Permite iterar por celdas vacías también

            $data = [];
            foreach ($cellIterator as $cell) {
                $data[] = $cell->getValue();
            }

            // Ignorar la primera fila (encabezados)
            if ($rowIndex > 1) {
                $id_product = $data[0]; // Código del producto
                $desc_product = $data[1]; // Descripción del producto
                $cost_product = $data[2]; // Costo del producto

                // Verificar si los datos están completos
                if (!empty($id_product) && !empty($desc_product) && !empty($cost_product)) {
                    // Insertar los datos en la base de datos
                    $sql_insert = "INSERT INTO product (id_product, desc_product, cost_product) 
                                   VALUES ('$id_product', '$desc_product', '$cost_product')";
                    if ($conn->query($sql_insert) === TRUE) {
                        $rowCount++;
                    } else {
                        echo "Error al insertar el producto en la fila $rowIndex: " . $conn->error;
                    }
                }
            }
        }

        // Cerrar la conexión a la base de datos
        $conn->close();

        // Mostrar mensaje de éxito
        echo "$rowCount productos se cargaron correctamente.";
    } else {
        echo "Error al cargar el archivo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta Masiva de Productos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #800000; /* Bordó estilo poncho salteño */
        }
        form {
            margin-top: 20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
        }
        input[type="file"] {
            margin-bottom: 20px;
        }
        input[type="submit"] {
            background-color: #800000;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #a52a2a; /* Tono más oscuro de bordó */
        }
        a{
            background-color: #800000;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>
<body>

    <h1>Alta Masiva de Productos</h1>
    <a href="index.php">Atras</a>
    <form action="alta_masiva.php" method="POST" enctype="multipart/form-data">
        <label for="file">Selecciona el archivo de Excel (.xls):</label>
        <input type="file" name="file" id="file" accept=".xls, .xlsx" required>

        <input type="submit" value="Cargar Productos">
    </form>

</body>
</html>
