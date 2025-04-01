<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Social_communication;

class SocialCommunicationSeeder extends Seeder
{
    public function run()
    {
        Social_communication::create([
            'name' => 'Facebook',
            'address' => 'https://www.facebook.com/'
        ]);

        Social_communication::create([
            'name' => 'Instagram',
            'address' => 'https://www.instagram.com/'
        ]);

        Social_communication::create([
            'name' => 'whatsapp',
            'address' => '00994253651458'
        ]);

        Social_communication::create([
            'name' => 'Email',
            'address' => 'https://mail.google.com/mail/u/0'
        ]);

        Social_communication::create([
            'name' => 'Threads',
            'address' => 'https://www.threads.net/'
        ]);

        Social_communication::create([
            'name' => 'X',
            'address' => 'https://www.x.com/'
        ]);
    }
}
