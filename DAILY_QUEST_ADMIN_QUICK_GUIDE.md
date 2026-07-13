# Daily Quest Templates - Admin Quick Guide

## Accessing the Feature

1. Log in to the admin panel
2. Click **Gamification** section in the sidebar
3. Click **Quest Templates**

You'll see a grid with 7 cards - one for each day of the week (Monday to Sunday).

## Viewing Templates

Each day card shows:
- **Day name** (in colored header)
- **Current quest** (if one exists)
- **Description** (brief overview)
- **Type** (e.g., "Complete Lesson")
- **Target** (number to achieve)
- **Rewards** (XP and coins)
- **Edit button**

If no quest exists for a day, you'll see "+ No quest template set" with an Edit button to create one.

## Creating/Editing a Template

### Step 1: Click Edit
Click the **Edit** button on any day card.

### Step 2: Fill in the Form

**Quest Title**
- What users see as the quest name
- Example: "Complete 3 Lessons Today"

**Quest Type**
- Choose from dropdown:
  - **Complete Lesson** - User completes N lessons
  - **Answer Quiz** - User answers N quiz questions
  - **Gain EXP** - User gains N experience points
  - **Login** - User logs in daily
  - **Perfect Quiz** - User gets perfect score on N quizzes

**Description**
- Instructions for the user
- Example: "Complete 3 lessons to earn rewards"

**Target Number**
- How many of the action to achieve
- Must be at least 1
- Example: 3 (for 3 lessons)

**Reward XP**
- Experience points the user gets
- Example: 100 XP

**Reward Coins** (Optional)
- Virtual currency (coins)
- Can be 0 if no coins reward
- Example: 50 coins

### Step 3: Check the Preview
As you type, the preview card on the right shows exactly how the quest will appear to users.

### Step 4: Save
Click **Save Template** to save your changes.

## Example Templates

### Monday - Get Started
- Title: "Complete Your First Lesson"
- Type: Complete Lesson
- Target: 1
- Reward: 50 XP, 25 coins

### Wednesday - Quiz Master
- Title: "Answer 5 Quiz Questions"
- Type: Answer Quiz
- Target: 5
- Reward: 150 XP, 50 coins

### Friday - Perfect Score
- Title: "Get Perfect Score on a Quiz"
- Type: Perfect Quiz
- Target: 1
- Reward: 200 XP, 75 coins

### Daily
- Title: "Daily Login Bonus"
- Type: Login
- Target: 1
- Reward: 25 XP, 10 coins

## Tips & Best Practices

### Make Engaging Quests
- Keep targets reasonable (not too hard)
- Give good rewards to incentivize participation
- Make descriptions clear and motivating

### Mix Quest Types
- Don't repeat the same type every day
- Rotate between different activities
- Keep users engaged with variety

### Balance Rewards
- Easier quests = Lower rewards
- Harder quests = Higher rewards
- Coins should be less common than XP

### Progressive Difficulty
- Monday/Wednesday: Easier (get users started)
- Thursday/Friday: Medium (maintain momentum)
- Weekend: Varied (flexible schedule)

## FAQ

**Q: What if I don't set a template for a day?**
A: Users won't get a quest that day. The system only creates quests for days with templates.

**Q: Can I delete a template?**
A: Click Edit and clear all fields - but it's better to set new values. Currently, to remove: update the database directly.

**Q: When do users get the quest?**
A: The system generates quests based on templates. You need to set up automatic quest generation in your system.

**Q: Can I set different rewards for different users?**
A: No, templates are uniform for all users. All users get the same quest on the same day.

**Q: What if I make a mistake?**
A: Click Edit again and update the values. Changes take effect immediately.

**Q: Can I change a template mid-week?**
A: Yes! Changes update immediately. Users already working on a quest won't be affected.

## Keyboard Shortcuts

- **Tab** - Move between form fields
- **Enter** - Submit form (when Save button is focused)
- **Esc** - Cancel (close form and go back)

## Support

If something isn't working:
1. Refresh the page
2. Check that you're logged in as admin
3. Try a different day's template
4. Check the DAILY_QUEST_TEMPLATES_GUIDE.md for technical details

## Next Steps

After setting up templates, the development team will:
1. Set up automatic daily quest generation
2. Create user interface to display quests
3. Add quest completion tracking
4. Add rewards claiming system
