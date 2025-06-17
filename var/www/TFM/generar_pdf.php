<?php
require_once __DIR__ . '/vendor/autoload.php';
use Dompdf\Dompdf;

set_time_limit(300);
date_default_timezone_set('Europe/Madrid');

function cargar_json_desde_directorio($directorio) {
    $resultados = [];
    foreach (glob("$directorio/*.json") as $archivo) {
        $contenido = file_get_contents($archivo);
        foreach (explode(PHP_EOL, $contenido) as $linea) {
            $linea = trim($linea);
            if ($linea) {
                $datos = json_decode($linea, true);
                if ($datos) $resultados[] = $datos;
            }
        }
    }
    return $resultados;
}

$timestamp = date("Ymd_His");
$fecha = date("Y-m-d H:i:s");

$hosts = cargar_json_desde_directorio(__DIR__ . "/logs/nmap");
$puertos = cargar_json_desde_directorio(__DIR__ . "/logs/nmap");
$vulnerabilidades = cargar_json_desde_directorio(__DIR__ . "/logs/zap");

$html = "<h1>Informe de Seguridad - Pablo Martínez Bernal</h1>";
$html .= "<p><strong>Fecha:</strong> $fecha</p>";

$html .= "<h2>1. Hosts activos</h2><ul>";
$ips_unicas = [];
foreach ($hosts as $h) {
    if (isset($h['ip']) && !in_array($h['ip'], $ips_unicas)) {
        $ips_unicas[] = $h['ip'];
        $html .= "<li>" . htmlspecialchars($h['ip']) . "</li>";
    }
}
$html .= "</ul>";

$html .= "<h2>2. Puertos abiertos</h2>";
$puertos_por_ip = [];
foreach ($puertos as $p) {
    if (isset($p['ip'], $p['puerto'])) {
        $puertos_por_ip[$p['ip']][] = $p;
    }
}
foreach ($puertos_por_ip as $ip => $puertos_ip) {
    $html .= "<h3>$ip</h3><table border='1' cellspacing='0' cellpadding='4'><tr><th>Puerto</th><th>Servicio</th><th>Versión</th></tr>";
    foreach ($puertos_ip as $p) {
        $html .= "<tr><td>" . htmlspecialchars($p['puerto']) . "</td><td>" . htmlspecialchars($p['servicio']) . "</td><td>" . htmlspecialchars($p['version']) . "</td></tr>";
    }
    $html .= "</table>";
}

$html .= "<h2>3. Vulnerabilidades detectadas</h2><table border='1' cellspacing='0' cellpadding='4'><tr><th>URL</th><th>Alerta</th><th>Riesgo</th><th>Solución</th></tr>";
foreach ($vulnerabilidades as $v) {
    $url = htmlspecialchars($v['url'] ?? '');
    $alert = htmlspecialchars($v['alert'] ?? '');
    $risk = htmlspecialchars($v['risk'] ?? '');
    $solution = htmlspecialchars($v['solution'] ?? '');
    $html .= "<tr><td>$url</td><td>$alert</td><td>$risk</td><td>$solution</td></tr>";
}
$html .= "</table>";

$dompdf = new Dompdf();
$dompdf->loadHtml("<html><body style='font-family:Arial;'>$html</body></html>");
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("informe_seguridad_$timestamp.pdf", ["Attachment" => true]);
?>
