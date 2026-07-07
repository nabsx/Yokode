<?php

namespace App\Http\Controllers;

use App\Models\ShopItem;
use App\Models\UserInventory;
use App\Models\UserBooster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    /**
     * Menampilkan halaman toko
     */
    public function index()
    {
        $shopItems = ShopItem::where('is_available', true)->get();
        
        return view('shop.index', compact('shopItems'));
    }
    
    /**
     * Membeli item dari toko
     */
    public function buy(Request $request, int $id)
    {
        $user = Auth::user();
        $item = ShopItem::findOrFail($id);
        
        // Cek apakah user punya cukup koin
        if ($user->coins < $item->price_coins) {
            return redirect()->route('shop.index')
                ->with('error', 'Koin tidak mencukupi!');
        }
        
        // Kurangi koin user
        $user->deductCoins($item->price_coins);
        
        // Proses efek item berdasarkan tipe
        $effects = json_decode($item->effects, true);
        
        switch ($item->type) {
            case 'heart':
                if (isset($effects['hearts'])) {
                    if ($effects['hearts'] == 'full') {
                        $user->refillHearts();
                        $message = 'Semua hearts terisi penuh!';
                    } else {
                        $user->addHeart($effects['hearts']);
                        $message = '+' . $effects['hearts'] . ' heart ditambahkan!';
                    }
                }
                break;
                
            case 'booster':
                if (isset($effects['xp_multiplier'])) {
                    $duration = $effects['duration_minutes'] ?? 30;
                    $user->activateBooster('xp_2x', $duration);
                    $message = 'XP Booster aktif selama ' . $duration . ' menit!';
                }
                break;
                
            default:
                // Untuk cosmetic atau item lainnya, simpan ke inventory
                UserInventory::updateOrCreate(
                    ['user_id' => $user->id, 'shop_item_id' => $item->id],
                    ['quantity' => \DB::raw('quantity + 1')]
                );
                $message = $item->name . ' berhasil dibeli!';
                break;
        }
        
        return redirect()->route('shop.index')
            ->with('success', $message ?? 'Item berhasil dibeli!');
    }
    
    /**
     * Menampilkan inventory user
     */
    public function inventory()
    {
        $user = Auth::user();
        $inventory = $user->inventory()->with('shopItem')->get();
        
        return view('shop.inventory', compact('inventory'));
    }
    
    /**
     * Menggunakan item dari inventory
     */
    public function useItem(Request $request, int $id)
    {
        $user = Auth::user();
        $inventory = UserInventory::where('user_id', $user->id)
            ->where('id', $id)
            ->with('shopItem')
            ->firstOrFail();
        
        $item = $inventory->shopItem;
        $effects = json_decode($item->effects, true);
        
        switch ($item->type) {
            case 'booster':
                $duration = $effects['duration_minutes'] ?? 30;
                $user->activateBooster('xp_2x', $duration);
                $message = 'XP Booster aktif selama ' . $duration . ' menit!';
                break;
        }
        
        // Kurangi quantity atau hapus item
        if ($inventory->quantity > 1) {
            $inventory->decrement('quantity');
        } else {
            $inventory->delete();
        }
        
        return redirect()->route('shop.inventory')
            ->with('success', $message ?? 'Item digunakan!');
    }
}