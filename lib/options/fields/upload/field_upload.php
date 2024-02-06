<?php
class Emarket_Options_upload extends Emarket_Options{
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since Emarket_Options 1.0
	*/
	function __construct($field = array(), $value ='', $parent = ''){
		
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
		
		$class = (isset($this->field['class']))? esc_attr( $this->field['class'] ):'regular-text';
		
		
		echo '<input type="hidden" id="'.esc_attr( $this->field['id'] ).'" name="'.$this->args['opt_name'].'['.$this->field['id'].']" value="'.$this->value.'" class="'.$class.'" />';
			
		echo '<img class="emarket-opts-screenshot" id="emarket-opts-screenshot-'.$this->field['id'].'" src="'.esc_url( $this->value ).'"/>';
		
		if($this->value == ''){$remove = ' style="display:none;"';$upload = '';}else{$remove = '';$upload = ' style="display:none;"';}
		echo ' <a href="javascript:void(0);" class="sw-opts-upload button-secondary"'.$upload.' rel-id="'.$this->field['id'].'">'.esc_html__('Browse', 'emarket').'</a>';
		echo ' <a href="javascript:void(0);" class="sw-opts-upload-remove"'.$remove.' rel-id="#'.$this->field['id'].'">'.esc_html__('Remove Upload', 'emarket').'</a>';
		
		echo (isset($this->field['desc']) && !empty($this->field['desc']))?'<br/><br/><span class="description">'.esc_html( $this->field['desc'] ).'</span>':'';
		
	}//function
	
	
	
	/**
	 * Enqueue Function.
	 *
	 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
	 *
	 * @since Emarket_Options 1.0
	*/
	function enqueue(){
		wp_enqueue_media();
		wp_enqueue_script(
			'sw-menu-upload-js',
			EMARKET_URL.'/options/fields/upload/field_upload.js',
			time(),
			true
		);
		
		wp_localize_script('sw-menu-upload-js', 'sw_upload', array('url' => EMARKET_URL.'/admin/img/emarket.png'));
		
	}//function
	
}//class
?>