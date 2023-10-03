<?php
class Modis_Filter_Widget extends WP_Widget {
	/* General Setup */
	public function __construct() {
		/* Widget settings. */
		$widget_ops = array(
			'classname' => 'modis_filter_widget',
			'description' => "Виджет который выводит блок Ajax Фильтрация"
		);
		/* Widget control settings. */
		$control_ops = array(
			'width' => 500,
			'height' => 45,
			'id_base' => 'modis_filter_widget'
		);
		/* Initialize the widget. */
		parent::__construct('modis_filter_widget', 'Ajax Фильтрация', $widget_ops, $control_ops);
	}

	/* Display Widget */
	public function widget($args, $instance) {
		$title1 = $instance['title1'];
		$title2 = $instance['title2'];

		$prices = $this->get_filtered_price();
		$min = ($prices->min_price > 0) ? floor( $prices->min_price ) : 10;
		$max = ($prices->max_price > 0) ? ceil( $prices->max_price ) : 1200;

		global $woocommerce;

		// Display Widget
		?>
		<div class="sortby wayup_sortby" data-minprice="<?php echo $min; ?>" data-maxprice="<?php echo $max; ?>">
			<h5 class="sortby_title"><?php echo $title1; ?></h5>
			<div id="slider-range"></div>
			<p class="sortby__price">
				<label for="amount">Цена: </label>
				<span class="field">
                    <?php echo get_woocommerce_currency_symbol(); ?><input type="text" value="<?php echo $min; ?>" id="priceMin" class="min_price" > - <?php echo get_woocommerce_currency_symbol(); ?> <input type="text" id="priceMax" value="<?php echo $max; ?>" class="max_price">
                </span>
			</p>
		</div>
		<div class="categories side-nav log">
			<h5 class="categories_title"><?php echo $title2; ?></h5>
			<div id="st-accordion" class="st-accordion">
				<ul>
					<?php
					$categories = get_terms(
						'product_cat',
						array(
							'orderby' => 'name',
							'hierarchical' => true,
							'hide_empty' => 1,
							'parent' => 0
					));

					foreach($categories as $cat) { ?>
						<li class="modis_filter_check">
						<?php $temp_cat = get_terms(
							'product_cat',
							array(
								'orderby' => 'name',
								'hierarchical' => true,
								'hide_empty' => 1,
								"parent" => $cat->term_id
							)
						);

						$class = '';
						if ($temp_cat) {
							$class = "has_child";
						} else {
							$class = "no_child";
						}
						?>
						<input id="term_<?php echo $cat->term_id; ?>" type="checkbox" name="category" value="<?php echo $cat->term_id; ?>">
						<a href="#" style="display: inline-block;" class="<?php echo $class; ?>"><?php echo $cat->name; ?></a>
						<?php
						if ($temp_cat) {
							echo '<div class="st-content cat-list">';
							foreach ($temp_cat as $temp) {
								?>
								<div class="log_group check" style="margin-left: 20px;">
									<input id="term_<?php echo $temp->term_id; ?>" type="checkbox" name="category" value="<?php echo $temp->term_id; ?>">
									<label for="term_<?php echo $temp->term_id; ?>"><?php echo $temp->name; ?></label>
								</div>
							<?php }
							echo '</div>';
						}
						echo '</li>';
					}
					?>
				</ul>
			</div>
		</div>
		<?php
	}

	protected function get_filtered_price() {
		global $wpdb, $sql;
		$args = wc()->query->get_main_query()->query_vars;
		$tax_query = isset($args['tax_query']) ? $args['tax_query'] : array();
		$meta_query = isset($args['meta_query']) ? $args['meta_query'] : array();

		// ... (rest of the function remains unchanged)

		return $wpdb->get_row($sql); // WPCS: unprepared SQL ok
	}

	// Update Widget
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title1'] = strip_tags($new_instance['title1']);
		$instance['title2'] = strip_tags($new_instance['title2']);
		return $instance;
	}

	// Widget Settings
	public function form($instance) {
		// default widget settings.
		$defaults = array(
			'title1' => 'Сортировать по цене',
			'title2' => 'Категории товаров'
		);
		$instance = wp_parse_args((array) $instance, $defaults);
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title1'); ?>">Фильтрация по цене | Заголовок</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title1'); ?>" name="<?php echo $this->get_field_name('title1'); ?>" value="<?php echo $instance['title1']; ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('title2'); ?>">Фильтрация по категории | Заголовок</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title2'); ?>" name="<?php echo $this->get_field_name('title2'); ?>" value="<?php echo $instance['title2']; ?>">
		</p>
		<?php
	}
}