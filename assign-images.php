<?php
// Importa una imagen por categoría y la asigna como imagen destacada a cada producto.
// Ejecutar: wp eval-file assign-images.php --user=admin

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

$map = [
    'Medicamentos'      => __DIR__ . '/prod-img/medicamentos.jpg',
    'Vitaminas'         => __DIR__ . '/prod-img/vitaminas.jpg',
    'Dermocosmética'    => __DIR__ . '/prod-img/dermocosmetica.jpg',
    'Cuidado Personal'  => __DIR__ . '/prod-img/cuidado-personal.jpg',
    'Dispositivos'      => __DIR__ . '/prod-img/dispositivos.jpg',
    'Primeros Auxilios' => __DIR__ . '/prod-img/primeros-auxilios.jpg',
];

// Importar cada imagen una sola vez -> attachment id por categoría
$att = [];
foreach ($map as $cat => $path) {
    if (!file_exists($path)) { WP_CLI::warning("falta $path"); continue; }
    $tmp = ['name' => basename($path), 'tmp_name' => $path];
    // copiar a tmp para que media_handle_sideload no borre el original
    $copy = wp_tempnam(basename($path));
    copy($path, $copy);
    $file = ['name' => basename($path), 'tmp_name' => $copy];
    $id = media_handle_sideload($file, 0, "Foto categoría $cat");
    if (is_wp_error($id)) { WP_CLI::warning("error $cat: " . $id->get_error_message()); continue; }
    $att[$cat] = $id;
    WP_CLI::log("imagen importada: $cat -> attachment $id");
}

// Asignar a productos según su categoría
$q = new WP_Query(['post_type' => 'product', 'posts_per_page' => -1, 'fields' => 'ids']);
$done = 0;
foreach ($q->posts as $pid) {
    $terms = wp_get_object_terms($pid, 'product_cat', ['fields' => 'names']);
    foreach ($terms as $t) {
        if (isset($att[$t])) {
            set_post_thumbnail($pid, $att[$t]);
            $done++;
            break;
        }
    }
}
WP_CLI::success("$done productos con foto asignada.");
