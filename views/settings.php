<?php defined('APPLICATION') or die;
$formData = $this->data('_Form');
?>
<h1><?= $this->data('Title') ?></h1>

<div class="padded alert alert-info">
    <?= sprintf($this->data('Description'), $this->data('RedirectUrl')) ?>
</div>
<?= $this->Form->open(), $this->Form->errors() ?>
<ul>
    <li class="form-group">
        <div class="label-wrap">
            <?= $this->Form->label($formData['AssociationKey']['LabelCode'], 'AssociationKey') ?>
            <div class="description info"><?= Gdn::translate($formData['AssociationKey']['Description']) ?></div>
        </div>
        <?= $this->Form->inputWrap('AssociationKey') ?>
    </li>
    <li class="form-group">
        <div class="label-wrap">
            <?= $this->Form->label($formData['AssociationSecret']['LabelCode'], 'AssociationSecret') ?>
            <div class="description info"><?= Gdn::translate($formData['AssociationSecret']['Description']) ?></div>
        </div>
        <?= $this->Form->inputWrap('AssociationSecret') ?>
    </li>
    <li class="Hidden">
        <?= $this->Form->textBox('AuthorizeUrl', ['value' => $this->data('AuthorizeUrl')]) ?>
    </li>
    <li class="Hidden">
        <?= $this->Form->textBox('TokenUrl', ['value' => $this->data('TokenUrl')]) ?>
    </li>
    <li class="Hidden">
        <?= $this->Form->textBox('ProfileUrl', ['value' => $this->data('ProfileUrl')]) ?>
    </li>
</ul>
<?= $this->Form->close('Save') ?>