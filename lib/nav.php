<?php 
if ( class_exists( 'Mega_Menu' ) || class_exists( 'UberMenu' ) ) :
	return;
endif;


class Emarket_Menu_Admin {
	public static $custom_fields = array(
		'mega_active' => array(),
		'icon' => array(),
		'span' => array(),
		'dropdown_span' => array(),
		'dropdown_align' => array(),
		'show_description_as_subtitle' => array(),
		'hide_title' => array(),
		'disable_link' => array(),
		'advanced' => array(),
		'page_select' => array(),
		'advanced_content' => array(),	
		'imgupload'		   => array(),
	);
	static function options_setup() {
		global $wp_roles;
		$roles = $wp_roles->role_names;
		
		self::$custom_fields = array(
			'mega_active' => array(
				'type' => 'checkbox',
				'label' => esc_html__( 'Active mega menu', 'emarket' ),
				'default' => 0					
			),
			'icon' => array(
				'type' => 'text',
				'label' => 'Icon',
				'description' => sprintf( __('Show an icon by <a href="%s">Font Awesome 4.6</a>','emarket'), esc_url( 'http://fortawesome.github.com/Font-Awesome/' ) ),
				'default' => '',
				'preview' => true,
			),
			'span' => array(
				'type' => 'select',
				'label' =>  esc_html__( 'Dropdown sub size', 'emarket' ),
				'default' => '',
				'options' => array(
					'' => 'default',
					'col-1' => '1',
					'col-2' => '2',
					'col-3' => '3',
					'col-4' => '4',
					'col-5' => '5',
					'col-6' => '6'							
				)
			),
			'mega_full' => array(
					'type' => 'checkbox',
					'label' =>  esc_html__( 'Active Full Mega Menu', 'emarket' ),
					'default' => 0					
			),
			'dropdown_span' => array(
				'type' => 'select',
				'label' =>  esc_html__( 'Dropdown Size', 'emarket' ),
				'default' => '',
				'options' => array(
					'column-1' => '1',
					'column-2' => '2',
					'column-3' => '3',
					'column-4' => '4',
					'column-5' => '5',
					'column-6' => '6',
				)
			),
			'show_description_as_subtitle' => array(
				'type' => 'checkbox',
				'label' =>  esc_html__( 'Show Description as Subtitle', 'emarket' ),
				'default' => 0					
			),
			'hide_title' => array(
				'type' => 'checkbox',
				'label' =>  esc_html__( 'Hide Title', 'emarket' ),
				'description' =>  esc_html__( 'If icon or subtitle are using.', 'emarket' ),
				'default' => 0
			),
			'disable_link' => array(
				'type' => 'checkbox',
				'label' =>  esc_html__( 'Disable Link', 'emarket' ),
				'description' =>  esc_html__( 'Remove Link', 'emarket' ),
				'default' => 0
			),
			'advanced' => array(
				'type' => 'select',
				'label' =>  esc_html__( 'Advanced', 'emarket' ),
				'default' => '',
				'options' => array(
						'no' =>  esc_html__( 'Default', 'emarket' ),							
						'apc' =>  esc_html__( 'Append Advanced Content or Shortcode', 'emarket' ),
						'apcs' =>  esc_html__( 'Append Advanced Content with Page Select', 'emarket' )
				)
			),
			'advanced_content' => array(
				'type' => 'textarea',
				'label' =>  esc_html__( 'Advanced Content', 'emarket' ),
				'default' => '',
				'depends' => 'advanced_eq_rbd'
			),
			'page_select' => array(
				'type' => 'page_select',
				'label' =>  esc_html__( 'Page Select', 'emarket' ),
				'default' => 0,
				'description' =>  esc_html__( 'Select page to show on mega menu', 'emarket' ),					
			),	
			
			'which_user' => array(
				'type' => 'select',
				'label' =>  esc_html__( 'Who Can See This Link', 'emarket' ),
				'default' => '',
				'options' => array(
						'' =>  esc_html__( 'Everyone', 'emarket' ),							
						'logined' =>  esc_html__( 'Logged In Users', 'emarket' ),
						'logout' =>  esc_html__( 'Logged Out User', 'emarket' )
				)
			),
			'user_role' => array(
				'type' => 'multi_checkbox',
				'label' =>  esc_html__( 'User Roles', 'emarket' ),
				'default' => '',
				'options' => $roles
			),
			
			'imgupload' => array(
				'type'	=> 'upload',
				'label' =>  esc_html__( 'Menu Thumbnail', 'emarket' ),
				'default' => ''
			),
		);
	}
}
add_action( 'admin_init', array('Emarket_Menu_Admin','options_setup' ) );
if ( !class_exists('Emarket_Menu') ):

