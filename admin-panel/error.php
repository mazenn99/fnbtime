<?php
if (count($error)): ?>
    <div class="alert container width-50 text-darker alert-danger" role="alert">
        <?php foreach ($error as $errors): ?>
            <p><?php echo $errors ?></p>
        <?php endforeach; ?>
    </div>
<?php
endif;
