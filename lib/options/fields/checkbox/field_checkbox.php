<?php
class Emarket_Options_checkbox extends Emarket_Options{
	
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
		
		$class = (isset($this->field['class']))?$this->field['class']:'';
		echo '<div class="emarketoption-checkbox '.esc_attr( $this->field['id'] ).'">';
		echo ($this->field['desc'] != '')?' <label>':'';
		
		echo '<input type="checkbox" id="'.esc_attr( $this->field['id'] ).'" name="'.$this->args['opt_name'].'['.$this->field['id'].']" value="1" class="'.$class.'" '.checked($this->value, '1', false).'/>';
		
		echo '<label for="'.esc_attr( $this->field['id'] ).'"></label></div>';
	}//function
	
}//class
?>