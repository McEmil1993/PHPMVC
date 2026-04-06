<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Requests\CreateUserRequest;
use App\Requests\UpdateUserRequest;
use App\Requests\ValidationException;
use App\Resources\ApiResponse;
use App\Resources\UserResource;
use App\Services\UserService;
use Bootstrap\Response;
use InvalidArgumentException;
use Throwable;

final class UserController
{
    public function __construct(private UserService $userService)
    {
    }

    public function index(): void
    {
        try {
            $users = $this->userService->list();
            Response::json(ApiResponse::success('Users fetched', UserResource::collection($users)));
        } catch (Throwable $e) {
            Response::json(ApiResponse::error('Failed to fetch users'), 500);
        }
    }

    public function store(): void
    {
        try {
            $payload = (new CreateUserRequest())->validate();
            $user = $this->userService->create($payload);
            Response::json(ApiResponse::success('User created', UserResource::make($user)), 201);
        } catch (ValidationException $e) {
            Response::json(ApiResponse::error('Validation failed', [
                'errors' => $e->errors(),
            ]), 422);
        } catch (InvalidArgumentException $e) {
            Response::json(ApiResponse::error($e->getMessage()), 422);
        } catch (Throwable $e) {
            Response::json(ApiResponse::error('Failed to create user'), 500);
        }
    }

    public function update(string $id): void
    {
        try {
            $userId = (int)$id;
            if ($userId <= 0) {
                throw new InvalidArgumentException('Invalid user id');
            }

            $payload = (new UpdateUserRequest())->validate();
            $user = $this->userService->update($userId, $payload);

            if ($user === null) {
                Response::json(ApiResponse::error('User not found'), 404);
                return;
            }

            Response::json(ApiResponse::success('User updated', UserResource::make($user)));
        } catch (ValidationException $e) {
            Response::json(ApiResponse::error('Validation failed', [
                'errors' => $e->errors(),
            ]), 422);
        } catch (InvalidArgumentException $e) {
            Response::json(ApiResponse::error($e->getMessage()), 422);
        } catch (Throwable $e) {
            Response::json(ApiResponse::error('Failed to update user'), 500);
        }
    }

    public function destroy(string $id): void
    {
        try {
            $userId = (int)$id;
            if ($userId <= 0) {
                throw new InvalidArgumentException('Invalid user id');
            }

            $deleted = $this->userService->delete($userId);
            if (!$deleted) {
                Response::json(ApiResponse::error('User not found'), 404);
                return;
            }

            Response::json(ApiResponse::success('User deleted', (object)[]));
        } catch (InvalidArgumentException $e) {
            Response::json(ApiResponse::error($e->getMessage()), 422);
        } catch (Throwable $e) {
            Response::json(ApiResponse::error('Failed to delete user'), 500);
        }
    }
}
