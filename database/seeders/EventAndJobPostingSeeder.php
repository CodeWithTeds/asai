<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use App\Models\EventImage;
use App\Models\JobPosting;
use App\Enums\EventStatus;
use App\Enums\EventType;
use App\Enums\JobPostingStatus;
use App\Enums\JobPostingType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class EventAndJobPostingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user (usually seeded via DatabaseSeeder) or create one if none exists
        $user = User::first() ?? User::factory()->create([
            'name' => 'Default Admin',
            'email' => 'admin@asai.com',
        ]);

        // 1. Prepare Public Disk Directories
        $jobCoversDir = 'job-postings/covers';
        if (!Storage::disk('public')->exists($jobCoversDir)) {
            Storage::disk('public')->makeDirectory($jobCoversDir);
        }

        $eventImagesDir = 'events';
        if (!Storage::disk('public')->exists($eventImagesDir)) {
            Storage::disk('public')->makeDirectory($eventImagesDir);
        }

        // 2. Copy Images to Public Storage Disk
        $imagesToCopy = [
            'card1.png',
            'card2.png',
            'card3.png',
            'parralax1.jpg',
            'parralax2.jpg',
            'parralax3.jpg',
            'hero.png',
            'logo.png',
        ];

        foreach ($imagesToCopy as $filename) {
            $srcPath = public_path('images/' . $filename);
            if (file_exists($srcPath)) {
                // Copy to job postings folder
                @copy($srcPath, Storage::disk('public')->path($jobCoversDir . '/' . $filename));
                // Copy to events folder
                @copy($srcPath, Storage::disk('public')->path($eventImagesDir . '/' . $filename));
            }
        }

        // 3. Seed Events
        $eventsData = [
            [
                'title' => 'ASAI Nationwide Operations Expansion',
                'body' => 'Annapolis Security Agency, Inc. has officially expanded its operations across Luzon, Visayas, and Mindanao. We are proud to establish localized regional hubs to better support our clients and provide faster response times.',
                'type' => EventType::News,
                'status' => EventStatus::Active,
                'starts_at' => now()->subDays(5),
                'expires_at' => now()->addMonths(12),
                'images' => [
                    'events/parralax1.jpg',
                    'events/card1.png'
                ]
            ],
            [
                'title' => 'ASAI Annual Safety Summit and Security Assembly',
                'body' => 'We successfully completed our Annual Safety Summit, highlighting advanced security methodologies and recognizing our outstanding security personnel from various branches nationwide.',
                'type' => EventType::Event,
                'status' => EventStatus::Active,
                'starts_at' => now()->subDays(2),
                'expires_at' => now()->addMonths(6),
                'images' => [
                    'events/parralax2.jpg',
                    'events/card2.png'
                ]
            ],
            [
                'title' => 'Advanced Tactical & K9 Joint Exercises',
                'body' => 'Our tactical response team and K9 detection units completed their specialized joint training program to elevate standard response capabilities for high-security areas.',
                'type' => EventType::Activity,
                'status' => EventStatus::Active,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(3),
                'images' => [
                    'events/parralax3.jpg',
                    'events/card3.png'
                ]
            ],
            [
                'title' => 'Integrated Smart Security Package Promo',
                'body' => 'Upgrade your facility protection with our bundled packages combining on-site guard deployment with smart CCTV monitoring solutions at exclusive package rates.',
                'type' => EventType::Promos,
                'status' => EventStatus::Active,
                'starts_at' => now()->subDays(10),
                'expires_at' => now()->addMonths(2),
                'images' => [
                    'events/hero.png',
                    'events/logo.png'
                ]
            ]
        ];

        foreach ($eventsData as $data) {
            $event = Event::create([
                'title' => $data['title'],
                'body' => $data['body'],
                'type' => $data['type'],
                'status' => $data['status'],
                'starts_at' => $data['starts_at'],
                'expires_at' => $data['expires_at'],
                'created_by' => $user->id,
            ]);

            foreach ($data['images'] as $imagePath) {
                EventImage::create([
                    'event_id' => $event->id,
                    'image_path' => $imagePath,
                ]);
            }
        }

        // 4. Seed Job Postings
        $jobPostingsData = [
            [
                'title' => 'Licensed Security Officer (LSO)',
                'description' => 'We are seeking licensed security guards for immediate deployment at high-end commercial establishments in Metro Manila. Requirements: Valid security license, high school diploma, and clean background records.',
                'cover_image' => 'job-postings/covers/card1.png',
                'location' => 'Metro Manila',
                'type' => JobPostingType::FullTime,
                'status' => JobPostingStatus::Open,
                'starts_at' => now()->subDays(3),
                'expires_at' => now()->addMonths(6),
            ],
            [
                'title' => 'VIP Executive Protection Specialist',
                'description' => 'Looking for qualified personal protection specialists. Prior military or executive security escort experience is required. Candidates must have advanced defensive driving skills and tactical training certificates.',
                'cover_image' => 'job-postings/covers/parralax1.jpg',
                'location' => 'Cebu City',
                'type' => JobPostingType::FullTime,
                'status' => JobPostingStatus::Open,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(4),
            ],
            [
                'title' => 'K9 Handler & Security Escort',
                'description' => 'Coordinate with detection canine units at major transport terminals. Requires a certified K9 handling course completion and experience in emergency crowd management.',
                'cover_image' => 'job-postings/covers/card2.png',
                'location' => 'Davao City',
                'type' => JobPostingType::FullTime,
                'status' => JobPostingStatus::Open,
                'starts_at' => now()->subDays(5),
                'expires_at' => now()->addMonths(3),
            ],
            [
                'title' => 'Security Operations Supervisor',
                'description' => 'Responsible for checking guard compliance on duty, managing schedules, organizing briefing sessions, and conducting field inspections. Candidates must have at least 3 years of supervisory experience.',
                'cover_image' => 'job-postings/covers/parralax2.jpg',
                'location' => 'Clark, Pampanga',
                'type' => JobPostingType::FullTime,
                'status' => JobPostingStatus::Open,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(8),
            ],
            [
                'title' => 'CCTV Surveillance & Control Room Operator',
                'description' => 'Monitor surveillance feeds, identify security breaches, and alert field officers. Must have basic computing capabilities and solid analytical thinking.',
                'cover_image' => 'job-postings/covers/card3.png',
                'location' => 'Makati City',
                'type' => JobPostingType::PartTime,
                'status' => JobPostingStatus::Open,
                'starts_at' => now()->subDays(1),
                'expires_at' => now()->addMonths(6),
            ],
            [
                'title' => 'Night Shift Facility Security Marshal',
                'description' => 'Secure warehousing and industrial complexes during night shifts. Perform regular gate checks, patrol logs, and manage visitor entries.',
                'cover_image' => 'job-postings/covers/parralax3.jpg',
                'location' => 'Laguna Technopark',
                'type' => JobPostingType::Contract,
                'status' => JobPostingStatus::Open,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(2),
            ],
            [
                'title' => 'Corporate Lobby Security Specialist',
                'description' => 'Manage front-desk entry logging and visitor badges at corporate headquarters. Requires excellent interpersonal and communication skills.',
                'cover_image' => 'job-postings/covers/hero.png',
                'location' => 'BGC, Taguig',
                'type' => JobPostingType::FullTime,
                'status' => JobPostingStatus::Open,
                'starts_at' => now()->subDays(4),
                'expires_at' => now()->addMonths(5),
            ],
            [
                'title' => 'Temporary Security Marshal (Event Base)',
                'description' => 'Crowd management and ticket marshal positions for our upcoming trade exhibits. Contract duration is 3-5 days. Training will be provided.',
                'cover_image' => 'job-postings/covers/logo.png',
                'location' => 'Pasay City',
                'type' => JobPostingType::Contract,
                'status' => JobPostingStatus::Open,
                'starts_at' => now(),
                'expires_at' => now()->addDays(15),
            ],
        ];

        foreach ($jobPostingsData as $data) {
            JobPosting::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'cover_image' => $data['cover_image'],
                'location' => $data['location'],
                'type' => $data['type'],
                'status' => $data['status'],
                'starts_at' => $data['starts_at'],
                'expires_at' => $data['expires_at'],
                'created_by' => $user->id,
            ]);
        }
    }
}
