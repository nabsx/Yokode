# Daily Quest Templates - Admin User Guide

## Quick Start

### Accessing the Feature

1. **Login** to the admin panel
2. **Navigate** to the left sidebar
3. **Look for** "Gamification" section
4. **Click** "Quest Templates" (with calendar icon)

```
Dashboard
├── Management
│   ├── Users
│   ├── Lessons
│   ├── Categories
│   └── Quizzes
├── Gamification
│   ├── Achievements
│   ├── Shop Items
│   ├── Daily Quests
│   └── ✨ Quest Templates ← Click here
└── Analytics
```

## Feature Overview

### Templates Dashboard

When you click "Quest Templates", you'll see a grid with 7 cards, one for each day of the week:

```
┌─────────────────────────────────────────────────────────────────┐
│  Monday      │  Tuesday     │  Wednesday   │  Thursday          │
│  ─────────   │  ─────────   │  ─────────   │  ─────────         │
│  Title       │  Title       │  Title       │  Title             │
│  Desc...     │  Desc...     │  Desc...     │  Desc...           │
│  Type Badge  │  Type Badge  │  Type Badge  │  Type Badge        │
│  Target: 5   │  Target: 3   │  Target: 10  │  Target: 2         │
│  100 XP      │  50 XP       │  200 XP      │  75 XP             │
│  [EDIT]      │  [EDIT]      │  [EDIT]      │  [EDIT]            │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│  Friday      │  Saturday    │  Sunday      │                    │
│  ─────────   │  ─────────   │  ─────────   │                    │
│  Title       │  Title       │  [+] Empty   │                    │
│  Desc...     │  Desc...     │  Click Edit  │                    │
│  Type Badge  │  Type Badge  │  to add      │                    │
│  Target: 8   │  Target: 4   │  quest       │                    │
│  150 XP      │  100 XP      │              │                    │
│  [EDIT]      │  [EDIT]      │  [EDIT]      │                    │
└─────────────────────────────────────────────────────────────────┘
```

Each card shows:
- **Day name** (highlighted at top)
- **Quest title** - What the quest is called
- **Description** - Short explanation (truncated)
- **Type badge** - Color-coded quest type
- **Target** - Number users need to achieve
- **Rewards** - XP and coins earned
- **Edit button** - Click to create or update

## Editing a Quest Template

### Step 1: Click "Edit" Button
Click the "EDIT" button on any day's card

### Step 2: Fill Out the Form

You'll see a form with these fields:

```
Day Badge: [Monday]

Quest Title *
┌────────────────────────────────────────┐
│ e.g., Complete 3 Lessons               │
└────────────────────────────────────────┘

Quest Type *
┌────────────────────────────────────────┐
│ Complete Lesson ▼                      │
│ • Complete Lesson                      │
│ • Answer Quiz                          │
│ • Gain Exp                             │
│ • Login                                │
│ • Perfect Quiz                         │
└────────────────────────────────────────┘

Description *
┌────────────────────────────────────────┐
│ Describe what users need to do...      │
│ [3 line textarea]                      │
└────────────────────────────────────────┘

Target Number *      │  Reward XP *
┌──────────────────┐ │ ┌──────────────────┐
│ 3                │ │ │ 100              │
└──────────────────┘ │ └──────────────────┘

Reward Coins (Optional)
┌────────────────────────────────────────┐
│ 50                                     │
└────────────────────────────────────────┘
```

### Step 3: Watch the Live Preview

As you type, the preview below updates to show how the quest will look:

```
┌─────────────────────────────────────────┐
│ PREVIEW                                 │
├─────────────────────────────────────────┤
│ Complete 3 Lessons                      │
│ Finish and pass any 3 lessons to       │
│ improve your skills and knowledge.     │
│                                         │
│ [Complete Lesson]  Target: 3           │
│                                         │
│ 100 XP  |  50 🪙                       │
└─────────────────────────────────────────┘
```

### Step 4: Save or Cancel

- **Save Template** - Saves and returns to templates view
- **Cancel** - Returns without saving

## Quest Types Explained

| Type | Description | Example |
|------|-------------|---------|
| **Complete Lesson** | User completes X lessons | "Complete 3 Lessons" |
| **Answer Quiz** | User answers X quiz questions | "Answer 10 Quiz Questions" |
| **Gain Exp** | User gains X experience points | "Gain 500 Experience Points" |
| **Login** | User logs in (target is usually 1) | "Login to the App" |
| **Perfect Quiz** | User gets perfect score on X quizzes | "Perfect Score on 2 Quizzes" |