class Emarket_Menu {
	public function __construct(){
		add_filter( 'wp_setup_nav_menu_item',	array( $this, 'add_custom_nav_fields') );
		add_action( 'wp_update_nav_menu_item',	array( $this, 'update_custom_nav_fields'), 10, 3 );
		add_filter( 'wp_edit_nav_menu_walker',	array( $this, 'edit_walker'), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this,'emarket_menu_upload') );
	}
	function emarket_menu_upload(){
		wp_enqueue_media();
		wp_enqueue_script(
			'sw-menu-upload-js',get_template_directory_uri().'/lib/admin/js/field_upload.js',
			array('jquery'),
			time(),
			true
		);
		wp_localize_script('sw-menu-upload-js', 'sw_upload', array('url' => EMARKET_URL.'/admin/img/emarket.png') );
	}

	public function add_custom_nav_fields( $menu_item ){
		if ( count(Emarket_Menu_Admin::$custom_fields) ){
			foreach (Emarket_Menu_Admin::$custom_fields as $field => $field_opts){
				$menu_item->$field = get_post_meta( $menu_item->ID, '_menu_item_'.$field, true );
				if ( is_null($menu_item->$field) && isset($field_opts['default']) ){
					$menu_item->$field = $field_opts['default'];
				}
			}
		}
		return $menu_item;
	}

	public function update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ){
		if ( count(Emarket_Menu_Admin::$custom_fields) ){
			foreach (Emarket_Menu_Admin::$custom_fields as $field => $type){
				
				if ( isset($type['type']) && $type['type'] == 'checkbox') {
					
					if ( isset($_POST['menu-item-'.$field]) && is_array($_POST['menu-item-'.$field]) && isset( $_POST['menu-item-'.$field][$menu_item_db_id] ) ) {						
						$field_value = 1;
						update_post_meta( $menu_item_db_id, '_menu_item_'.$field, $field_value );
					} else {		
						delete_post_meta($menu_item_db_id, '_menu_item_'.$field);
					}
					
				} else if ( array_key_exists('menu-item-'.$field, $_POST) && is_array($_POST['menu-item-'.$field]) ){
					$field_value = isset( $_POST['menu-item-'.$field][$menu_item_db_id] ) ? $_POST['menu-item-'.$field][$menu_item_db_id] : $type['default'];
					// sanitize values
					switch ($type){
						case 'text':
							$field_value = sanitize_text_field($field_value);
							if ($field == 'icon'){
								$field_value = sanitize_html_class($field_value);
							}
						break;
						
						case 'page_select':
							$field_value = intval( $field_value );
						break;
						
						case 'upload':
							$field_value = esc_url( $field_value );
						break;
						
						default:
					}
					if( strlen( trim( $field_value ) ) > 0 ) :
						update_post_meta( $menu_item_db_id, '_menu_item_'.$field, $field_value );
					else: 
						delete_post_meta( $menu_item_db_id, '_menu_item_'.$field );
					endif;
				}
			}
		}
	}

	public function edit_walker($walker, $menu_id){
		return 'Emarket_Menu_Admin_Walker';
	}
}

endif;

if ( !class_exists('Emarket_Menu_Admin_Walker') ):

/**
 *  /!\ This is a copy of Walker_Nav_Menu_Edit class in core
 *
 * Create HTML list of nav menu input items.
 *
 * @package WordPress
 * @since 3.0.0
 * @uses Walker_Nav_Menu
 */
class Emarket_Menu_Admin_Walker extends Walker_Nav_Menu  {
	/**
	 * @see Walker_Nav_Menu::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	function start_lvl(&$output, $depth = 0, $args = Array()) {}

	/**
	 * @see Walker_Nav_Menu::end_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	function end_lvl(&$output, $depth = 0, $args = Array()) {
	}

	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param object $args
	 */
	
	function start_el(&$output, $item, $depth = 0, $args = Array(), $current_object_id = 0) {
		global $_wp_nav_menu_max_depth;
		$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		ob_start();
		$item_id = esc_attr( $item->ID );
		$removed_args = array(
				'action',
				'customlink-tab',
				'edit-menu-item',
				'menu-item',
				'page-tab',
				'_wpnonce',
		);

		$original_title = '';
		if ( 'taxonomy' == $item->type ) {
			$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
			if ( is_wp_error( $original_title ) )
				$original_title = false;
		} elseif ( 'post_type' == $item->type ) {
			$original_object = get_post( $item->object_id );
			$original_title = $original_object->post_title;
		}

		$classes = array(
				'menu-item menu-item-depth-' . $depth,
				'menu-item-' . esc_attr( $item->object ),
				'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
		);

		$title = $item->title;

		if ( ! empty( $item->_invalid ) ) {
			$classes[] = 'menu-item-invalid';
			/* translators: %s: title of menu item which is invalid */
			$title = sprintf( esc_html__( '%s (Invalid)', 'emarket' ), $item->title );
		} elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
			$classes[] = 'pending';
			/* translators: %s: title of menu item in draft status */
			$title = sprintf( esc_html__( '%s (Pending)','emarket' ), $item->title );
		}
		
