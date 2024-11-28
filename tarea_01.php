<?php
include 'header.php'; // Incluye el encabezado

$url = "https://www.fgjcdmx.gob.mx/micrositios/licitaciones";

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

// Crear un DOMXPath para buscar elementos específicos
$xpath = new DOMXPath($dom);

// Buscar todos los contenedores de licitaciones
$licitaciones = $xpath->query("//div[contains(@class, 'Accordion-elements')]");
?>

<div class="container mt-5">
    <h1 class="fst-italic fw-bold mb-4">Licitaciones Encontradas</h1>
    <div class="row">
        <?php if ($licitaciones->length > 0): ?>
            <?php foreach ($licitaciones as $licitacion): 
                $titulo = $xpath->query(".//h2", $licitacion);
                if ($titulo->length > 0): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm border-light">
                            <div class="card-body">
                                <h5 class="card-title text-primary"><?= htmlspecialchars($titulo->item(0)->textContent); ?></h5>
                            </div>
                        </div>
                    </div>
                <?php endif; 
            endforeach; ?>
        <?php else: ?>
            <p class="text-center text-muted">No se encontraron licitaciones.</p>
        <?php endif; ?>
    </div>
</div>
