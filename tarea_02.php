<?php
include 'header.php'; // Incluye el header compartido

// URL de la página a scrapear
$url = "https://www.connexisnetwork.com/laboratorio/comprasdominicana.htm";

// Inicializa cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$html = curl_exec($ch);
curl_close($ch);

// Cargar el HTML en DOMDocument
libxml_use_internal_errors(true); // Ignorar errores de HTML mal formado
$dom = new DOMDocument();
$dom->loadHTML($html);
libxml_clear_errors();

// Crear un DOMXPath
$xpath = new DOMXPath($dom);

// Buscar todas las filas de la tabla
$rows = $xpath->query("//tr[contains(@class, 'gridLineDark')]");

if ($rows->length > 0) {
    ?>
    <div class="container mt-5">
        <h1 class="text-center">Datos Extraídos de la Tabla</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">

                <thead>
                    <tr>
                        <th>Autoridad</th>
                        <th>Identificador</th>
                        <th>Descripción</th>
                        <th>Fecha de Publicación</th>
                        <th>Fecha Límite</th>
                        <th>Precio Base</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Iterar sobre las filas
                    foreach ($rows as $row) {
                        echo "<tr>";
                        
                        // Extraer la información de cada columna
                        $autoridad = $xpath->query(".//td[contains(@id, 'thAuthorityNameCol')]/span", $row)->item(0);
                        $identificador = $xpath->query(".//td[contains(@id, 'thUniqueIdentifierCol')]/span", $row)->item(0);
                        $descripcion = $xpath->query(".//td[contains(@id, 'thDescriptionCol')]/span", $row)->item(0);
                        $fechaPublicacion = $xpath->query(".//td[contains(@id, 'thOfficialPublishDateCol')]/div/span", $row)->item(0);
                        $fechaLimite = $xpath->query(".//td[contains(@id, 'thDeadlineCol')]/div/span", $row)->item(0);
                        $precioBase = $xpath->query(".//td[contains(@id, 'thBasePriceColElements')]/div/span", $row)->item(0);
                        $estado = $xpath->query(".//td[contains(@id, 'thContractNoticeStateCol')]/span", $row)->item(0);
    
                        // Imprimir cada celda
                        echo "<td>" . ($autoridad ? htmlspecialchars(trim($autoridad->textContent)) : 'N/A') . "</td>";
                        echo "<td>" . ($identificador ? htmlspecialchars(trim($identificador->textContent)) : 'N/A') . "</td>";
                        echo "<td>" . ($descripcion ? htmlspecialchars(trim($descripcion->textContent)) : 'N/A') . "</td>";
                        echo "<td>" . ($fechaPublicacion ? htmlspecialchars(trim($fechaPublicacion->textContent)) : 'N/A') . "</td>";
                        echo "<td>" . ($fechaLimite ? htmlspecialchars(trim($fechaLimite->textContent)) : 'N/A') . "</td>";
                        echo "<td>" . ($precioBase ? htmlspecialchars(trim($precioBase->textContent)) : 'N/A') . "</td>";
                        echo "<td>" . ($estado ? htmlspecialchars(trim($estado->textContent)) : 'N/A') . "</td>";
    
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>

        </div>
    </div>
    <?php
} else {
    echo "<div class='container mt-5'><p class='text-center text-danger'>No se encontraron filas en la tabla.</p></div>";
}
?>
</body>
</html>
