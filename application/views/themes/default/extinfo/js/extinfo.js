$(document).ready(function() {
	$('.extinfo_contactgroup').each(function() {
		$(this).bind('click', function() {
			var the_id = $(this).attr('id');
			the_id = the_id.replace('extinfo_contactgroup_', '');
			$('#extinfo_contacts_' + the_id).toggle();
		});
	});

	setTimeout('hide_del_msg()', 3000);

	$('.deletecommentbox_host').hide();
	$('#selectall_host').hide();
	$('.td_host_checkbox').hide();
	$('.submithost').hide();

	$('.deletecommentbox_service').hide();
	$('#selectall_service').hide();
	$('.td_service_checkbox').hide();
	$('.submitservice').hide();

	// restore left border for first cell of each row
	$('table').find('tr:eq(0) th:eq(0)').css('border-left', '1px solid #dcdccd');
	$('table').find('tr:eq(0) th:eq(1)').css('border-left', '1px solid #dcdccd');
	$('table').find('tr td:nth-child(2)').css('border-left', '1px solid #dcdccd');

	$('#del_host_downtime_form').bind('submit', function() {
		//return false;
	});

	$('#del_submithost_svc').click(function() {

		$('.deletecommentbox_host').each(function() {
			if ($(this).attr('checked')) {
				var hostname = $(this).closest('tr').find('td:nth-child(2)').text();
				$('#scheduled_service_downtime').find('tr td:nth-child(2)').each(function() {
					if ($(this).text() == hostname) {
						$(this).closest('tr').find('td:nth-child(1) input[type="checkbox"]').attr('checked', true);
					}
				});
			}

		});

	});

	// refresh helper code
	var old_refresh = 0;
	var refresh_is_paused = false;
	var host_hidden = true;
	$('#select_multiple_delete_host').click(function() {
		if (!refresh_is_paused) {
			if ($('.td_host_checkbox').is(':visible') || $('.td_service_checkbox').is(':visible')) {
				// pausing and un-pausing refresh might be
				// irritating for users that already has selected
				// to pause refresh

				// save previous refresh rate
				// to be able to restore it later
				old_refresh = current_interval;
				$('#ninja_refresh_lable').css('font-weight', 'bold');
				ninja_refresh(0);
				$("#ninja_refresh_control").attr('checked', true);
			} else {
				// restore previous refresh rate
				ninja_refresh(old_refresh);
				$("#ninja_refresh_control").attr('checked', false);
				$('#ninja_refresh_lable').css('font-weight', '');
			}
		}

		if (!host_hidden) {
			$('.deletecommentbox_host').hide();
			$('.td_host_checkbox').hide();
			$('#selectall_host').hide();
			$('.submithost').hide();

			// uncheck all host checkboxes
			$('.deletecommentbox_host').attr('checked', false);
			$('.selectall_host').attr('checked', false);
			host_hidden = true;
		} else {
			$('.deletecommentbox_host').show();
			$('.td_host_checkbox').show();
			$('#selectall_host').show();
			$('.submithost').show();
			host_hidden = false;
		}
		return false;
	});

	var svc_hidden = true;
	$('#select_multiple_delete_service').click(function() {
		if (!refresh_is_paused) {
			if ($('.td_service_checkbox').is(':visible') || $('.td_host_checkbox').is(':visible')) {
				// pausing and un-pausing refresh might be
				// irritating for users that already has selected
				// to pause refresh

				// save previous refresh rate
				// to be able to restore it later
				old_refresh = current_interval;
				$('#ninja_refresh_lable').css('font-weight', 'bold');
				ninja_refresh(0);
				$("#ninja_refresh_control").attr('checked', true);
			} else {
				// restore previous refresh rate
				ninja_refresh(old_refresh);
				$("#ninja_refresh_control").attr('checked', false);
				$('#ninja_refresh_lable').css('font-weight', '');
			}
		}

		if (!svc_hidden) {
			$('.deletecommentbox_service').hide();
			$('.td_service_checkbox').hide();
			$('#selectall_service').hide();
			$('.submitservice').hide();

			// uncheck all service checkboxes
			$('.deletecommentbox_service').attr('checked', false);
			$('.selectall_service').attr('checked', false);

			svc_hidden = true;
		} else {
			$('.deletecommentbox_service').show();
			$('.td_service_checkbox').show();
			$('#selectall_service').show();
			$('.submitservice').show();
			svc_hidden = false;
		}
		return false;
	});

	$('#selectall_host').change(function() {
		$('.deletecommentbox_host').each(function() {
			$(this).attr('checked', $('#selectall_host').attr('checked'));
		});
	});
	$('#selectall_service').change(function() {
		$('.deletecommentbox_service').each(function() {
			$(this).attr('checked', $('#selectall_service').attr('checked'));
		});
	});

	// check that user selected any checkboxes before submitting
	$('#del_submithost').click(function() {
		if (!$('.deletecommentbox_host').filter(':checked').length) {
			$('.host_feedback').text('   Nothing selected...');
			setTimeout(function() {$('.host_feedback').hide();}, 5000);
			return false;
		} else {
			$('.host_feedback').text('');
		}
	});

	$('#del_submitservice').click(function() {
		if (!$('.deletecommentbox_service').filter(':checked').length) {
			$('.service_feedback').text('   Nothing selected...');
			setTimeout(function() {$('.service_feedback').hide();}, 5000);
			return false;
		} else {
			$('.service_feedback').text('');
		}
	});
});

function hide_del_msg() {
	$('#comment_del_msg').hide('slow');
}