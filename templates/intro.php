<div class="wrap" style="direction:ltr">
    <div style="padding:20px;border:1px solid #dcdcdc;font-size:1.2em;color:#d26800;">
    <h1>Delete Old Revisions</h1>

        <p>
            This plugin will automatically delete old revisions before <strong> <?= $this->before; ?> </strong>.
            <br>
            The plugin will delete <strong> <?= $this->number; ?></strong> revisions each time.
        </p>
        <div style="color:red;font-weight:bold;font-size:2em;text-align:center;padding:20px 0;">
            Don't start unless you know what you're doing.
        </div>
        <a class="button button-primary" href="<?= home_url( 'wp-admin/admin.php?page=dmi-delete-revisions&start_del=true'); ?>">Start Now</a>
    </div>
</div>