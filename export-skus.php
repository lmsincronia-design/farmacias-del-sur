<?php
// Exporta la lista de SKUs para Golan. Ejecutar: wp eval-file export-skus.php --user=admin
$q = new WP_Query(["post_type"=>"product","posts_per_page"=>-1,"orderby"=>"title","order"=>"ASC"]);
$rows = [["SKU (reemplazar por ID Golan)","Nombre","Categoria","Precio habitual","Precio oferta","Stock"]];
while ($q->have_posts()) {
    $q->the_post();
    $p = wc_get_product(get_the_ID());
    $cats = wp_get_object_terms(get_the_ID(), "product_cat", ["fields" => "names"]);
    $rows[] = [$p->get_sku(), $p->get_name(), implode("/", $cats), $p->get_regular_price(), $p->get_sale_price(), $p->get_stock_quantity()];
}
wp_reset_postdata();

$out = dirname(__FILE__) . "/skus-golan.csv";
$fh = fopen($out, "w");
fwrite($fh, "\xEF\xBB\xBF"); // BOM UTF-8 para acentos en Excel
foreach ($rows as $r) { fputcsv($fh, $r); }
fclose($fh);
WP_CLI::success((count($rows) - 1) . " productos exportados a skus-golan.csv");
