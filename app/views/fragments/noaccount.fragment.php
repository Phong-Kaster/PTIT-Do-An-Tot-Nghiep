<div class="no-data">
    <?php if ($AuthUser->get("settings.max_accounts") == -1 || $AuthUser->get("settings.max_accounts") > 0): ?>
        <p><?= __("You haven't add any Instagram account yet. Click the button below to add your first account.") ?></p>
        <a class="small button" href="<?= APPURL."/accounts/new" ?>">
            <span class="sli sli-user-follow"></span>
            <?= __("New Account") ?>
        </a>
    <?php else: ?>
        <p><?= __("You don't have any Instagram account.") ?></p>
    <?php endif; ?>
</div>