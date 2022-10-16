<?php

namespace Orion\Larashared\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CommandController extends ResponseController
{
    /**
     * Optimize
     *
     * @param  string|null  $action
     * @return JsonResponse
     */
    public function optimize(?string $action = null): JsonResponse
    {
        $availables = ['clear'];

        if ($action && ! in_array($action, $availables, true)) {
            return $this->sendError('Failure.', ['error' => 'Action is not supported.', 'available-actions' => $availables]);
        }

        return $this->artisan('optimize'.($action ? (':'.$action) : null));
    }

    /**
     * Maintenance
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function maintenance(Request $request): JsonResponse
    {
        $status = $request->input('status');

        if ($status === 'up') {
            return $this->artisan('up');
        }

        $parameters = [];

        if ($request->has('secret')) {
            $parameters['--secret'] = $request->input('secret');
        }

        if ($request->has('refresh')) {
            $parameters['--refresh'] = $request->input('refresh');
        }

        if ($request->has('retry')) {
            $parameters['--retry'] = $request->input('retry');
        }

        return $this->artisan('down', $parameters);
    }

    /**
     * @param  Request  $request
     * @param  string|null  $action
     * @return JsonResponse
     */
    public function config(Request $request, ?string $action = null): JsonResponse
    {
        $availables = ['cache', 'clear'];

        if (! $action) {
            return $this->sendError('Failure.', ['error' => 'Please specify action.', 'available-actions' => $availables]);
        }

        if (! in_array($action, $availables, true)) {
            return $this->sendError('Failure.', ['error' => 'Action is not supported.', 'available-actions' => $availables]);
        }

        return $this->artisan('config:'.$action);
    }

    /**
     * @param  Request  $request
     * @param  string|null  $action
     * @return JsonResponse
     */
    public function routeCommand(Request $request, ?string $action = null): JsonResponse
    {
        $availables = ['cache', 'clear'];

        if (! $action) {
            return $this->sendError('Failure.', ['error' => 'Please specify action.', 'available-actions' => $availables]);
        }

        if (! in_array($action, $availables, true)) {
            return $this->sendError('Failure.', ['error' => 'Action is not supported.', 'available-actions' => $availables]);
        }

        return $this->artisan('route:'.$action);
    }

    /**
     * @param  Request  $request
     * @param  string|null  $action
     * @return JsonResponse
     */
    public function view(Request $request, ?string $action = null): JsonResponse
    {
        $availables = ['cache', 'clear'];

        if (! $action) {
            return $this->sendError('Failure.', ['error' => 'Please specify action.', 'available-actions' => $availables]);
        }

        if (! in_array($action, $availables, true)) {
            return $this->sendError('Failure.', ['error' => 'Action is not supported.', 'available-actions' => $availables]);
        }

        return $this->artisan('view:'.$action);
    }

    /**
     * @param  Request  $request
     * @param  string|null  $action
     * @return JsonResponse
     */
    public function migrate(Request $request, ?string $action = null): JsonResponse
    {
        $availables = ['fresh', 'refresh', 'rollback', 'reset'];

        if ($action && ! in_array($action, $availables, true)) {
            return $this->sendError('Failure.', ['error' => 'Action is not supported.', 'available-actions' => $availables]);
        }

        $parameters = [];

        if (! $action && $request->has('pretend')) {
            $parameters['--pretend'] = (bool) $request->input('pretend');
        }

        if ($request->has('step')) {
            $parameters['--step'] = $request->input('pretend');
        }

        if ($request->has('seed')) {
            $parameters['--seed'] = (bool) $request->input('seed');
        }

        if ($request->has('force')) {
            $parameters['--force'] = (bool) $request->input('force');
        }

        return $this->artisan('migrate'.($action ? (':'.$action) : null), $parameters);
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function seed(Request $request): JsonResponse
    {
        $parameters = [];

        if ($request->has('class')) {
            $parameters['--class'] = $request->input('class');
        }

        if ($request->has('force')) {
            $parameters['--force'] = (bool) $request->input('force');
        }

        return $this->artisan('db:seed', $parameters);
    }

    private function artisan(string $command, array $parameters = []): JsonResponse
    {
        $exitCode = Artisan::call($command, $parameters);
        $output = preg_replace('/\s+/', ' ', trim(Artisan::output()));

        if ($exitCode !== 0) {
            return $this->sendError('Failure.', ['error' => $output]);
        }

        return $this->sendSuccess('Success', empty($output) ? 'Command successful.' : $output);
    }
}
