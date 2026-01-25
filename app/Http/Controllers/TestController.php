<?php

namespace App\Http\Controllers;

use App\Models\AccountSession;
use App\Services\Firebase\FcmService;
use App\Services\Firebase\FirebaseService;

class TestController extends Controller
{


    public function sendNotification($id)
    {
        $session = AccountSession::findOrFail($id);

        if (!$session || !$session->fcm_token)
            return response()->json(["error'=>'FCM token yo'q"], 400);


        $fin = app(FcmService::class)->sendToToken(
            $session->fcm_token,

            // 🔔 Notification ko‘rinishi
            title: 'Ombor ogohlantirish',
            body: "Go'sht tugadi",

            // 🧠 App ichida bajariladigan buyruq
            data: [
                'action' => 'OPEN_PRODUCT',
                'product_id' => 'MEAT_001',
            ]
        );

        // return back();
        return response()->json($fin);
    }


    public function storeData()
    {
        try {
            $firebaseService = app(FirebaseService::class);
            $firestore = $firebaseService->firestore();

            $orgDoc = $firestore->collection('org')->document((string) 1);

            // 1. 'updates' ichiga yangi hujjat qo'shish
            $orgDoc->collection('updates')->document('update_id_1')->set([
                'editor' => "Abdulaziz",
                'global_sync_id' => 3,
                'last_active' => now()->toDateTimeString(),
            ]);

            // 2. 'orders' ichiga yangi hujjat qo'shish
            // $orgDoc->collection('orders')->document('order_id_1')->set([
            //     [
            //         'name' => "Shashlik",
            //         'product_id' => 2,
            //         'qunatity' => 3,
            //         'last_active' => now()->toDateTimeString(),
            //     ],
            //     [
            //         'name' => "Shashlik",
            //         'product_id' => 2,
            //         'qunatity' => 3,
            //         'last_active' => now()->toDateTimeString(),
            //     ],
            // ]);


            return "Yozildi!";
        } catch (\Exception $e) {
            return "Xato: " . $e->getMessage();
        }
    }

    public function getData($id = 1)
    {
        try {
            $firestore = app(FirebaseService::class)->firestore();
            $snapshot = $firestore->collection('users')->document((string) $id)->snapshot();

            if ($snapshot->exists()) {
                return $snapshot->data(); // Massiv qaytaradi
            }
            return "Hujjat topilmadi!";
        } catch (\Exception $e) {
            return "Xato: " . $e->getMessage();
        }
    }

    public function updateData($id = 1)
    {
        try {
            $firestore = app(FirebaseService::class)->firestore();

            // Faqat ko'rsatilgan maydonlarni yangilaydi
            $firestore->collection('users')->document((string) $id)->update([
                ['path' => 'full_name', 'value' => 'Yangi Ism'],
                ['path' => 'last_active', 'value' => now()->toDateTimeString()]
            ]);

            return "Yangilandi!";
        } catch (\Exception $e) {
            return "Xato: " . $e->getMessage();
        }
    }

    public function deleteData($id = 1)
    {
        try {
            $firestore = app(FirebaseService::class)->firestore();
            $firestore->collection('users')->document((string) $id)->delete();

            return "O'chirildi!";
        } catch (\Exception $e) {
            return "Xato: " . $e->getMessage();
        }
    }



}
