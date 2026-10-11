<?php
defined('ABSPATH') || exit;
get_header();

$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/');
$contact  = function_exists('fv_contact_details') ? fv_contact_details() : [];
$wa_url   = 'https://wa.me/' . ($contact['whatsapp_raw'] ?? (defined('FV_WA') ? FV_WA : '56900000000'));
$sale_count = function_exists('wc_get_product_ids_on_sale') ? count(wc_get_product_ids_on_sale()) : 0;
$featured_products = function_exists('wc_get_products') ? wc_get_products([
    'status'   => 'publish',
    'limit'    => 1,
    'include'  => $sale_count ? array_slice(wc_get_product_ids_on_sale(), 0, 1) : [],
    'orderby'  => 'date',
    'order'    => 'DESC',
]) : [];
$featured_product = $featured_products ? reset($featured_products) : null;
?>

<main id="primary" class="site-main fv-home">
  <div class="fv-container">
    <section id="locales" class="fv-section fv-locations fv-reveal">
      <div class="fv-locations__head">
        <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/logo-farmacias-del-sur.webp'); ?>" alt="Farmacias del Sur">
        <div><span class="fv-kicker">Estamos cerca de ti</span><h2>Conoce nuestros locales</h2><p>Tres farmacias, tres ciudades y la misma atención de confianza.</p></div>
      </div>
      <div class="fv-locations__grid">
        <article class="fv-location fv-location--sur">
          <div class="fv-location__photo"><img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/local-del-sur.webp'); ?>" alt="Fachada de Farmacias del Sur en Santiago" loading="eager"><span>Casa matriz</span></div>
          <div class="fv-location__body"><small>Santiago</small><h3>Farmacias del Sur</h3><p>Av. Libertador Bernardo O’Higgins 1910</p><a href="https://www.google.com/maps/search/?api=1&amp;query=Av.%20Libertador%20Bernardo%20O%27Higgins%201910%2C%20Santiago%2C%20Chile" target="_blank" rel="noopener">Cómo llegar <span>↗</span></a></div>
        </article>
        <article class="fv-location fv-location--estacion">
          <div class="fv-location__photo"><img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/local-estacion.webp'); ?>" alt="Fachada de Farmacia Estación en Cauquenes" loading="eager"><span>Cauquenes</span></div>
          <div class="fv-location__body"><small>Cauquenes</small><h3>Farmacia Estación</h3><p>Av. Monseñor Enrique Alvear 285</p><a href="https://www.google.com/maps/search/?api=1&amp;query=Av.%20Monse%C3%B1or%20Enrique%20Alvear%20285%2C%20Cauquenes%2C%20Chile" target="_blank" rel="noopener">Cómo llegar <span>↗</span></a></div>
        </article>
        <article class="fv-location fv-location--quirihue">
          <div class="fv-location__photo"><img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/local-quirihue.webp'); ?>" alt="Fachada de Farmacia Quirihue" loading="eager"><span>Quirihue</span></div>
          <div class="fv-location__body"><small>Quirihue</small><h3>Farmacia Quirihue</h3><p>Av. Prat 470</p><a href="https://www.google.com/maps/search/?api=1&amp;query=Av.%20Prat%20470%2C%20Quirihue%2C%20Chile" target="_blank" rel="noopener">Cómo llegar <span>↗</span></a></div>
        </article>
      </div>
    </section>

    <section class="fv-hero">
      <div class="fv-hero__content">
        <span class="fv-eyebrow">Buenos precios, más atención</span>
        <h1>Tu salud, siempre <em>cerca.</em></h1>
        <p class="fv-hero__lead">Medicamentos, vitaminas y cuidado personal con atención cercana en Santiago, Cauquenes y Quirihue.</p>
        <div class="fv-actions">
          <a class="fv-button fv-button--primary" href="<?php echo esc_url($wa_url); ?>" target="_blank" rel="noopener">Comprar por WhatsApp →</a>
          <a class="fv-button fv-button--ghost" href="<?php echo esc_url($shop_url); ?>">Explorar catálogo</a>
        </div>
      </div>
      <div class="fv-hero__visual" aria-hidden="true">
        <div class="fv-orbit">
          <div class="fv-orbit__inner">
            <span class="fv-orbit__icon">✚</span>
            <strong>Salud cotidiana</strong>
            <small>Todo lo que necesitas, en un solo lugar.</small>
          </div>
        </div>
      </div>
    </section>

    <section class="fv-trust" aria-label="Beneficios">
      <div class="fv-trust__item"><span class="fv-trust__icon">⚡</span><div><strong>Despacho rápido</strong><small>A todo Chile</small></div></div>
      <div class="fv-trust__item"><span class="fv-trust__icon">◉</span><div><strong>Atención directa</strong><small>Por WhatsApp</small></div></div>
      <div class="fv-trust__item"><span class="fv-trust__icon">✓</span><div><strong>Stock real</strong><small>Actualizado al día</small></div></div>
    </section>

    <div class="fv-feature-zone fv-reveal">
      <section class="fv-showcase" aria-label="Promociones destacadas" data-fv-slider>
        <div class="fv-showcase__track">
          <article class="fv-showcase__slide is-active fv-showcase__slide--botanical" aria-hidden="false">
            <div><span>Bienestar de temporada</span><h2>Cuida tu salud todos los días</h2><p>Vitaminas, autocuidado y productos esenciales.</p><a href="<?php echo esc_url($shop_url); ?>">Ver productos →</a></div>
          </article>
          <article class="fv-showcase__slide fv-showcase__slide--advice" aria-hidden="true">
            <div><span>Atención cercana</span><h2>Te ayudamos a elegir mejor</h2><p>Conversa con nosotros y encuentra lo que necesitas.</p><a href="<?php echo esc_url($wa_url); ?>" target="_blank" rel="noopener">Hablar por WhatsApp →</a></div>
          </article>
          <article class="fv-showcase__slide fv-showcase__slide--care" aria-hidden="true">
            <div><span>Farmacia online</span><h2>Compra simple y segura</h2><p>Stock actualizado y despacho a todo Chile.</p><a href="<?php echo esc_url($shop_url); ?>">Explorar catálogo →</a></div>
          </article>
        </div>
        <button class="fv-showcase__arrow fv-showcase__arrow--prev" type="button" aria-label="Promoción anterior">‹</button>
        <button class="fv-showcase__arrow fv-showcase__arrow--next" type="button" aria-label="Promoción siguiente">›</button>
        <div class="fv-showcase__dots" aria-label="Elegir promoción">
          <button class="is-active" type="button" aria-label="Promoción 1" aria-current="true"></button>
          <button type="button" aria-label="Promoción 2"></button>
          <button type="button" aria-label="Promoción 3"></button>
        </div>
      </section>
      <?php if ($featured_product) :
        $featured_price = '$' . number_format((float) $featured_product->get_price(), 0, ',', '.');
        $featured_wa = $wa_url . '?text=' . rawurlencode('Hola! Quiero comprar: ' . $featured_product->get_name() . ' (' . $featured_price . ')');
      ?>
        <aside class="fv-featured-product">
          <div class="fv-featured-product__media"><span>Oferta destacada</span><a href="<?php echo esc_url($featured_product->get_permalink()); ?>"><?php echo wp_kses_post($featured_product->get_image('woocommerce_single')); ?></a></div>
          <div class="fv-featured-product__copy"><small>Recomendado para ti</small><h2><?php echo esc_html($featured_product->get_name()); ?></h2><div class="fv-featured-product__price"><?php echo wp_kses_post($featured_product->get_price_html()); ?></div><a class="fv-button fv-button--featured" href="<?php echo esc_url($featured_wa); ?>" target="_blank" rel="noopener">Comprar por WhatsApp</a></div>
        </aside>
      <?php endif; ?>
    </div>

    <section class="fv-section fv-campaigns fv-reveal">
      <div class="fv-section__head">
        <div><span class="fv-kicker">Campañas Farmacias del Sur</span><h2>Más formas de cuidarte</h2><p>Ideas, productos y recomendaciones para cada momento.</p></div>
        <a class="fv-text-link" href="<?php echo esc_url($shop_url); ?>">Ver catálogo completo →</a>
      </div>
      <div class="fv-campaigns__grid">
        <a class="fv-campaign fv-campaign--health" href="<?php echo esc_url($shop_url); ?>"><span class="screen-reader-text">Cuida tu salud todos los días. Ver productos.</span></a>
        <a class="fv-campaign fv-campaign--vitamins" href="<?php echo esc_url(home_url('/product-category/vitaminas/')); ?>"><span class="screen-reader-text">Vitaminas para tu día a día. Hasta 20% de descuento.</span></a>
        <a class="fv-campaign fv-campaign--skin" href="<?php echo esc_url(home_url('/product-category/dermocosmetica/')); ?>"><span class="screen-reader-text">Tu rutina, piel más sana. Ver dermocosmética.</span></a>
        <a class="fv-campaign fv-campaign--winter" href="<?php echo esc_url(home_url('/product-category/medicamentos/')); ?>"><span class="screen-reader-text">Temporada de resfríos. Protégete a ti y a tu familia.</span></a>
        <a class="fv-campaign fv-campaign--online" href="<?php echo esc_url($shop_url); ?>"><span class="screen-reader-text">Compra online fácil y seguro. Ir al catálogo.</span></a>
      </div>
      <div class="fv-service-band" aria-label="Servicios Farmacias del Sur">
        <div><span>🚚</span><strong>Envío gratis</strong><small>Desde $34.990</small></div>
        <div><span>⌁</span><strong>Retiro en tienda</strong><small>Listo desde 2 horas</small></div>
        <div><span>▣</span><strong>Pago protegido</strong><small>Transacción segura</small></div>
        <div><span>✚</span><strong>Atención cercana</strong><small>Te orientamos por WhatsApp</small></div>
      </div>
    </section>

    <section class="fv-section fv-products fv-reveal">
      <div class="fv-section__head">
        <div><span class="fv-kicker">Recién llegados</span><h2>Novedades para cuidarte</h2></div>
        <a class="fv-text-link" href="<?php echo esc_url($shop_url); ?>">Ver catálogo →</a>
      </div>
      <?php echo do_shortcode('[products limit="12" columns="4" orderby="date" order="DESC"]'); ?>
    </section>

    <section class="fv-connect fv-reveal">
      <div class="fv-connect__intro"><span class="fv-kicker">Siempre cerca</span><h2>Hablemos</h2><p>Escríbenos, llámanos o síguenos para conocer novedades y promociones.</p></div>
      <div class="fv-connect__grid">
        <a class="fv-contact-card fv-contact-card--wa" href="<?php echo esc_url($wa_url); ?>" target="_blank" rel="noopener"><span>◉</span><div><small>Compra por</small><strong>WhatsApp</strong><em><?php echo esc_html($contact['whatsapp_display'] ?? '+56 9 0000 0000'); ?></em></div></a>
        <a class="fv-contact-card fv-contact-card--phone" href="tel:<?php echo esc_attr($contact['phone_raw'] ?? '56220000000'); ?>"><span>☎</span><div><small>Atención telefónica</small><strong><?php echo esc_html($contact['phone_display'] ?? '+56 2 2000 0000'); ?></strong><em><?php echo esc_html($contact['hours'] ?? 'Lun–Vie 09:00–19:00'); ?></em></div></a>
        <a class="fv-contact-card fv-contact-card--ig" href="https://instagram.com/<?php echo esc_attr($contact['instagram'] ?? 'farmaciasdelsur.cl'); ?>" target="_blank" rel="noopener"><span>◎</span><div><small>Síguenos en</small><strong>Instagram</strong><em><?php echo esc_html($contact['instagram_label'] ?? '@farmaciasdelsur.cl'); ?></em></div></a>
      </div>
    </section>

    <section class="fv-cta fv-reveal">
      <div>
        <h2>¿Necesitas orientación?</h2>
        <p>Escríbenos y te ayudamos a encontrar el producto adecuado.</p>
      </div>
      <a class="fv-button fv-button--primary" href="<?php echo esc_url($wa_url); ?>" target="_blank" rel="noopener">Hablar por WhatsApp →</a>
    </section>
  </div>
</main>

<?php get_footer();