		$title = empty( $item->label ) ? $title : $item->label;
		?>
		<li id="menu-item-<?php echo esc_attr( $item_id ); ?>" class="<?php echo implode(' ', $classes ); ?>">
			<dl class="menu-item-bar">
				<dt class="menu-item-handle">
					<span class="item-title"><input id="<?php echo esc_attr( "menu-item-checkbox-$item_id" ) ?>" type="checkbox" class="menu-item-checkbox" data-menu-item-id="<?php echo esc_attr( $item_id ) ?>"><?php echo esc_html( $title ); ?></span>
					<span class="item-controls">
						<span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
						<span class="item-order hide-if-js">
							<a href="<?php
								echo wp_nonce_url(
									add_query_arg(
										array(
											'action' => 'move-up-menu-item',
											'menu-item' => $item_id,
										),
										remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
									),
									'move-menu_item'
								);
							?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up', 'emarket'); ?>">&#8593;</abbr></a>
							|
							<a href="<?php
								echo wp_nonce_url(
									add_query_arg(
										array(
											'action' => 'move-down-menu-item',
											'menu-item' => $item_id,
										),
										remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
									),
									'move-menu_item'
								);
							?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down', 'emarket'); ?>">&#8595;</abbr></a>
						</span>
						<a class="item-edit" id="edit-<?php echo esc_attr( $item_id ); ?>" title="<?php esc_attr_e('Edit Menu Item', 'emarket'); ?>" href="<?php
							echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
						?>"><?php esc_html_e( 'Edit Menu Item', 'emarket' ); ?></a>
					</span>
				</dt>
			</dl>
			<div class="menu-item-settings" id="menu-item-settings-<?php echo esc_attr( $item_id ); ?>">
				<?php if( 'custom' == $item->type ) : ?>
					<p class="field-url description description-wide">
						<label for="edit-menu-item-url-<?php echo esc_attr( $item_id ); ?>">
							<?php esc_html_e( 'URL','emarket' ); ?><br />
							<input type="text" id="edit-menu-item-url-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
						</label>
					</p>
				<?php endif; ?>
				<p class="description description-thin">
					<label for="edit-menu-item-title-<?php echo esc_attr( $item_id ); ?>">
						<?php esc_html_e( 'Navigation Label','emarket' ); ?><br />
						<input type="text" id="edit-menu-item-title-<?php echo esc_attr( $item_id ); ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
					</label>
				</p>
				<p class="description description-thin">
					<label for="edit-menu-item-attr-title-<?php echo esc_attr( $item_id ); ?>">
						<?php esc_html_e( 'Title Attribute','emarket' ); ?><br />
						<input type="text" id="edit-menu-item-attr-title-<?php echo esc_attr( $item_id ); ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
					</label>
				</p>
				<p class="field-link-target description">
					<label for="edit-menu-item-target-<?php echo esc_attr( $item_id ); ?>">
						<input type="checkbox" id="edit-menu-item-target-<?php echo esc_attr( $item_id ); ?>" value="_emarket" name="menu-item-target[<?php echo esc_attr( $item_id ); ?>]"<?php checked( $item->target, '_emarket' ); ?> />
						<?php esc_html_e( 'Open link in a new window/tab','emarket' ); ?>
					</label>
				</p>
				<p class="field-css-classes description description-thin">
					<label for="edit-menu-item-classes-<?php echo esc_attr( $item_id ); ?>">
						<?php esc_html_e( 'CSS Classes (optional)','emarket' ); ?><br />
						<input type="text" id="edit-menu-item-classes-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
					</label>
				</p>
				<p class="field-xfn description description-thin">
					<label for="edit-menu-item-xfn-<?php echo esc_attr( $item_id ); ?>">
						<?php esc_html_e( 'Link Relationship (XFN)','emarket' ); ?><br />
						<input type="text" id="edit-menu-item-xfn-<?php echo esc_attr( $item_id ); ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
					</label>
				</p>
				<p class="field-description description description-wide">
					<label for="edit-menu-item-description-<?php echo esc_attr( $item_id ); ?>">
						<?php esc_html_e( 'Description','emarket' ); ?><br />
						<textarea id="edit-menu-item-description-<?php echo esc_attr( $item_id ); ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo esc_attr( $item_id ); ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
						<span class="description"><?php esc_html_e('The description will be displayed in the menu if the current theme supports it.','emarket'); ?></span>
					</label>
				</p>
				<div class="advance-menu">
					<a href="javascript:void(0);" class="menu-advance-href" data-id="menu-advance-<?php  echo esc_attr( $item_id ) ?>"><?php esc_html_e( 'Show/Hide Advance Menu Config', 'emarket'); ?> </a>
					<div class="menu-config-content" id="menu-advance-<?php  echo esc_attr( $item_id ) ?>" style="display: none;">
						<?php
						foreach (Emarket_Menu_Admin::$custom_fields as $field => $field_opts):
							$menu_item_parent_id = $item->menu_item_parent;
							if ( $menu_item_parent_id == 0){
								// is level 1
								if ( $field == 'span' || $field == 'advanced' || $field == 'advanced_content' || $field == 'page_select' ) {
									continue;
								}
							} else if ( $menu_item_parent_id > 0 ) {
								if ( $field == 'dropdown_span' ||  $field == 'mega_active' || $field == 'mega_full' ){
									continue;
								}
							}
						?><p class="field-<?php echo esc_attr( $field ); ?> description description-wide">
								<label for="edit-menu-item-<?php echo esc_attr( $field ); ?>-<?php echo esc_attr( $item_id ); ?>">						
								<?php if( $field_opts['type'] !='checkbox' ){ echo $field_opts['label'];} ?>
								</label>
								<?php
								switch( $field_opts['type'] ):
									default:
									case 'text':
										?><input type="text" id="edit-menu-item-<?php echo esc_attr( $field ); ?>-<?php echo esc_attr( $item_id ); ?>" class="code edit-menu-item-<?php echo esc_attr( $field ); ?>" name="menu-item-<?php echo esc_attr( $field ); ?>[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->$field ); ?>" /><?php
										break;
									case 'textarea':
										?><textarea id="edit-menu-item-<?php echo esc_attr( $field ); ?>-<?php echo esc_attr( $item_id ); ?>" class="widefat edit-menu-item-<?php echo esc_attr( $field ); ?>" rows="3" cols="20" name="menu-item-<?php echo esc_attr( $field ); ?>[<?php echo esc_attr( $item_id ); ?>]"><?php echo esc_html( $item->$field ); // textarea_escaped ?></textarea><?php
										break;
									case 'select': 
										?><select  id="edit-menu-item-<?php echo esc_attr( $field ); ?>-<?php echo esc_attr( $item_id ); ?>" class="edit-menu-item-<?php echo esc_attr( $field ); ?>" name="menu-item-<?php echo esc_attr( $field ); ?>[<?php echo esc_attr( $item_id ); ?>]" >
										<?php if ( isset($field_opts['options']) && is_array($field_opts['options']) ): ?>
											<?php foreach ( $field_opts['options'] as $opt_val => $opt_label ): ?>
											<option value="<?php echo esc_attr($opt_val); ?>"
												<?php if ( $opt_val == $item->$field ){ ?>selected="selected"<?php }?>
											><?php echo sprintf( '%s', $opt_label ); ?></option>
												
