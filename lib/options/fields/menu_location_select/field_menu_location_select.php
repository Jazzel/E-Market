<?php
class Emarket_Options_menu_location_select extends Emarket_Options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since Emarket_Options 1.0.1
	*/
	function __construct($field = array(), $value ='', $parent = null ){
		
		parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
		$this->field = $field;
		$this->value = $value;
		
		
	}//function
	
	
	
	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since Emarket_Options 1.0.1
	*/
	function render(){
		
		$class = (isset($this->field['class']))? 'class="emarket-single-select '.esc_attr( $this->field['class'] ).'" ': 'class="emarket-single-select"';
		global $_wp_registered_nav_menus;
		$placehold = ( isset( $this->field['placehold'] ) )? $this->field['placehold'] : esc_html__( 'Please select', 'emarket' );
		echo '<select id="'.esc_attr( $this->field['id'] ).'" name="'.$this->args['opt_name'].'['.$this->field['id'].']" '.$class.' >';
		
		if($_wp_registered_nav_menus){
			foreach ( $_wp_registered_nav_menus as $k => $v ) {
				echo '<option value="'.esc_attr( $k ).'"'.selected($this->value, $k, false).'>'.esc_html( $v ).'</option>';
			}
		}//if

		echo '</select>';
	
		echo (isset($this->field['desc']) && !empty($this->field['desc']))?' <span class="description">'.esc_html( $this->field['desc'] ).'</span>':'';
		
	}//function
	
}//class
?>