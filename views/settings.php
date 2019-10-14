<?php defined('APPLICATION') or die;
// Get form data.
$formData = $this->data('_Form');
// Remove fields that need not be configured by the user.
unset($formData['AuthorizeUrl']);
unset($formData['TokenUrl']);
unset($formData['ProfileUrl']);
unset($formData['BearerToken']);
// Rephrase form element text.
$formData['IsDefault']['Description'] = $formData['IsDefault']['LabelCode'];
$formData['IsDefault']['LabelCode'] = 'Default Signin Method';
?>
<h1><?= $this->data('Title') ?></h1>
<div class="padded alert alert-info">
    <?= sprintf($this->data('Description'), $this->data('RedirectUrl')) ?>
</div>
<?= $this->Form->open() ?>
<?= $this->Form->errors() ?>
<?= $this->Form->simple($formData) ?>
<p class="Hidden">
    <?= $this->Form->textBox('AuthorizeUrl', ['value' => $this->data('AuthorizeUrl')]) ?>
    <?= $this->Form->textBox('TokenUrl', ['value' => $this->data('TokenUrl')]) ?>
    <?= $this->Form->textBox('ProfileUrl', ['value' => $this->data('ProfileUrl')]) ?>
</p>
<?= $this->Form->close('Save') ?>