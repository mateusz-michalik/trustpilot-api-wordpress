# TrustPilot API for WordPress

A plugin to integrate your WordPress site with the Trustpilot API. The plugin will give you

- A new custom post type called 'Reviews' where Trustpilot reviews are stored
- A new custom taxonomy to store Trustpilot review categories
- A wp-json endpoint to trigger manual API import of Trustpilot reviews

## Installation / Local Dev

The step below assume that you have already created a Trustpilot application over at https://businessapp.b2b.trustpilot.com/#/applications/ and have your API credentials ready to go.

1. Add the plugin to your WP plugins folder
2. Activate the plugin
3. Add your Trustpilot API credentials and hit 'save'
4. Ensure that your Trustpilot application has the following redirect URL specified: `YOUR_SITE_URL/wp-admin/admin.php?page=trustpilot-api-settings`
5. Once your credentials are stored, hit 'Connect to Trustpilot' under the 'Application OAuth' heading
6. This will take you to Trustpilot where you will have to login and authorize the app/plugin
7. Once this is done, an oauth token is saved and the plugin can make calls to the API to import reviews

## Importing Reviews

The plugin will automatically create a custom endpoint at `YOUR_SITE_URL/wp-json/trustpilot-api-wordpress/v1/import-reviews`.
Go to this URL in your browser, or set a CRON to hit this URL periodically. The endpoint will pull in 20 reviews at a time and is paginated.

## Resources

Below are some resources/docs that helped when building this plugin:

Trustpilot Applications: https://businessapp.b2b.trustpilot.com/#/applications/

Authentication Methods: https://developers.trustpilot.com/authentication

Business Unit ID Retrieval: https://developers.trustpilot.com/tutorials/how-to-find-your-business-unit-id

Business Unit API: https://developers.trustpilot.com/business-units-api#get-a-business-unit's-reviews

Business Units Reviews Retrieval: https://developers.trustpilot.com/tutorials/how-to-get-all-your-service-reviews