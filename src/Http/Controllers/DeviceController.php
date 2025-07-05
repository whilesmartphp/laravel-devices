<?php

namespace Whilesmart\LaravelDevices\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;
use Whilesmart\LaravelDevices\Traits\ApiResponse;

class DeviceController extends Controller
{
    use ApiResponse;

    #[OA\Post(
        path: "/api/v1/devices/",
        summary: "Add a new device to the user's profile",
        security: [],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new  OA\Property(property: "name", description: "Name of the device", type: "string"),
                    new OA\Property(property: "token", description: "FCM Token", type: "string"),
                    new OA\Property(property: "type", description: "Device type. web|mobile", type: "string"),
                    new OA\Property(property: "identifier", description: "Device identifier", type: "string"),
                    new OA\Property(property: "platform", description: "Device platform", type: "string"),
                ]
            )
        ),
        tags: ["Device"],
        responses: [
            new OA\Response(response: 200, description: "OK")
        ]

    )]
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string',
            'name' => 'nullable|string',
            'type' => 'nullable|string|in:web,mobile',
            'identifier' => 'nullable|string',
            'platform' => 'nullable|string',
        ]);

        $user = $request->user();
        $data = $request->all();
        $data['type'] = $data['type'] ?? 'mobile';
        $data['user_id'] = $user->id;
        try {
            $device = $user->devices()->firstOrCreate($data);

            return $this->success($device, 'Device created successfully', 201);
        } catch (Exception $err) {
            Log::error($err);

            return $this->failure("Operation failed", 500, ['message' => $err->getMessage(), 'trace' => $err->getTrace()]);
        }
    }


    #[OA\Delete(
        path: "/api/v1/devices/{id}",
        summary: "Removes a new device from the user's profile",
        security: [],
        tags: [
            "Device"
        ],
        parameters: [
            new OA\Parameter(name: "id", description: "Device ID", in: "path", required: true,),
        ],
        responses: [
            new OA\Response(response: 200, description: "OK")
        ]
    )]
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();
            $device = $user->devices()->find($id);
            if ($device == null) {
                return $this->failure('Device not found', 404);
            }

            $device->delete();

            return $this->success([], 'Device deleted successfully', 200);

        } catch (Exception $err) {
            Log::error($err);

            return $this->failure("Operation failed", 500, ['message' => $err->getMessage(), 'trace' => $err->getTrace()]);
        }
    }


    #[OA\PUT(
        path: "/api/v1/devices/{id}",
        summary: "Update a new device on the user's profile",
        security: [],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", description: "Name of the device", type: "string"),
                    new OA\Property(property: "token", description: "FCM Token", type: "string"),
                    new OA\Property(property: "type", description: "Device type. web|mobile", type: "string"),
                    new OA\Property(property: "identifier", description: "Device identifier", type: "string"),
                    new OA\Property(property: "platform", description: "Device platform", type: "string"),
                ]
            )
        ),
        tags: ["Device"],
        parameters: [
            new OA\Parameter(name: "id", description: "Device ID", in: "path", required: true),
        ],
        responses: [new OA\Response(response: 200, description: "OK")
        ]
    )]
    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'token' => 'required|string',
            'name' => 'nullable|string',
            'type' => 'nullable|string|in:web,mobile',
            'identifier' => 'nullable|string',
            'platform' => 'nullable|string',
        ]);

        try {
            $user = $request->user();

            $device = $user->devices()->find($id);
            if ($device == null) {
                return $this->failure('Device not found', 404);
            }
            $data = $request->all();
            $device->update($data);

            return $this->success($device, 'Device updated successfully', 200);

        } catch (Exception $err) {
            Log::error($err);

            return $this->failure("Operation failed", 500, ['message' => $err->getMessage(), 'trace' => $err->getTrace()]);
        }
    }


    #[OA\Get(
        path: "/api/v1/devices/",
        summary: "Get a user's devices",
        security: [],
        tags: [
            "Device"
        ],
        responses: [
            new OA\Response(response: 200, description: "OK")
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $devices = $user->devices;

        return $this->success($devices, 'Devices retrieved successfully', 200);
    }
}
