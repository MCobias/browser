<?php

declare(strict_types=1);

namespace Brick\Browser\RequestHandler;

use Brick\Http\Request;
use Brick\Http\RequestHandler;
use Brick\Http\Response;

/**
 * Network implementation of a request handler, that talks to an HTTP server.
 */
class NetworkHandler implements RequestHandler
{
    /**
     * {@inheritdoc}
     */
    public function handle(Request $request) : Response
    {
        $request->setHeader('Connection', 'close');

        $path = $request->getHost();

        if ($request->isSecure()) {
            $path = 'ssl://' . $path;
        }

        $fp = fsockopen($path, $request->getPort());

        fwrite($fp, $request);

        $result = Response::parse(stream_get_contents($fp));

        fclose($fp);

        return $result;
    }
}
