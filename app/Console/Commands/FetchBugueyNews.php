<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Announcement;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FetchBugueyNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch-buguey';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawls official sources and filters news using Buguey keywords';

    /**
     * The keywords required to validate a news article.
     *
     * @var array
     */
    protected $keywords = [
        'buguey', 
        'buguey features', 
        'lgu buguey', 
        'municipality of buguey',
        'buguey lagoon',
        'malaga',
        'guraman festival',
        'st. anne church',
        'minanga',
        'san lorenzo',
        'centro buguey',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting news fetch for Buguey...');
        $this->newLine();

        // In a production environment, you would use Http::get() to fetch actual RSS feeds 
        // from PIA Region 2, DA-BFAR, or the Cagayan Tourism API here.
        // For demonstration, here is the raw extracted data simulating the HTTP response:
        $crawledArticles = [
            [
                'title' => 'Cagayan Valley targets aquaculture expansion',
                'description' => 'BFAR Region 2 is distributing new HDPE fish cages across northern coastal towns, specifically targeting the Buguey lagoon to boost local Malaga production.',
                'source' => 'BFAR Region 2',
                'category' => 'LGU Memorandum'
            ],
            [
                'title' => 'Provincial Road Upgrades in District 1',
                'description' => 'The DPWH has announced major road rehabilitations spanning from Aparri down to Gonzaga, improving trade routes for farmers.',
                'source' => 'PIA Region 2',
                'category' => 'General Update'
            ],
            [
                'title' => 'Discovering Hidden Gems of the North',
                'description' => 'Tourism in the north is booming. One of the standout Buguey features is its untouched mangrove forests and historical baroque churches.',
                'source' => 'Visit Cagayan',
                'category' => 'General Update'
            ],
            [
                'title' => 'Free Vaccination Program Rollout',
                'description' => 'The Department of Health launches a comprehensive vaccination drive across all municipalities in Cagayan Valley, including special health missions.',
                'source' => 'DOH Region 2',
                'category' => 'Health Service'
            ],
            [
                'title' => 'Buguey Fishermen Receive New Equipment',
                'description' => 'The LGU Buguey, in partnership with DA-BFAR, distributed modern fishing equipment to 50 registered fisherfolk in the coastal barangays. This initiative aims to improve their daily catch and livelihood.',
                'source' => 'DA-BFAR Cagayan',
                'category' => 'Barangay News'
            ],
        ];

        $addedCount = 0;
        $skippedCount = 0;

        foreach ($crawledArticles as $article) {
            // Combine title and description to check for keywords
            $contentToScan = strtolower($article['title'] . ' ' . $article['description']);
            
            // Check if ANY of our predefined keywords exist in the text
            if (Str::contains($contentToScan, $this->keywords)) {
                
                // Prevent duplicate entries by checking if the title already exists
                $exists = Announcement::where('title', $article['title'])->exists();

                if (!$exists) {
                    Announcement::create([
                        'title' => $article['title'],
                        'content' => $article['description'] . ' (Source: ' . $article['source'] . ')',
                        'category' => $article['category'] ?? 'General Update',
                        'target_barangay' => null, // Broadcast to all Buguey residents
                        'posted_at' => Carbon::now(),
                        'is_active' => true,
                    ]);
                    
                    $this->info("✓ Added: {$article['title']}");
                    $addedCount++;
                } else {
                    $this->warn("⊘ Skipped (duplicate): {$article['title']}");
                }
            } else {
                $this->comment("⊗ Filtered out (no Buguey keywords): {$article['title']}");
                $skippedCount++;
            }
        }

        $this->newLine();
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("Fetch complete!");
        $this->info("Added: {$addedCount} new Buguey-related announcements");
        $this->info("Filtered: {$skippedCount} non-relevant articles");
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        return Command::SUCCESS;
    }
}
