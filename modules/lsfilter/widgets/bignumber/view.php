<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<a href="<?php echo listview::querylink($query); ?>" title="Go to listview">
	<div class="<?php echo $state; ?> state-background state_summary-container">
		<?php if($display_explanation) { ?>
		<p class="explanation"><?php echo html::specialchars($display_explanation); ?></p>
		<?php } else { ?>
		<div class="state_summary-state">
			<span class="big-number"><?php echo html::specialchars($display_text); ?></span>
		</div>
		<?php } ?>
	</div>
</a>