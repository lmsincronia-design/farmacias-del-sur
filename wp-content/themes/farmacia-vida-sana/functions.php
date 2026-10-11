<?php
defined('ABSPATH') || exit;

add_filter('option_blogname', fn () => 'Farmacias del Sur');
add_filter('option_blogdescription', fn () => 'Buenos precios, más atención');

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('storefront-style', get_template_directory_uri() . '/style.css', [], wp_get_theme('storefront')->get('Version'));
    wp_enqueue_style('farmacia-vida-sana', get_stylesheet_uri(), ['storefront-style'], wp_get_theme()->get('Version'));
    wp_enqueue_script('farmacia-vida-sana', get_stylesheet_directory_uri() . '/theme.js', [], wp_get_theme()->get('Version'), true);
}, 20);

add_action('storefront_before_header', function () {
    echo '<div class="fv-announcement">Tres locales para cuidarte <span>•</span> Santiago <span>•</span> Cauquenes <span>•</span> Quirihue</div>';
});

add_action('storefront_before_content', function () {
    if (!function_exists('wc_get_products')) {
        return;
    }
    $products = wc_get_products([
        'status'  => 'publish',
        'limit'   => 10,
        'orderby' => 'date',
        'order'   => 'DESC',
    ]);
    if (!$products) {
        return;
    }
    echo '<div class="fv-promo-ribbon"><div class="fv-product-strip" data-fv-product-strip>';
    echo '<button class="fv-product-strip__arrow fv-product-strip__arrow--prev" type="button" aria-label="Productos anteriores">‹</button>';
    echo '<div class="fv-product-strip__viewport"><div class="fv-product-strip__track">';
    foreach ($products as $index => $product) {
        $classes = ['fv-product-strip__card', 'fv-product-strip__card--' . (($index % 4) + 1)];
        printf(
            '<a class="%1$s" href="%2$s"><span class="fv-product-strip__image">%3$s</span><span class="fv-product-strip__copy"><small>Producto destacado</small><strong>%4$s</strong><span class="fv-product-strip__price">%5$s</span></span></a>',
            esc_attr(implode(' ', $classes)),
            esc_url($product->get_permalink()),
            wp_kses_post($product->get_image('woocommerce_thumbnail')),
            esc_html($product->get_name()),
            wp_kses_post($product->get_price_html())
        );
    }
    echo '</div></div>';
    echo '<button class="fv-product-strip__arrow fv-product-strip__arrow--next" type="button" aria-label="Productos siguientes">›</button>';
    echo '</div></div>';
}, 5);

add_filter('storefront_credit_link', '__return_false');

add_filter('wp_nav_menu_items', function ($items, $args) {
    if (!empty($args->theme_location) && $args->theme_location === 'primary') {
        $items = str_replace('>Shop<', '>Catálogo<', $items);
        $items = str_replace('>Cart<', '>Carrito<', $items);
        $items = str_replace('>Checkout<', '>Finalizar compra<', $items);
        $items = str_replace('>My account<', '>Mi cuenta<', $items);
        if (!str_contains($items, '#locales')) {
            $items .= '<li class="menu-item menu-item-locales"><a href="' . esc_url(home_url('/#locales')) . '">Locales</a></li>';
        }
    }
    return $items;
}, 20, 2);

add_filter('wp_nav_menu_objects', function ($items) {
    $labels = [
        'Shop'       => 'Catálogo',
        'Cart'       => 'Carrito',
        'Checkout'   => 'Finalizar compra',
        'My account' => 'Mi cuenta',
    ];
    foreach ($items as $item) {
        if (isset($labels[$item->title])) {
            $item->title = $labels[$item->title];
        }
    }
    return $items;
}, 20);

add_filter('gettext', function ($translated, $text, $domain) {
    if ($domain !== 'default' && $domain !== 'woocommerce' && $domain !== 'storefront') {
        return $translated;
    }
    $labels = [
        'Shop'       => 'Catálogo',
        'Cart'       => 'Carrito',
        'Checkout'   => 'Finalizar compra',
        'My account' => 'Mi cuenta',
    ];
    return $labels[$text] ?? $translated;
}, 20, 3);

