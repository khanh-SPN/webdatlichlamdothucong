<?php
declare(strict_types=1);

namespace App\Error;

use Cake\Core\Configure;
use Cake\Error\Renderer\WebExceptionRenderer;
use Cake\View\Exception\MissingTemplateException;
use Throwable;

class AppExceptionRenderer extends WebExceptionRenderer
{
    protected function getHttpCode(Throwable $exception): int
    {
        if ($exception instanceof MissingTemplateException) {
            return 404;
        }

        return parent::getHttpCode($exception);
    }

    protected function _template(Throwable $exception, string $method, int $code): string
    {
        if ($code === 403) {
            return $this->template = 'error403';
        }

        // Always render generic error templates to avoid leaking internal paths/DB info.
        // This is intentionally independent of Configure::read('debug')
        // so that invalid URLs never show framework debug pages.
        return $this->template = $code < 500 ? 'error400' : 'error500';
    }

    public function render(): \Psr\Http\Message\ResponseInterface
    {
        $previousDebug = (bool)Configure::read('debug');

        // Force non-debug rendering for all error pages.
        Configure::write('debug', false);

        try {
            return parent::render();
        } finally {
            Configure::write('debug', $previousDebug);
        }
    }
}