## Real-World Examples

### Monday - Warm-up Day
```
Title: Review Basics
Type: Complete Lesson
Description: Complete 2 beginner lessons to warm up for the week
Target: 2
Reward XP: 75
Reward Coins: 25
```

### Wednesday - Mid-Week Challenge
```
Title: Quiz Master
Type: Perfect Quiz
Description: Get a perfect score on 1 quiz
Target: 1
Reward XP: 150
Reward Coins: 50
```

### Friday - Achievement Day
```
Title: Weekly Grinder
Type: Gain Exp
Description: Earn 500 experience points this week
Target: 500
Reward XP: 200
Reward Coins: 100
```

### Sunday - Social Day
```
Title: Community Contributor
Type: Login
Description: Login to check the leaderboard and see how you rank
Target: 1
Reward XP: 50
Reward Coins: 20
```

## Tips & Tricks

### ✅ Good Practices

1. **Vary the quest types** throughout the week
   - Don't make every quest "Complete Lesson"
   - Mix in quizzes, XP goals, logins

2. **Scale difficulty by day**
   - Easier earlier in week (Monday-Wednesday)
   - Harder later in week (Friday-Sunday)
   - Makes progression feel natural

3. **Keep titles short but descriptive**
   - Good: "Weekly Grinder"
   - Bad: "Complete 5 lessons and get 250 XP before midnight"

4. **Use rewards to incentivize**
   - Higher difficulty = higher rewards
   - Special days (Friday) = bonus coins
   - Consider user progression level

5. **Test with live preview**
   - Check how it looks before saving
   - Verify all info is clear and correct

### ⚠️ Things to Avoid

1. **Unreasonable targets**
   - Don't set target to 100 for average users
   - Consider user skill level

2. **Vague descriptions**
   - Be specific about what users need to do
   - Include any tips or hints

3. **Inconsistent naming**
   - Use consistent terminology across quests
   - Users will understand better

4. **Forgetting rewards**
   - Always include both XP and coins (or at least XP)
   - Make rewards meaningful

## Troubleshooting

### "Page not found" when accessing templates
- Ensure you're logged in as admin
- Try refreshing the page
- Check URL: should be `/admin/daily-quests/templates`

### Edit form not loading
- Click the day card's EDIT button again
- Check browser console for errors
- Try a different day

### Live preview not updating
- Make sure you're typing in the input fields
- Refresh the page and try again
- Preview updates automatically as you type

### Can't save template
- Check that all required fields (*) are filled
- Verify target and rewards are numbers
- Check browser console for JavaScript errors

### Lost form changes
- Click Cancel only if you want to discard
- Form doesn't auto-save, only saves on Save button

## Day Naming

The days are always in this order:

1. **Monday** (0) - Start of week
2. **Tuesday** (1)
3. **Wednesday** (2) - Mid-week
4. **Thursday** (3)
5. **Friday** (4) - End of work week
6. **Saturday** (5) - Weekend
7. **Sunday** (6) - End of week

## What Happens Next?

After you create/update quest templates:

1. ✓ Templates are saved to the database
2. ✓ System identifies current day
3. ✓ Users receive the appropriate daily quest
4. ✓ Users see quest on their dashboard
5. ✓ Users complete quest to earn rewards

Note: The automatic quest assignment is handled by another system component.

## FAQ

**Q: Can I change a template after users start using it?**
A: Yes! Update anytime. Existing quests won't be affected, only new ones.

**Q: What if I don't set a template for a day?**
A: Users won't get a quest that day. The empty state shows "No quest template set."

**Q: Can I copy a template from one day to another?**
A: Not yet - you'll need to manually enter the same info for each day. We're considering this feature.

**Q: What's the difference between Reward XP and Reward Coins?**
A: XP is for level progression, Coins are for shop purchases. Both are earned when quest completes.

**Q: How many quests can I create?**
A: You can create one per day of the week (7 total). Each day can have only 1 active template.

**Q: Can I delete a template?**
A: Click Edit and change the template - there's no separate delete button. Update with different content to effectively "remove" it.

**Q: When do daily quests reset?**
A: Each day at midnight (system timezone). Users get a fresh quest for that day.

## Support

For questions or issues:
1. Check this guide first
2. Try the troubleshooting section
3. Contact your technical support team

---

Happy quest creating! 🎯