add_filter('the_title', function ($title, $post_id) {
    if (!$post_id || get_post_type($post_id) !== 'page') {
        return $title;
    }
    $labels = [
        'shop'       => 'Catálogo',
        'cart'       => 'Carrito',
        'checkout'   => 'Finalizar compra',
        'my-account' => 'Mi cuenta',
    ];
    $slug = get_post_field('post_name', $post_id);
    return $labels[$slug] ?? $title;
}, 20, 2);

add_filter('wp_list_pages_excludes', function ($exclude) {
    $sample = get_page_by_path('pagina-de-ejemplo');
    if ($sample) {
        $exclude[] = $sample->ID;
    }
    return array_unique($exclude);
});

// Storefront imprime dos menús idénticos cuando no hay menús asignados.
// El menú principal ya es responsive, por lo que evitamos el segundo fallback.
add_filter('wp_nav_menu_args', function ($args) {
    if (($args['theme_location'] ?? '') === 'handheld' && !has_nav_menu('handheld')) {
        $args['fallback_cb'] = false;
    }
    return $args;
});

function fv_category_icon($slug) {
    $icons = [
        'cuidado-personal' => '✦',
        'dermocosmetica'   => '◌',
        'dispositivos'     => '＋',
    ];
    return $icons[$slug] ?? '✚';
}

function fv_contact_details() {
    return [
        'whatsapp_raw'     => defined('FV_WA') ? FV_WA : '56900000000',
        'whatsapp_display' => '+56 9 0000 0000',
        'phone_raw'        => '56220000000',
        'phone_display'    => '+56 2 2000 0000',
        'instagram'        => 'farmaciasdelsur.cl',
        'instagram_label'  => '@farmaciasdelsur.cl',
        'email'            => 'contacto@farmaciasdelsur.cl',
        'hours'            => 'Lun–Vie 09:00–19:00 · Sáb 10:00–14:00',
    ];
}

add_action('storefront_footer', function () {
    $contact = fv_contact_details();
    ?>
    <div class="fv-footer-grid">
      <div class="fv-footer-brand">
        <span class="fv-footer-mark"><img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/marca-del-sur.webp'); ?>" alt=""></span>
        <div><strong>Farmacias del Sur</strong><p>Tres locales, buenos precios y atención cercana.</p></div>
      </div>
      <div><h3>Contáctanos</h3><a href="tel:<?php echo esc_attr($contact['phone_raw']); ?>"><?php echo esc_html($contact['phone_display']); ?></a><a href="mailto:<?php echo esc_attr($contact['email']); ?>"><?php echo esc_html($contact['email']); ?></a></div>
      <div><h3>Nuestros locales</h3><a href="<?php echo esc_url(home_url('/#locales')); ?>">Santiago · Alameda 1910</a><a href="<?php echo esc_url(home_url('/#locales')); ?>">Cauquenes · Alvear 285</a><a href="<?php echo esc_url(home_url('/#locales')); ?>">Quirihue · Prat 470</a></div>
      <div><h3>Síguenos</h3><a href="https://instagram.com/<?php echo esc_attr($contact['instagram']); ?>" target="_blank" rel="noopener"><?php echo esc_html($contact['instagram_label']); ?></a><a href="https://wa.me/<?php echo esc_attr($contact['whatsapp_raw']); ?>" target="_blank" rel="noopener">WhatsApp directo</a></div>
    </div>
    <?php
}, 5);

add_action('wp_footer', function () {
    $contact = fv_contact_details();
    ?>
    <a class="fv-whatsapp-float" href="https://wa.me/<?php echo esc_attr($contact['whatsapp_raw']); ?>" target="_blank" rel="noopener" aria-label="Comprar por WhatsApp">
      <span aria-hidden="true">◉</span><strong>WhatsApp</strong>
    </a>
    <?php
});
