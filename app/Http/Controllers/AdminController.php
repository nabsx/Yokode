<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Achievement;
use App\Models\ShopItem;
use App\Models\DailyQuest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'active_users' => User::where('role', 'user')->where('updated_at', '>=', now()->subDays(7))->count(),
            'total_lessons' => Lesson::count(),
            'total_quizzes' => Quiz::count(),
            'total_categories' => Category::count(),
            'premium_users' => User::where('is_premium', true)->count(),
        ];

        // Top users by exp
        $topUsers = User::where('role', 'user')
            ->select('id', 'name', 'email', 'total_exp', 'is_premium')
            ->orderBy('total_exp', 'desc')
            ->limit(10)
            ->get();

        // Recent activity
        $recentUsers = User::where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'topUsers', 'recentUsers'));
    }

    /**
     * User Management - List
     */
    public function usersIndex(Request $request)
    {
        $query = User::where('role', 'user');

        // Search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        // Filter by premium
        if ($request->filled('premium')) {
            $premium = $request->get('premium');
            if ($premium === 'yes') {
                $query->where('is_premium', true);
            } elseif ($premium === 'no') {
                $query->where('is_premium', false);
            }
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * User Management - Show
     */
    public function usersShow(User $user)
    {
        if ($user->role !== 'user') {
            abort(404);
        }

        $stats = [
            'total_exp' => $user->total_exp,
            'lessons_completed' => $user->progresses()->where('completed', true)->count(),
            'quizzes_answered' => $user->answers()->count(),
            'achievements' => $user->achievements()->count(),
            'coins' => $user->coins,
            'hearts' => $user->hearts->current_hearts ?? 0,
            'streak' => $user->streak->current_streak ?? 0,
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * User Management - Edit
     */
    public function usersEdit(User $user)
    {
        if ($user->role !== 'user') {
            abort(404);
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * User Management - Update
     */
    public function usersUpdate(Request $request, User $user)
    {
        if ($user->role !== 'user') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'total_exp' => 'nullable|integer|min:0',
            'coins' => 'nullable|integer|min:0',
            'is_premium' => 'boolean',
            'premium_expires_at' => 'nullable|date',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)->with('success', 'User updated successfully.');
    }

    /**
     * User Management - Delete/Ban
     */
    public function usersDestroy(User $user)
    {
        if ($user->role !== 'user') {
            abort(404);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User has been deleted.');
    }

    /**
     * Module Management - List
     */
    public function lessonsIndex(Request $request)
    {
        $query = Lesson::query();

        if ($request->filled('category')) {
            $query->where('category_id', $request->get('category'));
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
        }

        $lessons = $query->orderBy('created_at', 'desc')->paginate(20);
        $categories = Category::all();

        return view('admin.lessons.index', compact('lessons', 'categories'));
    }

    /**
     * Module Management - Create
     */
    public function lessonsCreate()
    {
        $categories = Category::all();
        return view('admin.lessons.create', compact('categories'));
    }

    /**
     * Module Management - Store
     */
    public function lessonsStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'order' => 'nullable|integer',
            'content' => 'nullable|string',
            'difficulty' => 'nullable|in:easy,medium,hard',
        ]);

        Lesson::create($validated);

        return redirect()->route('admin.lessons.index')->with('success', 'Lesson created successfully.');
    }

    /**
     * Module Management - Edit
     */
    public function lessonsEdit(Lesson $lesson)
    {
        $categories = Category::all();
        return view('admin.lessons.edit', compact('lesson', 'categories'));
    }

    /**
     * Module Management - Update
     */
    public function lessonsUpdate(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'order' => 'nullable|integer',
            'content' => 'nullable|string',
            'difficulty' => 'nullable|in:easy,medium,hard',
        ]);

        $lesson->update($validated);

        return redirect()->route('admin.lessons.index')->with('success', 'Lesson updated successfully.');
    }

    /**
     * Module Management - Delete
     */
    public function lessonsDestroy(Lesson $lesson)
    {
        $lesson->delete();
        return redirect()->route('admin.lessons.index')->with('success', 'Lesson deleted successfully.');
    }

    /**
     * Category Management - List
     */
    public function categoriesIndex()
    {
        $categories = Category::withCount('lessons')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Category Management - Create
     */
    public function categoriesCreate()
    {
        return view('admin.categories.create');
    }

    /**
     * Category Management - Store
     */
    public function categoriesStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Category Management - Edit
     */
    public function categoriesEdit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Category Management - Update
     */
    public function categoriesUpdate(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Category Management - Delete
     */
    public function categoriesDestroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

    /**
     * Quiz Management - List
     */
    public function quizzesIndex(Request $request)
    {
        $query = Quiz::with('lesson');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('question', 'like', "%$search%")
                  ->orWhereHas('lesson', function ($q) use ($search) {
                      $q->where('title', 'like', "%$search%");
                  });
        }

        $quizzes = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.quizzes.index', compact('quizzes'));
    }

    /**
     * Quiz Management - Create
     */
    public function quizzesCreate()
    {
        $lessons = Lesson::orderBy('title')->get();
        return view('admin.quizzes.create', compact('lessons'));
    }

    /**
     * Quiz Management - Store
     */
    public function quizzesStore(Request $request)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'question' => 'required|string',
            'options' => 'required|array|min:2|max:4',
            'options.*' => 'required|string',
            'correct_answer' => 'required|string|in:0,1,2,3',
            'points' => 'required|integer|min:1|max:1000',
        ]);

        $validated['options'] = array_values($validated['options']); // Ensure array indexing
        $validated['correct_answer'] = (int)$validated['correct_answer'];

        Quiz::create($validated);

        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz created successfully.');
    }

    /**
     * Quiz Management - Edit
     */
    public function quizzesEdit(Quiz $quiz)
    {
        $lessons = Lesson::orderBy('title')->get();
        return view('admin.quizzes.edit', compact('quiz', 'lessons'));
    }

    /**
     * Quiz Management - Update
     */
    public function quizzesUpdate(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'question' => 'required|string',
            'options' => 'required|array|min:2|max:4',
            'options.*' => 'required|string',
            'correct_answer' => 'required|string|in:0,1,2,3',
            'points' => 'required|integer|min:1|max:1000',
        ]);

        $validated['options'] = array_values($validated['options']);
        $validated['correct_answer'] = (int)$validated['correct_answer'];

        $quiz->update($validated);

        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz updated successfully.');
    }

    /**
     * Quiz Management - Delete
     */
    public function quizzesDestroy(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz deleted successfully.');
    }

    /**
     * Gamification Management - Achievements
     */
    public function achievementsIndex()
    {
        $achievements = Achievement::paginate(20);
        return view('admin.achievements.index', compact('achievements'));
    }

    /**
     * Gamification Management - Shop Items
     */
    public function shopItemsIndex()
    {
        $items = ShopItem::paginate(20);
        return view('admin.shop.index', compact('items'));
    }

    /**
     * Gamification Management - Daily Quests
     */
    public function dailyQuestsIndex()
    {
        $quests = DailyQuest::orderBy('date', 'desc')->paginate(20);
        return view('admin.quests.index', compact('quests'));
    }

    /**
     * Statistics & Reports
     */
    public function analytics()
    {
        // User growth
        $userGrowth = User::where('role', 'user')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();

        // Premium stats
        $premiumStats = [
            'total_premium' => User::where('is_premium', true)->count(),
            'active_premium' => User::premiumActive()->count(),
        ];

        // Top lessons
        $topLessons = Lesson::withCount('progresses')
            ->orderBy('progresses_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.analytics', compact('userGrowth', 'premiumStats', 'topLessons'));
    }
}
