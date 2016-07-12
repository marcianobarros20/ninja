<?php defined('SYSPATH') OR die('No direct access allowed.');
/* @var $form Form_Model */

echo '<form class="nj-form" action="'.html::specialchars($action).'" method="POST">';
echo '<input type="hidden" name="csrf_token" value="'.Session::instance()->get(Kohana::config('csrf.csrf_token')).'"/>';
foreach($form->get_fields() as $field) {
	$form->get_field_view($field)->render(true);
}
echo '<fieldset>';
echo '<input class="info state-background" type="submit" value="Save">';
echo '<input class="info state-background cancel" type="reset" value="Cancel">';
echo '</fieldset>';
echo '</form>';