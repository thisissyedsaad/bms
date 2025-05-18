<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'full_name' => 'Sarah Johnson',
                'profile_name' => 'Mystic Sarah',
                'email' => 'sarah@example.com',
                'profile_photo' => 'psychics/sarah.jpg',
                'tagline' => 'Guiding you through life\'s journey',
                'mobile_number' => '+1234567890',
                'whatsapp_number' => '+1234567890',
                'profile_description' => 'With over 15 years of experience in tarot reading and spiritual healing, I help people find clarity and direction in their lives.',
                'meta_title' => 'Mystic Sarah - Professional Psychic Reader',
                'meta_description' => 'Get accurate psychic readings from Mystic Sarah. Specializing in tarot, love readings, and spiritual guidance.',
                'meta_keywords' => 'psychic reading, tarot, spiritual healing, love reading',
                'website' => 'https://mysticsarah.com',
            ],
            [
                'full_name' => 'Michael Chen',
                'profile_name' => 'Intuitive Michael',
                'email' => 'michael@example.com',
                'profile_photo' => 'psychics/michael.jpg',
                'tagline' => 'Connecting you with your spiritual path',
                'mobile_number' => '+1987654321',
                'whatsapp_number' => '+1987654321',
                'profile_description' => 'As an intuitive empath, I provide deep insights into relationships, career paths, and spiritual growth.',
                'meta_title' => 'Intuitive Michael - Empathic Psychic Reader',
                'meta_description' => 'Experience transformative psychic readings with Intuitive Michael. Expert in empathic readings and spiritual guidance.',
                'meta_keywords' => 'empath, intuitive reading, spiritual guidance, psychic advisor',
                'website' => 'https://intuitivemichael.com',
            ],
            [
                'full_name' => 'Elena Rodriguez',
                'profile_name' => 'Crystal Elena',
                'email' => 'elena@example.com',
                'profile_photo' => 'psychics/elena.jpg',
                'tagline' => 'Crystal healing and spiritual wisdom',
                'mobile_number' => '+1122334455',
                'whatsapp_number' => '+1122334455',
                'profile_description' => 'Combining crystal healing with psychic abilities to provide holistic spiritual guidance and healing.',
                'meta_title' => 'Crystal Elena - Crystal Healer & Psychic',
                'meta_description' => 'Find healing and guidance with Crystal Elena. Specialized in crystal healing and spiritual readings.',
                'meta_keywords' => 'crystal healing, spiritual healing, psychic reading, holistic guidance',
                'website' => 'https://crystalelena.com',
            ],
        ];
    }
}
