<?php

namespace Vormkracht10\Seo\Checks\Meta;

use Closure;
use Illuminate\Http\Client\Response;
use Vormkracht10\Seo\Checks\Meta\MetaCheck;
use Vormkracht10\Seo\Checks\Traits\FormatRequest;

class TitleLengthCheck implements MetaCheck
{
    use FormatRequest;

    public string $title = 'Check if the title is not longer than 60 characters';

    public string $priority = 'medium';

    public int $timeToFix = 1;

    public int $scoreWeight = 5;

    public bool $checkSuccessful = false;

    public function handle(array $request, Closure $next): array
    {
        $title = $this->getMetaContent($request[0]);

        if (! $title) {
            return $next($this->formatRequest($request));
        }

        if (strlen($title) <= 60) {
            $this->checkSuccessful = true;
        }

        return $next($this->formatRequest($request));
    }

    public function getMetaContent(Response $response): string|null
    {
        $response = $response->body();
        preg_match('/<title>(.*)<\/title>/', $response, $matches);

        return $matches[1] ?? null;
    }
}