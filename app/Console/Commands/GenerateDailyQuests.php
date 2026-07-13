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

        $this->info("Generating daily quests for {$date->format('Y-m-d')} (Day: $dayOfWeek)");

        // Get template for today
        $template = DailyQuestTemplate::where('day_of_week', $dayOfWeek)->first();

        if (!$template) {
            $this->warn("No template found for day $dayOfWeek");
            return;
        }

        // Create daily quest
        $dailyQuest = \App\Models\DailyQuest::create([
            'title' => $template->title,
            'description' => $template->description,
            'type' => $template->type,
            'target' => $template->target,
            'reward_exp' => $template->reward_exp,
            'reward_coins' => $template->reward_coins,
            'date' => $date,
        ]);

        // Assign quest to all active users
        $users = User::where('role', 'user')->get();
        
        $questsCreated = 0;
        foreach ($users as $user) {
            // Check if user already has a quest for this date
            $existing = UserQuest::where('user_id', $user->id)
                ->where('daily_quest_id', $dailyQuest->id)
                ->where('date', $date)
                ->exists();

            if (!$existing) {
                UserQuest::create([
                    'user_id' => $user->id,
                    'daily_quest_id' => $dailyQuest->id,
                    'progress' => 0,
                    'completed' => false,
                    'date' => $date,
                ]);
                $questsCreated++;
            }
        }

        $this->info("Daily quest created: {$template->title}");
        $this->info("Assigned to $questsCreated users");
    }
}
