<?php

namespace RJPlugins;

use GDN;
use Gdn_OAuth2;

class SpotifyPlugin extends Gdn_OAuth2 {
    /**
     * Set the key for saving OAuth settings in GDN_UserAuthenticationProvider.
     *
     * @return: void.
     */
    public function __construct() {
        $this->setProviderKey('spotify');
    }

    /**
     * Settings endpoint
     * @param  [type] $sender [description]
     * @param  [type] $args   [description]
     * @return [type]         [description]
     */
    public function settingsEndpoint($sender, $args) {
        $sender->title('Spotify Settings');
        $sender->settingsView = 'plugins/rj-spotify';
        parent::settingsEndpoint($sender, $args);
    }

    public function getSettingsFormFields() {
        $formFields = parent::getSettingsFormFields();
        $formFields['RedirectUrl'] = [
            'LabelCode' => 'Redirect Url',
            'Description' => 'Enter the Url users should be redirected after logging in.'
        ];
        return $formFields;
    }

    public function assetModel_styleCss_handler($sender, $args) {
        $sender->addCssFile('spotify.css', 'plugins/rj-spotify');
    }

    public function gdn_oAuth2_afterConnection_handler($sender, $args) {
        if ($args['Provider'] !== 'spotify') {
            return;
        }
        // $args['User'];
    }

    /**
     * Set Spotify FullName as UserName and Photo.
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