											<?php endforeach; ?>
										<?php endif; ?>
										</select>
									<?php 
										break;
									case 'page_select':
									?><select  id="edit-menu-item-<?php echo esc_attr( $field ); ?>-<?php echo esc_attr( $item_id ); ?>" class="edit-menu-item-<?php echo esc_attr( $field ); ?>" name="menu-item-<?php echo esc_attr( $field ); ?>[<?php echo esc_attr( $item_id ); ?>]" >
										<option value="0" selected><?php esc_html_e( 'Select page', 'emarket' ); ?></option>
										<?php $pages = get_pages(); ?>
										<?php foreach ( $pages as $page ): ?>
										<option value="<?php echo esc_attr( $page-> ID ); ?>"
											<?php if ( $page->ID == $item->$field ){ ?>selected="selected"<?php }?>
										><?php echo esc_html( $page->post_title ); ?></option>
										
										<?php endforeach; ?>										
										</select>
									<?php 
										break;
										case 'upload': 
									?>			
											<input type="hidden" id="edit-menu-item-<?php echo esc_attr( $field ); ?>-<?php echo esc_attr( $item_id ); ?>" class="code edit-menu-item-<?php echo esc_attr( $field ); ?>" name="menu-item-<?php echo esc_attr( $field ); ?>[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->$field ); ?>" />
											<?php
												echo '<img class="emarket-opts-screenshot" id="emarket-opts-screenshot-'.esc_attr( $item_id ).'" src="'. esc_url( ( $item->$field != '' ) ? esc_url( $item->$field ) : get_template_directory_uri() . '/lib/admin/img/emarket.png' ) .'" width = "30" />';
											if($item -> imgupload == ''){$remove = ' style="display:none;"';$upload = '';}else{$remove = '';$upload = ' style="display:none;"';}
											echo ' <a href="javascript:void(0);" class="sw-menu-upload button-secondary"'.$upload.' rel-id="#edit-menu-item-'.esc_attr( $field ).'-'.esc_attr( $item_id ).'">'.esc_html__('Browse', 'emarket').'</a>';
											echo ' <a href="javascript:void(0);" class="sw-menu-upload-remove"'.$remove.' rel-id="#edit-menu-item-'.esc_attr( $field ).'-'.esc_attr( $item_id ).'">'.esc_html__('Remove', 'emarket').'</a>';
											?>								
									<?php
										break;	
										case 'checkbox':
									?>
										<input type="checkbox" id="edit-menu-item-<?php echo esc_attr( $field ); ?>-<?php echo esc_attr( $item_id ); ?>" value="1" name="menu-item-<?php echo esc_attr( $field ); ?>[<?php echo esc_attr( $item_id ); ?>]" <?php checked( $item->$field, 1 ); ?> />
									<?php
										break;	
										case 'multi_checkbox':
										$multi_value = array();
										if( is_array( $item->$field ) ){
											$multi_value = $item->$field;
										}else{
											$multi_value[] = $item->$field;
										}
										$checkbox_value = $multi_value;
										$i = 0;
									?>
											<?php foreach( $field_opts['options'] as $key => $option ){ ?>
												<label for="edit-menu-item-<?php echo esc_attr( $field ); ?>-<?php echo esc_attr( $item_id . $i ); ?>" class="label-multicheck"><input type="checkbox" id="edit-menu-item-<?php echo esc_attr( $field ); ?>-<?php echo esc_attr( $item_id . $i ); ?>" value="<?php echo esc_attr( $key ); ?>" name="menu-item-<?php echo esc_attr( $field ); ?>[<?php echo esc_attr( $item_id ); ?>][]" <?php echo checked( in_array( $key, $checkbox_value ), true ) ?>/><?php echo esc_html( $option ); ?></label>
											<?php $i ++; } ?>
									<?php
										
