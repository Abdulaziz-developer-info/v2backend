<?php

namespace App\Services\Firebase;

use Kreait\Firebase\Factory;

class FirebaseService
{
    protected $factory;
    protected $firestore;
    protected $auth;

    public function __construct()
    {
        $path = config('firebase.credentials');
        
        // Fayl borligini tekshirish (Xatolikni oldini olish uchun)
        if (!file_exists($path)) {
            throw new \Exception("Firebase JSON fayli topilmadi: " . $path);
        }

        $this->factory = (new Factory)->withServiceAccount($path);
    }

    public function firestore()
    {
        if (!$this->firestore) {
            // DIQQAT: database() metodi Google Cloud Firestore obyektini qaytaradi
            $this->firestore = $this->factory->createFirestore()->database();
        }
        return $this->firestore;
    }

    public function auth()
    {
        if (!$this->auth) {
            $this->auth = $this->factory->createAuth();
        }
        return $this->auth;
    }

    public function createCustomToken(string $uid): string
    {
        return $this->auth()->createCustomToken($uid)->toString();
    }
}