<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Record $record
 */
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5 text-center">
    <h2 class="display-3 fw-bold mb-3">Contact Us</h2>
    <p class="fs-4">We'd love to hear from you. Get in touch with any questions or feedback.</p>
    <?= $this->Form->create($record) ?>
    <div class="row">

        <!-- Left Column -->
        <div class="col-md-6">
            <div class="mb-3 text-start">
                <!-- First Name input -->>
                <?= $this->Form->control('first_name', [
                    'class' => 'form-control form-control-lg',
                    'label' => 'First Name',
                    'required' => true
                ]) ?>

                <!-- Mail Input -->
                <?= $this->Form->control('email', [
                    'class' => 'form-control form-control-lg',
                    'label' => 'Your Email',
                    'required' => true
                ]) ?>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
            <div class="mb-3 text-start">
                <!-- Last name input -->
                <?= $this->Form->control('last_name', [
                    'class' => 'form-control form-control-lg',
                    'label' => 'Last Name',
                    'required' => true
                ]) ?>

                <!-- Phone input -->
                <?= $this->Form->control('phone', [
                    'class' => 'form-control form-control-lg',
                    'label' => 'Your Phone Number',
                    'required' => true
                ]) ?>
            </div>
        </div>
    </div>

    <div class="mb-3 text-start">
        <!-- Subject input -->
        <?= $this->Form->control('subject', [
            'class' => 'form-control form-control-lg',
            'label' => 'Subject',
            'required' => true
        ]) ?>

        <!-- Message input -->
        <?= $this->Form->control('message', [
            'class' => 'form-control form-control-lg',
            'label' => 'Message',
            'required' => true
        ]) ?>
    </div>

    <div class="mt-4 d-flex justify-content-center gap-3">
        <?= $this->Form->button(__('CONTACT US')) ?>
    </div>
    <?= $this->Form->end() ?>
</div>

