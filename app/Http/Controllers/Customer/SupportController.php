<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index()
    {
        $messages = SupportMessage::forUser(auth()->id())
            ->orderBy('created_at')
            ->get();

        SupportMessage::forUser(auth()->id())
            ->where('sender_role', 'admin')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('customer.support.index', compact('messages'));
    }

    public function store(Request $request)
    {
        $request->validate(['body' => 'required|string|max:2000']);

        $isFirstMessage = SupportMessage::forUser(auth()->id())->doesntExist();

        SupportMessage::create([
            'user_id'     => auth()->id(),
            'sender_role' => 'customer',
            'body'        => $request->body,
        ]);

        if ($isFirstMessage) {
            SupportMessage::create([
                'user_id'     => auth()->id(),
                'sender_role' => 'admin',
                'body'        => "Salam! 👋 Müraciətiniz qəbul edildi.\n\nBiz həftənin hər günü saat 09:00–18:00 arasında işləyirik. Ən qısa zamanda sizə geri dönüş ediləcəkdir.\n\nÇox təcilildirsə: 📞 0505006663",
            ]);
        }

        return back();
    }
}
