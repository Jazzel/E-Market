<?php
class Emarket_Options_tags_multi_select extends Emarket_Options{	
	
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
		$placehold = ( isset( $this->field['placehold'] ) )? $this->field['placehold'] : esc_html__( 'Please select', 'emarket' );
		
		echo '<select id="'.esc_attr( $this->field['id'] ).'" name="'.$this->args['opt_name'].'['.$this->field['id'].'][]" '.$class.'multiple="multiple" data-placehold="'. esc_attr( $placehold ) .'">';

		$args = wp_parse_args($this->field['args'], array());
		
		$tags = get_tags($args); 
		foreach ( $tags as $tag ) {
			$selected = (is_array($this->value) && in_array($tag->term_id, $this->value))?' selected="selected"':'';
			echo '<option value="'.esc_attr( $tag->term_id ).'"'.$selected.'>'.esc_html( $tag->name ).'</option>';
		}

		echo '</select>';

		echo (isset($this->field['desc']) && !empty($this->field['desc']))?'<br/><span class="description">'.esc_html( $this->field['desc'] ).'</span>':'';
		
	}//function
	
}//class
?>