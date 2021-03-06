<?php

/*
 * View - generuje treść podstrony na podstawie zebranych danych
 */
class Style_View
{
	/*
	 * Formularz
	 */
	 
	public function ShowForm($row, $failed)
	{
		$contents = NULL;

		if (is_array($row))
		{
			$contents = $row['contents'];
		}
		
		// Form Generator:
		
		require_once LIB_DIR . 'gener' . '/' . 'form.php';
		
		$main_form = new FormBuilder();
		
		$form_title = 'Wygląd';
		$form_image = 'img/32x32/picture_edit.png';
		$form_width = '600px';
		$form_widths = Array('10%', '90%');
		
		$main_form->init($form_title, $form_image, $form_width, $form_widths);
		
		// action:
		
		$form_action = 'index.php?route=' . MODULE_NAME;
		
		$main_form->set_action($form_action);
		
		// failed:
		
		$main_form->set_failed($failed);

		// inputs:
		
		$form_data = Array(
						Array('type' => 'textarea', 'id' => 'contents', 'name' => 'contents', 'value' => $contents, 'style' => 'height: 400px; width: 96%;')
						);
		$form_input = Array('caption' => 'Treść', 'data' => $form_data);
		$form_inputs[] = $form_input;
		
		$main_form->set_inputs($form_inputs);

		// buttons:

		$form_buttons = Array();
		
		$form_data = Array('type' => 'submit', 'id' => 'restore_button', 'name' => 'restore_button', 'value' => 'Przywróć domyślne', 'style' => 'width: 150px;');
		$form_buttons[] = $form_data;
		
		$main_form->set_buttons($form_buttons, 'left');

		$form_buttons = Array();

		$form_data = Array('type' => 'submit', 'id' => 'save_button', 'name' => 'save_button', 'value' => 'Zapisz', 'style' => 'width: 80px;');
		$form_buttons[] = $form_data;
		$form_data = Array('type' => 'submit', 'id' => 'cancel_button', 'name' => 'cancel_button', 'value' => 'Anuluj', 'style' => 'width: 80px;');
		$form_buttons[] = $form_data;
		
		$main_form->set_buttons($form_buttons, 'right');

		// render:
		
		$site_content = $main_form->build_form();
		
		// Form Generator.
		
		return $site_content;
	}
}

?>
