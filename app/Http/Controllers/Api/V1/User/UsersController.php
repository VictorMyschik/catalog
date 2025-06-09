<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\User;

use App\DTO\UserProfileDTO;
use App\Http\Controllers\Api\V1\APIController;
use App\Http\Controllers\Api\V1\Response\ResponseFactory;
use App\Http\Controllers\Api\V1\User\Request\UpdateProfileRequest;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class UsersController extends ApiController
{
    public function __construct(
        private readonly UserService       $userService,
        protected readonly ResponseFactory $response,
    ) {}

    #[OA\Get(
        path: "/api/v1/user",
        summary: "Получить информацию о текущем пользователе",
        security: [["bearerAuth" => []]],
        tags: ["Пользователи. Инфо"],
        parameters: [
            new OA\Parameter(ref: "#/components/parameters/AcceptHeader"),
            new OA\Parameter(ref: "#/components/parameters/XRequestedWithHeader"),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful response",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "ok"),
                        new OA\Property(property: "content", type: "array", items: new OA\Items(
                            ref: "#/components/schemas/UserProfileResponse"
                        )),
                    ],
                    type: "object"
                )
            ),
            new OA\Response(response: 401, description: "Unauthorized", content: new OA\JsonContent(ref: "#/components/schemas/AuthError")),
        ]
    )]
    public function profile(Request $request): JsonResponse
    {
        return $this->apiResponse($this->response->getUserResponse($request->user()));
    }

    #[OA\Post(
        path: "/api/v1/user/profile",
        summary: "Обновить информацию о пользователе",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/UpdateProfileRequest")
        ),
        tags: ["Пользователи. Инфо"],
        parameters: [
            new OA\Parameter(ref: "#/components/parameters/AcceptHeader"),
            new OA\Parameter(ref: "#/components/parameters/XRequestedWithHeader"),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Profile updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "ok"),
                        new OA\Property(property: "content", ref: "#/components/schemas/UserProfileResponse", type: "object"),
                    ],
                    type: "object"
                )
            ),
            new OA\Response(response: 422, description: "Bad Request", content: new OA\JsonContent(ref: "#/components/schemas/ValidationError")),
            new OA\Response(response: 401, description: "Unauthorized", content: new OA\JsonContent(ref: "#/components/schemas/AuthError")),
        ]
    )]
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $dto = new UserProfileDTO(
            email: $request->getEmail(),
            name: $request->getName(),
        );

        $updatedUser = $this->userService->update($dto, $request->user());

        return $this->apiResponse(
            $this->response->getUserResponse($updatedUser),
        );
    }
}