										break;
									?>
										
								<?php endswitch; ?>
								<?php if( $field_opts['type'] =='checkbox' ){ echo $field_opts['label'];} ?>
								<?php if (isset( $field_opts['description'] ) && is_string( $field_opts['description'] )): ?><span class="description"> ( <?php echo $field_opts['description']; ?> ) </span><?php endif; ?>
							</p>
							
						<?php endforeach; ?>
					</div>
				</div>
				<div class="menu-item-actions description-wide submitbox">
					<?php if( 'custom' != $item->type && $original_title !== false ) : ?>
						<p class="link-to-original">
							<?php printf( __('Original: %s','emarket'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
						</p>
					<?php endif; ?>
					<a class="item-delete submitdelete deletion" id="delete-<?php echo esc_attr( $item_id ); ?>" href="<?php
					echo wp_nonce_url(
						add_query_arg(
							array(
								'action' => 'delete-menu-item',
								'menu-item' => $item_id,
							),
							remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
						),
						'delete-menu_item_' . $item_id
					); ?>"><?php esc_html_e('Remove','emarket'); ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" id="cancel-<?php echo esc_attr( $item_id ); ?>" href="<?php	echo esc_url( add_query_arg( array('edit-menu-item' => $item_id, 'cancel' => time()), remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) ) ) );
						?>#menu-item-settings-<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e('Cancel','emarket'); ?></a>
				</div>

				<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item_id ); ?>" />
				<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
				<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
				<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
				<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
				<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
			</div><!-- .menu-item-settings-->
			<ul class="menu-item-transport"></ul>
		<?php
		$output .= ob_get_clean();
	}
}

endif;


if ( !class_exists('Emarket_Menu_Walker') ):

class Emarket_Menu_Walker extends Walker_Nav_Menu {
	function check_current($classes) {
		return preg_match('/(current[-_])|active|dropdown/', $classes);
	}

	function start_lvl(&$output, $depth = 0, $args = array()) {
		$output .= "\n<ul class=\"dropdown-menu\">\n";
	}

	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		$item_html = '';
		parent::start_el($item_html, $item, $depth, $args);
		if( !$item->is_dropdown && ($depth === 0) ){
			$item_html = str_replace('<a', '<a class="item-link elementor-item"', $item_html);
			$item_html = str_replace('</a>', '</a>', $item_html);
		}
		if ($item->is_dropdown && ($depth === 0)) {
			$item_html = str_replace( '<a', '<a class="item-link elementor-item dropdown-toggle" data-toogle="dropdown"', $item_html );
			$item_html = str_replace( '</a>', '</a>', $item_html );
		}
		if( $item->current == true ){
			$item_html = str_replace('<a', '<a class="item-link elementor-item elementor-item-active"', $item_html);
			$item_html = str_replace('</a>', '</a>', $item_html);
		}
		elseif (stristr($item_html, 'li class="nav-header')) {
			$item_html = preg_replace('/<a[^>]*>(.*)<\/a>/iU', '$1', $item_html);
		}

