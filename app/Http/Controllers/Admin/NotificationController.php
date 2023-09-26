<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\NotificationResource;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'total' => \Auth::user()->notifications()->count(),
            'notifications' => NotificationResource::collection(\Auth::user()->notifications()->limit(1000)->get())
                ->response()->getData()
        ]);
    }

    /**
     * Display a listing of the unread notifications.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unread()
    {
        return response()->json([
            'success' => true,
            'total' => \Auth::User()->unreadNotifications()->count(),
            'notifications' => NotificationResource::collection(\Auth::user()->unreadNotifications()->get())
                ->response()->getData()
        ]);
    }

    /**
     * Display the specified notifiaction.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $notification = \Auth::user()->notifications()->where("id", $id)->first();
        if (!$notification) {
            throw new \App\Exceptions\NotFoundException();
        }

        return response()->json([
            'success' => true,
            'notification' => new NotificationResource($notification)
        ]);
    }

    /**
     * Mark as read the specified notification in storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead($id)
    {
        $notification = \Auth::user()->notifications()->where("id", $id)->first();
        if (!$notification) {
            throw new \App\Exceptions\NotFoundException();
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'notification' => new NotificationResource($notification)
        ]);
    }

    /**
     * Remove the specified notification from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $notification = \Auth::user()->notifications()->where("id", $id)->first();
        if (!$notification) {
            throw new \App\Exceptions\NotFoundException();
        }

        $notification->delete();

        return response()->json([
            'success' => true
        ]);
    }
}