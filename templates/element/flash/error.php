<?php
/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
echo $this->element('flash/toast', [
    'variant' => 'error',
    'message' => $message,
    'params' => $params,
]);