		// icon - extra field
		if ( isset($item->icon) && !empty($item->icon) ){
			if ( is_rtl() ){
				$item_html = preg_replace('/(<a[^>]*>)(.*)(<\/a>)/iU', '$1<span class="menu-title">$2</span><span class="fa '.esc_attr($item->icon).'"></span>$3', $item_html);
			} else {
				$item_html = preg_replace('/(<a[^>]*>)(.*)(<\/a>)/iU', '$1<span class="fa '.esc_attr($item->icon).'"></span><span class="menu-title">$2</span>$3', $item_html);
			}
		}else if( isset($item->imgupload) && !empty($item->imgupload) ){
			if( $depth == 0 ){
				$item_html = preg_replace('/(<a[^>]*>)(.*)(<\/a>)/iU', '$1<span class="menu-img"><img src="'.esc_url($item->imgupload).'" alt="Menu Image"/></span><span class="menu-title">$2</span>$3', $item_html);
			}
		}
		else {
			$item_html = preg_replace('/(<a[^>]*>)(.*)(<\/a>)/iU', '$1<span class="menu-title">$2</span>$3', $item_html);
		}

		$output .= $item_html;
	}

	function display_element($element, &$children_elements, $max_depth, $depth = 0, $args = array(), &$output = '') {
		$element->is_dropdown = !empty($children_elements[$element->ID]);
		
		if( $element -> icon ){
			$element->classes[] = 'has-icon';
		}
		if ($element->is_dropdown) {
			if ($depth === 0) {
				$element->classes[] = 'dropdown';
			} elseif ($depth >= 1) {
				$element->classes[] = 'dropdown-submenu';
			}
		}

		parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
	}
}

endif;

if ( !class_exists('Emarket_Mega_Menu_Walker') ):

class Emarket_Mega_Menu_Walker extends Walker_Nav_Menu {
	
	function check_current($classes) {
		return preg_match('/(current[-_])|active|dropdown/', $classes);
	}
	
	public function have_dropdown_span( $item ){
		return !empty($item->dropdown_span) && preg_match('/column-(1|2|3|4|5|6)/', $item->dropdown_span);
	}
	
	function start_lvl( &$item, $depth = 0, $args = array() ) {
		$output = '';
		$dropdown_span = $item->dropdown_span;
		if ( $depth === 1 ){		
			$output = ( $item -> mega_active == 1 ) ? '<ul class="dropdown-menu nav-level'.esc_attr( $depth ).' '. esc_attr( $dropdown_span ) .'">' : '<ul class="dropdown-menu">';
		} elseif ( $depth > 1 ) {
			$output = '<ul class="dropdown-sub nav-level'.esc_attr( $depth ).'">';
		}
		
		return $output;
	}
	
	function end_lvl( &$item, $depth = 0, $args = array() ){
		if ( $depth === 1 ){
			$output = ( $item -> mega_active == 1 ) ? '</ul>' : '</ul>';
		} elseif ( $depth > 1 ) {
			$output = '</ul>';
		}
		return $output;
	}
	

