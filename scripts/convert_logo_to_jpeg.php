<?php
// Simple helper to generate a JPEG logo from an existing PNG so DomPDF can embed it without GD.
// Usage: php scripts/convert_logo_to_jpeg.php [source_png] [target_jpg]

declare(strict_types=1);

$root = dirname(__DIR__);
$srcPng = $argv[1] ?? ($root . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'csar-logo.png');
$dstJpg = $argv[2] ?? ($root . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'csar-logo.jpg');

if (!file_exists($srcPng)) {
    fwrite(STDERR, "PNG introuvable: {$srcPng}\n");
    exit(1);
}
if (!function_exists('imagecreatefrompng')) {
    fwrite(STDERR, "La fonction imagecreatefrompng n'est pas disponible. Activez l'extension GD.\n");
    exit(2);
}

$png = @imagecreatefrompng($srcPng);
if (!$png) {
    fwrite(STDERR, "Impossible de lire le PNG: {$srcPng}\n");
    exit(3);
}

$width = imagesx($png);
$height = imagesy($png);
$jpeg = imagecreatetruecolor($width, $height);

// Remplir le fond en blanc si PNG possède de la transparence
$white = imagecolorallocate($jpeg, 255, 255, 255);
imagefilledrectangle($jpeg, 0, 0, $width, $height, $white);
imagealphablending($jpeg, true);
imagecopy($jpeg, $png, 0, 0, 0, 0, $width, $height);

// Enregistrer en JPEG (qualité 90)
if (!imagejpeg($jpeg, $dstJpg, 90)) {
    fwrite(STDERR, "Echec d'écriture du JPEG: {$dstJpg}\n");
    imagedestroy($png);
    imagedestroy($jpeg);
    exit(4);
}

imagedestroy($png);
imagedestroy($jpeg);

echo "Logo converti en JPEG: {$dstJpg}\n";






