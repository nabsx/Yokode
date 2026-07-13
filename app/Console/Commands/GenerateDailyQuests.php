<?php

namespace App\Console\Commands;

use App\Models\DailyQuestTemplate;
use App\Models\User;
use App\Models\UserQuest;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateDailyQuests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quests:generate {--date= : Date to generate quests for (format: Y-m-d)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate daily quests from templates for the current day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->option('date') ? Carbon::createFromFormat('Y-m-d', $this->option('date')) : Carbon::now();
        $dayOfWeek = (int) $date->format('w'); // 0 = Sunday, ..., 6 = Saturday
        $dateStr = $date->toDateString();

        $this->info("Generating daily quests for {$dateStr} (Day: {$date->format('l')})");

        // Get template for today
        $template = DailyQuestTemplate::where('day_of_week', $dayOfWeek)->first();

        if (!$template) {
            $this->warn("No template found for day $dayOfWeek ({$date->format('l')})");
            return;
        }

        // Use firstOrCreate to prevent duplicates - if quest exists for this date, get it
        $dailyQuest = \App\Models\DailyQuest::firstOrCreate(
            ['date' => $dateStr],  // Find by date (must be unique)
            [                       // Create with these attributes if not found
                'title' => $template->title,
                'description' => $template->description,
                'type' => $template->type,
                'target' => $template->target,
                'reward_exp' => $template->reward_exp,
                'reward_coins' => $template->reward_coins,
            ]
        );

        // Check if this is new or existing
        $isNew = $dailyQuest->wasRecentlyCreated;

        // Assign quest to all active users
        $users = User::where('role', 'user')->get();
        
        $questsCreated = 0;
        foreach ($users as $user) {
            // Use firstOrCreate to prevent duplicate user_quest entries
            $userQuest = UserQuest::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'daily_quest_id' => $dailyQuest->id,
                    'date' => $dateStr,
                ],
                [
                    'progress' => 0,
                    'completed' => false,
                ]
            );

            if ($userQuest->wasRecentlyCreated) {
                $questsCreated++;
            }
        }

        if ($isNew) {
            $this->info("✓ Daily quest created: {$template->title}");
            $this->info("✓ Assigned to $questsCreated users");
        } else {
            $this->line("ℹ Quest already exists for {$dateStr}: {$dailyQuest->title}");
            $this->line("ℹ Assigned to {$users->count()} users (checked for duplicates)");
        }
    }
}