	function start_el( &$output, $item, $depth = 0, $args = array(), $current_object_id = 0 ) {
		$output = '';
		$class_names = $value = '';
		$advanced = empty( $item->advanced ) ? 'no' : $item->advanced;
		
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		
		if( ($depth === 0) ){
			$class_names .= '';
			$class_names .= ( $item -> mega_active == 1 ) ? ' emarket-mega-menu' : ' emarket-menu-custom';
			$class_names .= ( $item -> mega_full == 1 ) ? ' megamenu-full' : '';
			$class_names .= ' level1';
		}		
		if( $item -> imgupload != '' ){
			$class_names .= ' emarket-menu-img';
		}
		if( $item -> icon != '' ){
			$class_names .= ' emarket-menu-icon';
		}
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .=  '<li ' .  $class_names .'>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';		
		
		// if have child.
		if (!$item->is_dropdown && ($depth === 0)){
			$attributes .= ' class="item-link elementor-item" ';			
		}
		if ($item->is_dropdown && ($depth === 0)){
			$attributes .= ' class="item-link elementor-item dropdown-toggle"';
			$attributes .= ' data-toogle="dropdown"';
		}
		
		$show_icon = isset($item->icon) && !empty($item->icon);
		$show_img = isset($item->imgupload) && !empty($item->imgupload);
		$show_subtitle = !empty($item->description) && isset($item->show_description_as_subtitle) && $item->show_description_as_subtitle== 1;
		$show_title = !( isset($item->hide_title) && $item->hide_title == 1 );
		$hide_link = ( isset($item->hide_title) && $item->disable_link == 1 );

		$page_content = ( $item->page_select != 0 ) ? intval( $item->page_select ) : 0;
		$apcontent = '';
		if ( preg_match('/(apc|apcs)/i', $advanced) ){
			
			/* append advanced content */
			$apcontent 		= empty( $item->advanced_content ) ? '' : $item->advanced_content;
			
			/* Advance with custom shortcode or html content */
			if ( preg_match( '/apc/i', $advanced ) && !empty( $apcontent ) ){
				$apcontent = '<div class="container">'. do_shortcode( $apcontent ) . '</div>';
			}
			
			/* Advance with page select */
			if ( preg_match( '/apcs/i', $advanced ) && !empty( $page_content ) ){
				$apcontent = '<div class="container">';
				$content_el = sw_get_the_content_by_id( $page_content );
				if( defined( 'ELEMENTOR_VERSION' ) && \Elementor\Plugin::$instance->documents->get( $page_content ) ){
					$content_el = \Elementor\Plugin::$instance->frontend->get_builder_content( $page_content );
				}
				$apcontent .= do_shortcode( $content_el ) . '</div>';
			}
		}
		$item_output = !empty($args->before) ? $args->before : '';
		$acontent = '';
		if ( $show_icon || $show_title || $show_subtitle || $show_img ){
			$show_count = 0;
			$show_classes = array();
			if ( $show_icon ){
				$show_count++;
				$show_classes[] = 'have-icon';
				$span_icon = '<span class="fa '.esc_attr( $item->icon ).'"></span>';
			} else {
				$span_icon = '';
			}
			
			if ( $show_title ){
				$show_count++;
				$show_classes[] = 'have-title';
				$span_title = '<span class="menu-title">';
				$span_title .= !empty($args->link_before) ? $args->link_before : '';
				$span_title .= apply_filters( 'the_title', $item->title, $item->ID );
				$span_title .= !empty($args->link_after) ? $args->link_after : '';
				$span_title .= '</span>';
			} else {
				$span_title = '';
			}
			if( $show_img ){
				$span_img = '<span class="menu-img"><img src="'. esc_url( $item -> imgupload ) .'" alt="Menu Image" /></span>';
			}else{
				$span_img = '';
			}
			if ( $show_subtitle ){
				$show_count++;
				$show_classes[] = 'have-subtitle';
				$span_subtitle = '<span class="menu-subtitle">' . esc_html( $item->description ) . '</span>';
			} else {
				$span_subtitle = '';
			}
			
			$ina = '<span class="'. implode(' ', $show_classes) .'">';
			$ina .= $span_icon . $span_img . $span_title  . $span_subtitle;
			$ina .= '</span>';
			if( $hide_link ){
				$acontent =  $ina;
			}else{
				$acontent = '<a' . $attributes . '>' . $ina . '</a>';
			}
		} else {
			$acontent = '';
		}
		
		$item_output .= $acontent . $apcontent;
		
		$item_output .= !empty($args->after) ? $args->after : '';
	
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		
		return $output;
	}

	function end_el(&$output, $element, $depth=0, $args=array() ){
		return '</li>';
	}

	
	public function getElement( $element, $children_elements, $max_depth, $depth = 0, $args = array() ){
		
		if ( !$element )
			return;
		
		$element->is_dropdown = !empty($children_elements[$element->ID]);
		
		if ($element->is_dropdown) {
			if ($depth === 0) {				
				$element->classes[] = 'dropdown';				
				if ( !$this->have_dropdown_span($element) ){
					foreach ($children_elements[$element->ID] as $child){
						$child->span = '';
					}
				} else {
					foreach ($children_elements[$element->ID] as $child){
						$child->default_span = $element->dropdown_span;
					}
				}
				
			} elseif ($depth === 1) {
				$element->classes[] = 'dropdown-submenu';
			}
		}
		$need_span = !( empty($element->span) && empty($element->default_span) );
		if ( $need_span && $depth === 1 ){
			$span_class = !empty($element->span) ? $element->span : ( !empty($element->default_span) ? $element->default_span : '');
			$element->classes[] = $span_class;
		}
		
		$output = '';
		$id_field = $this->db_fields['id'];
		
		//display this element
		$this->start_el($output, $element, $depth, $args);
		
		$id = $element->$id_field;
		
		// descend only when the depth is right and there are childrens for this element
		if ( ($max_depth == 0 || $max_depth > $depth+1) && isset( $children_elements[$id]) ) {
			$output .= $this->start_lvl( $element, $depth + 1, $args );
			foreach( $children_elements[ $id ] as $child ){
				$output .= $this->getElement($child, $children_elements, $max_depth, $depth + 1, $args);
			}
			unset( $children_elements[ $id ] );
			
			$output .= $this->end_lvl( $element, $depth + 1, $args );
			
		}
		
		$output .= $this->end_el($output, $element, $depth, $args);
		
		return $output;
	}
	
