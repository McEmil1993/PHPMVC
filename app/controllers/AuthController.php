<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Requests\LoginRequest;
use App\Requests\ValidationException;
use App\Resources\ApiResponse;
use App\Resources\UserResource;
use App\Services\AuthService;
use Bootstrap\Response;
use Bootstrap\Session;
use Bootstrap\View;
use Closure;
use InvalidArgumentException;
use PDO;
use Throwable;

final class AuthController
{
    private Closure $pdoFactory;

    /** @param callable():PDO $pdoFactory */
    public function __construct(callable $pdoFactory)
    {
        $this->pdoFactory = $pdoFactory instanceof Closure ? $pdoFactory : Closure::fromCallable($pdoFactory);
    }

    public function showLogin(): void
    {
        try {
            $user = Session::get('auth_user');
            if (is_array($user)) {
                header('Location: /home', true, 302);
                return;
            }

            View::render('auth/login', [
                'message' => null,
                'errors' => [],
                'old' => [],
            ]);
        } catch (Throwable $e) {
            Response::json(ApiResponse::error('Failed to load login page'), 500);
        }
    }

    public function login(): void
    {
        try {
            $payload = (new LoginRequest())->validate();

            $pdo = ($this->pdoFactory)();
            $authService = new AuthService($pdo);
            $result = $authService->attempt($payload['email'], $payload['password']);
            $user = $result['user'];

            if ($user === null) {
                $errors = [];
                $message = 'Login failed';

                if (($result['reason'] ?? null) === 'not_found') {
                    $message = 'Email not found';
                    $errors['email'] = $message;
                } elseif (($result['reason'] ?? null) === 'wrong_password') {
                    $message = 'Wrong password';
                    $errors['password'] = $message;
                }

                $accept = (string)($_SERVER['HTTP_ACCEPT'] ?? '');
                $wantsHtml = str_contains($accept, 'text/html');
                if ($wantsHtml) {
                    View::render('auth/login', [
                        'message' => $message,
                        'errors' => $errors,
                        'old' => [
                            'email' => $payload['email'],
                        ],
                    ]);
                    return;
                }

                Response::json(ApiResponse::error($message), 401);
                return;
            }

            Session::regenerate();
            Session::set('auth_user', [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);

            $nameParts = preg_split('/\s+/', trim($user->name)) ?: [];
            $first = (string)($nameParts[0] ?? $user->name);
            $last = (string)($nameParts[count($nameParts) - 1] ?? '');
            Session::set('account_id', $user->id);
            Session::set('firstname', $first);
            Session::set('lastname', $last === $first ? '' : $last);

            $accept = (string)($_SERVER['HTTP_ACCEPT'] ?? '');
            $wantsHtml = str_contains($accept, 'text/html');
            if ($wantsHtml) {
                header('Location: /home', true, 302);
                return;
            }

            Response::json(ApiResponse::success('Logged in', UserResource::make($user)));
        } catch (ValidationException $e) {
            $errors = $e->errors();

            $accept = (string)($_SERVER['HTTP_ACCEPT'] ?? '');
            $wantsHtml = str_contains($accept, 'text/html');
            if ($wantsHtml) {
                View::render('auth/login', [
                    'message' => 'Please check your inputs',
                    'errors' => $errors,
                    'old' => [
                        'email' => is_string($_POST['email'] ?? null) ? (string)$_POST['email'] : '',
                    ],
                ]);
                return;
            }

            Response::json(ApiResponse::error('Validation failed', ['errors' => $errors]), 422);
        } catch (InvalidArgumentException $e) {
            Response::json(ApiResponse::error($e->getMessage()), 422);
        } catch (Throwable $e) {
            $accept = (string)($_SERVER['HTTP_ACCEPT'] ?? '');
            $wantsHtml = str_contains($accept, 'text/html');
            if ($wantsHtml) {
                View::render('auth/login', [
                    'error' => 'Login failed. Please try again.',
                ]);
                return;
            }

            Response::json(ApiResponse::error('Login failed'), 500);
        }
    }

    public function logout(): void
    {
        try {
            Session::forget('auth_user');
            Session::forget('account_id');
            Session::forget('firstname');
            Session::forget('lastname');

            $accept = (string)($_SERVER['HTTP_ACCEPT'] ?? '');
            $wantsHtml = str_contains($accept, 'text/html');
            if ($wantsHtml) {
                header('Location: /login', true, 302);
                return;
            }

            Response::json(ApiResponse::success('Logged out', (object)[]));
        } catch (Throwable $e) {
            Response::json(ApiResponse::error('Logout failed'), 500);
        }
    }
}
