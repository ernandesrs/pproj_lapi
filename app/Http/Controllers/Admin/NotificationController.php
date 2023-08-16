<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\NotificationResource;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'unread_total' => \Auth::user()->unreadNotifications()->count(),
            'total' => \Auth::user()->notifications()->count(),
            'unread_notifications' => NotificationResource::collection(\Auth::user()->unreadNotifications()->get())
                ->response()->getData(),
            'notifications' => NotificationResource::collection(\Auth::user()->notifications()->limit(50)->get())
                ->response()->getData()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
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
     * Mark as read the specified resource in storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
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
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
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