	function walk( $elements, $max_depth, ...$args ){ 
		
		$output = '';
		
		if ($max_depth < -1) //invalid parameter
			return $output;
		
		if (empty($elements)) //nothing to walk
			return $output;
		
		$id_field = $this->db_fields['id'];
		$parent_field = $this->db_fields['parent'];
		
		// flat display
		if ( -1 == $max_depth ) {
			$empty_array = array();
			foreach ( $elements as $e )
				$output .= $this->getElement( $e, $empty_array, 1, 0, $args );
			return $output;
		}
		
		/*
		 * need to display in hierarchical order
		* separate elements into two buckets: top level and children elements
		* children_elements is two dimensional array, eg.
		* children_elements[10][] contains all sub-elements whose parent is 10.
		*/
		$top_level_elements = array();
		$children_elements  = array();
		foreach ( $elements as $e) {
			if ( 0 == $e->$parent_field )
				$top_level_elements[] = $e;
			else
				$children_elements[ $e->$parent_field ][] = $e;
		}
		
		/*
		 * when none of the elements is top level
		* assume the first one must be root of the sub elements
		*/
		if ( empty($top_level_elements) ) {
		
			$first = array_slice( $elements, 0, 1 );
			$root = $first[0];
		
			$top_level_elements = array();
			$children_elements  = array();
			foreach ( $elements as $e) {
				if ( $root->$parent_field == $e->$parent_field )
					$top_level_elements[] = $e;
				else
					$children_elements[ $e->$parent_field ][] = $e;
			}
		}
		
		foreach ( $top_level_elements as $e ){
			$output .= $this->getElement( $e, $children_elements, $max_depth, 0, $args );
		}
		return $output;
	}
}

endif;

/**
 * Remove the id="" on nav menu items
 * Return 'menu-slug' for nav menu classes
 */
function emarket_nav_menu_css_class( $classes, $item ) {
	$slug = sanitize_title($item->title);
	$classes = preg_replace('/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', 'active', $classes);
	$classes = preg_replace('/^((menu|page)[-_\w+]+)+/', '', $classes);

	$classes[] = 'menu-' . $slug;

	$classes = array_unique($classes);

	return array_filter($classes, 'emarket_element_empty');
}
add_filter('nav_menu_css_class', 'emarket_nav_menu_css_class', 10, 2);
add_filter('nav_menu_item_id', '__return_null');

/**
 * Clean up wp_nav_menu_args
 *
 * Remove the container
 * Use Emarket_Menu_Walker() by default
*/
function emarket_nav_menu_args($args = '') {
	$emarket_nav_menu_args['container'] = false;
	$emarket_theme_locates = array();
	$emarket_menu = emarket_options()->getCpanelValue( 'menu_location' );
	if( !is_array( $emarket_menu ) ){
		$emarket_theme_locates[] = $emarket_menu;
	}else{
		$emarket_theme_locates = $emarket_menu;
	}
	if (!$args['items_wrap']) {
		$emarket_nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
	}
	if (!$args['walker']) {
		if ( 'mega' == emarket_options()->getCpanelValue( 'menu_type' ) && $emarket_theme_locates[0] != NULL && in_array( $args['theme_location'], $emarket_theme_locates ) ){
			$args['menu_class'] .= ' emarket-mega';
			$emarket_nav_menu_args['walker'] = new Emarket_Mega_Menu_Walker();
			
			if( !emarket_mobile_check() ) :
				wp_enqueue_script('emarket_megamenu', get_template_directory_uri() . '/js/megamenu.js', array(), null, true);
			endif;
			
		} else {
			$emarket_nav_menu_args['walker'] = new Emarket_Menu_Walker();
		}
	}

	return array_merge($args, $emarket_nav_menu_args);
}
add_filter('wp_nav_menu_args', 'emarket_nav_menu_args');

function emarket_exclude_menu_items( $items = array() ){
	if ( empty( $items ) ) {
		return $items;
	}
	$excluded = array();
	$logged_in = is_user_logged_in();
	foreach ( $items as $key => $item ) {
		$exclude = in_array( $item->menu_item_parent, $excluded );
		if ( $item->object == 'logout' ) {
			$exclude = ! $logged_in;
		} elseif ( $item->object == 'login' ) {
			$exclude = $logged_in;
		} else {
			$which_user = get_post_meta( $item->ID, '_menu_item_which_user', true );
			switch ( $which_user ) {
				case 'logined':
					
					if ( ! $logged_in ) {
						$exclude = true;
						$user_roles = get_post_meta( $item->ID, '_menu_item_user_role', true );
					} elseif ( ! empty( $user_roles ) ) {

						// Checks all roles, should not exclude if any are active.
						$valid_role = false;

						foreach ( $user_roles as $role ) {
							if ( current_user_can( $role ) ) {
								$valid_role = true;
								break;
							}
						}

						if ( ! $valid_role ) {
							$exclude = true;
						}
					}
					break;

				case 'logout':
					$exclude = $logged_in;
					break;

			}

		}
		$exclude = apply_filters( 'sw_should_exclude_item', $exclude, $item );
		
		// unset non-visible item
		if ( $exclude && !is_admin() ) {
			$excluded[] = $item->ID; // store ID of item
			unset( $items[ $key ] );
		}
	}
	return $items;
}
add_filter( 'wp_get_nav_menu_items', 'emarket_exclude_menu_items', 100 );


/**
	* Call To Responsive Menu
**/
include( get_template_directory().'/lib/responsive_dropdown_sidebar.php' );
