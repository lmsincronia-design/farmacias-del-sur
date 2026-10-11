<?php
/*
 * Plugin Name: Farmacia Vida Sana - WhatsApp y ajustes
 * Description: Reemplaza "Añadir al carrito" por "Comprar por WhatsApp" y otros ajustes de la tienda.
 */

if (!defined('FV_WA')) {
    define('FV_WA', '56900000000'); // <-- número de WhatsApp de la farmacia (placeholder)
}

function fv_wa_link($product) {
    $name  = $product->get_name();
    $price = html_entity_decode(wp_strip_all_tags(wc_price($product->get_price())));
    $msg   = rawurlencode("Hola! Quiero comprar: {$name} ({$price})");
    return 'https://wa.me/' . FV_WA . '?text=' . $msg;
}

// ---- Loop de tienda: reemplazar botón ----
// WooCommerce registra sus botones después de cargar los mu-plugins; retirarlos
// en wp_loaded evita que aparezcan junto al botón de WhatsApp.
add_action('wp_loaded', function () {
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
});
add_action('woocommerce_after_shop_loop_item', function () {
    global $product;
    if (!$product) return;
    if (!$product->is_in_stock()) {
        echo '<span class="button fv-agotado" style="opacity:.6;pointer-events:none;">Agotado</span>';
        return;
    }
    printf(
        '<a href="%s" target="_blank" rel="noopener" class="button fv-wa-button">Comprar por WhatsApp</a>',
        esc_url(fv_wa_link($product))
    );
}, 10);

// ---- Ficha de producto: reemplazar botón ----
add_action('woocommerce_single_product_summary', function () {
    global $product;
    if (!$product) return;
    if (!$product->is_in_stock()) {
        echo '<p class="stock out-of-stock">Agotado</p>';
        return;
    }
    printf(
        '<a href="%s" target="_blank" rel="noopener" class="button alt fv-wa-button" style="display:inline-block;">Comprar por WhatsApp</a>',
        esc_url(fv_wa_link($product))
    );
}, 30);

// ---- "SALE!" -> "OFERTA" ----
add_filter('woocommerce_sale_flash', function () {
    return '<span class="onsale">OFERTA</span>';
});

// ---- Estilos del botón WhatsApp ----
add_action('wp_head', function () {
    echo '<style>
        .fv-wa-button{background:#12745f !important;border-color:#12745f !important;color:#fff !important;}
        .fv-wa-button:hover{background:#0b5748 !important;border-color:#0b5748 !important;}
        .onsale{background:#e63946 !important;color:#fff !important;}
    </style>';
});
