<?php

namespace RJPlugins;

use GDN;
use Gdn_OAuth2;
use Gdn_AuthenticationProviderModel;

class SpotifyPlugin extends Gdn_OAuth2 {
    /** Provider key */
    const PROVIDER_KEY = 'spotify';
    /** Authorization endpoint */
    const AUTHORIZE_URL = 'https://accounts.spotify.com/authorize';
    /** Token URl */
    const TOKEN_URL = 'https://accounts.spotify.com/api/token';
    /** Profile endpoint */
    const PROFILE_URL = 'https://api.spotify.com/v1/me';

    /**
     * Set the provider key for saving Spotify settings.
     *
     * Settings are saved in the GDN_UserAuthenticationProvider table.
     *
     * @return: void.
     */
    public function __construct() {
        $this->setProviderKey(self::PROVIDER_KEY);
    }

    /**
     * Save Spotify info into AuthenticationProvider table.
     *
     * @return void.
     */
    public function structure() {
        $authenticationProviderModel = new Gdn_AuthenticationProviderModel();
        $provider = [
            'AuthenticationKey' => self::PROVIDER_KEY,
            'AuthenticationSchemeAlias' => self::PROVIDER_KEY,
            'Name' => ucfirst(self::PROVIDER_KEY),
            'AcceptedScope' => 'user-read-private user-read-email',
            'ProfileKeyEmail' => 'email',
            'ProfileKeyPhoto' => 'images', // Spotify returns an array, while a string is needed!
            'ProfileKeyName' => 'display_name',
            'ProfileKeyFullName' => 'display_name',
            'ProfileKeyUniqueID' => 'id'
        ];
        $authenticationProviderModel->save($provider);
    }

    /**
     * Settings endpoint.
     *
     * @param SettingsController $sender Instance of the calling class.
     * @param mixed $args Event arguments.
     *
     * @return void.
     */
    public function settingsEndpoint($sender, $args) {
        $description = Gdn::translate('rj-spotify-instructions');
        $redirectUrl = Gdn::request()->url('/entry/'. self::PROVIDER_KEY, true, true);
        // Set title, description and url endpoints.
        // Urls are set in hidden fields. Parent method requires them.
        $sender->setData([
            'Title' => Gdn::translate('Spotify Settings'),
            'Description' => $description,
            'RedirectUrl' => $redirectUrl,
            'AuthorizeUrl' => self::AUTHORIZE_URL,
            'TokenUrl' => self::TOKEN_URL,
            'ProfileUrl' => self::PROFILE_URL
        ]);
        // Set custom view.
        $this->settingsView = 'plugins/rj-spotify';

        parent::settingsEndpoint($sender, $args);
    }

    /**
     * Prevent showing/requiring additional fields in the config.
     *
     * Spotify specific OAuth2 fields are already set in the structure() method.
     *
     * @return array Empty array of additional config fields.
     */
    public function getSettingsFormFields() {
        return [];
    }

    /**
     * Add spotify css to pages.
     *
     * @param AssetModel $sender Instance of the calling class.
     * @param mixed $args Event arguments.
     *
     * @return void.
     */
    public function assetModel_styleCss_handler($sender, $args) {
        $sender->addCssFile('spotify.css', 'plugins/rj-spotify');
    }

    /**
     * Suggest Spotify FullName as UserName and handle Spotify images field.
     *
     * Prefill user name field with Spotifys FullName.
     * Spotify returns an "images" array, but Vanilla expects only an image url
     * of type string. Conversion is done here.
     *
     * @param EntryController $sender Instance of the calling class.
     * @param mixed $args Event arguments.
     *
     * @return void.
     */
    public function entryController_afterConnectData_handler($sender, $args) {
        // Set UserName.
        if (
            $sender->Form->getValue('ConnectName') == false &&
            $sender->Form->getFormValue('ConnectName', false) == false
        ) {
            $sender->Form->setValue('ConnectName', $args['Profile']['FullName']);
        }
        // Set Photo.
        if (count($args['Profile']['Photo']) > 0) {
            $sender->Form->setFormValue(
                'Photo',
                $args['Profile']['Photo'][0]['url']
            );
        }
    }
}
