<?php

namespace joriswvanrijn\EnvironmentLabel;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Response as IlluminateResponse;

class EnvironmentLabel
{
    /**
     * @param \Illuminate\Http\Request $request A Request instance
     * @param \Symfony\Component\HttpFoundation\Response $response A Response instance
     */
    public function modifyResponse($request, $response)
    {
        // Skip non-html requests
        if (($response->headers->has('Content-Type') && strpos($response->headers->get('Content-Type'), 'html') === false)
            || $request->getRequestFormat() !== 'html'
            || stripos($response->headers->get('Content-Disposition'), 'attachment;') !== false
        ) {
            return;
        }

        $this->injectEnvironmentbar($request, $response);
    }

    private function injectEnvironmentbar($request, $response)
    {
        $content = $response->getContent();

        $env = [
            'show' > true,
            'name' => App::environment(),
            'text_color' => '#333',
            'background_color' => '#808e9b',
        ];

        // See if we have display config stored for the found env
        $environments = config('environment-label.environments');
        if (array_key_exists($k = strtolower(App::environment()), $environments)) {
            $env = $environments[$k];
        }

        // Stop if we must not show the bar
        if (!$env['show']) {
            return;
        }

        $badge = View::make('environment-label::badge', array_merge($env, [
            'label' => config('environment-label.label')
        ]))->render();

        // Try to put the widget at the end, directly before the </body>
        $pos = strripos($content, '</body>');
        if (false !== $pos) {
            $content = substr($content, 0, $pos) . $badge . substr($content, $pos);
        } else {
            $content = $content . $badge;
        }

        $original = null;
        if ($response instanceof IlluminateResponse && $response->getOriginalContent()) {
            $original = $response->getOriginalContent();
        }

        $response->setContent($content);

        // Restore original response (eg. the View or Ajax data)
        if ($original) {
            $response->original = $original;
        }
    }
}
