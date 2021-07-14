<?php if (! empty($errors)) : ?>
	<div class="errors" role="alert">
		<ul>
		<?php foreach ($errors as $error) : ?>
			<span class="bad_clr"><?= esc($error) ?></span><br />
		<?php endforeach ?>
		</ul>
	</div>
<?php endif ?>
