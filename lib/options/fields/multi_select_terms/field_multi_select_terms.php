<?php
class Emarket_Options_multi_select_terms extends Emarket_Options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since Emarket_Options 1.0
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
	 * @since Emarket_Options 1.0
	*/
	function render(){
		
		$class = (isset($this->field['class']))? 'class="emarket-single-select '.esc_attr( $this->field['class'] ).'" ': 'class="emarket-single-select"';
		$placehold = ( isset( $this->field['placehold'] ) )? $this->field['placehold'] : esc_html__( 'Please select', 'emarket' );
		echo '<select id="'.esc_attr( $this->field['id'] ).'" name="'.$this->args['opt_name'].'['.$this->field['id'].'][]" '.$class.'multiple="multiple" data-placehold="'. esc_attr( $placehold ) .'">';
			$categories = get_terms( $this->field['std'], array( 'orderby' => 'id', 'hide_empty' => 'false' ) );  
			foreach($categories as $k => $term){
				$selected = (is_array($this->value) && in_array($term -> term_id, $this->value))?' selected="selected"':'';
				
				echo '<option value="'.esc_attr( $term -> term_id ).'"'.$selected.'>'.esc_html( $term -> name ).'</option>';
				
			}//foreach

		echo '</select>';

		echo (isset($this->field['desc']) && !empty($this->field['desc']))?'<br/><span class="description">'.esc_html( $this->field['desc'] ).'</span>':'';
		
	}//function
	
}//class
?>