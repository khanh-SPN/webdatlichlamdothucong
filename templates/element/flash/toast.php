<?php
/**
 * Shared toast shell for flash messages.
 *
 * @var \App\View\AppView $this
 * @var string $variant success|error|warning|info|default
 * @var string $message
 * @var array $params
 */

$variant = $variant ?? 'default';
$escaped = !isset($params['escape']) || $params['escape'] !== false;
$body = $escaped ? h($message) : $message;

$subtitle = $params['subtitle'] ?? null;
if ($subtitle !== null && $escaped) {
    $subtitle = h($subtitle);
}

$actionUrl = isset($params['actionUrl']) ? (string)$params['actionUrl'] : null;
$actionLabel = $params['actionLabel'] ?? null;
if ($actionLabel !== null && $escaped) {
    $actionLabel = h($actionLabel);
}

$extraClass = trim((string)($params['class'] ?? ''));
$role = in_array($variant, ['error', 'warning'], true) ? 'alert' : 'status';

$icons = [
    'success' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    'error' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    'warning' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
    'info' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    'default' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
];

$icon = $icons[$variant] ?? $icons['default'];

$rootClass = 'flash-toast flash-toast--' . $variant;
if ($extraClass !== '') {
    $rootClass .= ' ' . h($extraClass);
}
?>
<div class="<?= $rootClass ?>" data-flash-toast role="<?= h($role) ?>">
    <span class="flash-toast__icon"><?= $icon ?></span>
    <div class="flash-toast__content">
        <p class="flash-toast__text"><?= $body ?></p>
        <?php if ($subtitle !== null && $subtitle !== '') : ?>
            <p class="flash-toast__subtitle"><?= $subtitle ?></p>
        <?php endif; ?>
        <?php if ($actionUrl !== null && $actionUrl !== '' && $actionLabel !== null && $actionLabel !== '') : ?>
            <a href="<?= h($actionUrl) ?>" class="flash-toast__action"><?= $actionLabel ?></a>
        <?php endif; ?>
    </div>
    <button type="button" class="flash-toast__close" aria-label="Dismiss notification">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>
