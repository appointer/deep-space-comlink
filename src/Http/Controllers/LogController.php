<?php

namespace Appointer\DeepSpaceComlink\Http\Controllers;

use Illuminate\Contracts\Logging\Log;
use Illuminate\Http\Request;

class LogController
{
    /**
     * Use this endpoint to help you debug your web service implementation.
     * The logs contain a description of the error in a human-readable format.
     *
     * @param $version
     * @param Request $request
     * @param Log $logger
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store($version, Request $request, Log $logger)
    {
        $logger->error('deep-space-comlink logged a Safari push error.', [
            'logs' => $request->all(),
            'version' => $version
        ]);

        // Return with an empty OK response.
        return response('');
    }
}