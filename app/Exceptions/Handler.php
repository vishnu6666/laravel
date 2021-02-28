<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $path = explode('/', $request->path());
        $isAjax = !empty($path[0]) && $path[0] == 'v1' ? true : false;

        if ($exception instanceof ModelNotFoundException) {

            

            if ($isAjax) {
                return response()->json([
                    'status' => 0,
                    'result' => new \stdClass(),
                    'message' => 'Not Found',
                ], 404);
            }
        }
        
        if($exception instanceof AuthenticationException)
        {
            
            if (empty($request->all()) && $path[1] != 'v1') 
            {
                if($request->is('superadmin/*'))
                {
                    return redirect(route('adminLogin'));
                }

                return redirect(route('userLogin'));
            }

            $message = empty($request->version) ? 'Kindly update your app' : 'Your session has been expired';

            return response()->json([
                'status' => 0,
                'result' => new \stdClass(),
                'message' => $message,
            ], 401);
            
        }
        return parent::render($request, $exception);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {

        $path = explode('/', $request->path());
        $isAjax = !empty($path[0]) && $path[0] == 'api' ? true : false;

        if ($e->response) {
            return $e->response;
        }

        return ($request->expectsJson() || $isAjax)
            ? $this->invalidJson($request, $e)
            : $this->invalid($request, $e);
    }

    /**
     * Convert a validation exception into a JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        
        $errors = collect($exception->errors())->first();
        
        $message = '';

        if (!empty($errors[0])) {
            $message = $errors[0];
        }
        return response()->json([
            'status' => 0,
            'result' => new \stdClass(),
            'message' => $message,
        ], 200);
    }
}
