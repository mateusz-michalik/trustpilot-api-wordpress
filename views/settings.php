<div class="wrap">
    <h1>Trustpilot API for WordPress: Settings</h1>
    <hr>

    <h2>API Credentials</h2>
    <p>Enter your API key in the details below, you will not be able to use OAUTH to connect Trustpilot to
    this site without a valid API key</p>
    <p>You can retrieve your API key from here: 
    <a href="https://businessapp.b2b.trustpilot.com/#/applications/" target="_blank">Trustpilot Applications</a></p>
    <p>For the 'redirect URLs' section of your application in Trustpilot, make sure to add the following to a new line:</p>
    <p><?= $this->get_plugin_settings_url() ?></p>

    <form method="post" action="options.php">
        <?php
        settings_fields('trustpilot-api-settings');
        do_settings_sections('trustpilot-api-settings');
        ?>
        <p>
            <label>API Key:</label>
            <input type="text" name="trustpilot_api_key" value="<?=esc_attr(get_option('trustpilot_api_key'))?>">
        </p>
        <p>
            <label>API Secret:</label>
            <input type="text" name="trustpilot_api_secret" value="<?=esc_attr(get_option('trustpilot_api_secret'))?>">
        </p>
        <?php submit_button()?>
    </form>
    <hr>

    <h2>Application OAUTH</h2>
    <?php if (!esc_attr(get_option('trustpilot_api_key'))): ?>
        <p>You must enter your API credentials above before you can connect to the Trustpilot API</p>
    <?php else: ?>

        <?php if (!esc_attr(get_option('trustpilot_api_access_token'))): ?>
            <p>You have not connected and authorized this application with your Trustpilot account</p>
            <p>
                <a href="<?=$this->get_oauth_url()?>" class="button button-primary">Connect to Trustpilot</a>
            </p>
        <?php else: ?>
            <p>You have successfully connected with the TrustPilot API for your app.</p>
        <?php endif?>
    <?php endif?>
    <br>
    <hr>
</div>