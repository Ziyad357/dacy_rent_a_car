@extends('admin.layout')
@section('title', $user->name . ' — Dəstək')

@section('content')
<div class="max-w-2xl">

    <a href="{{ route('admin.support.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-300 mb-5 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Geri
    </a>

    <div class="flex items-center gap-3 mb-5">
        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 text-sm font-bold text-white"
             style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
            {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
        </div>
        <div>
            <h1 class="text-lg font-bold text-white">{{ $user->name }}</h1>
            <p class="text-xs text-gray-500">{{ $user->email }}</p>
        </div>
    </div>

    <div class="theme-card rounded-2xl overflow-hidden flex flex-col" style="height: 520px;">

        {{-- Messages --}}
        <div id="chat-messages" class="flex-1 overflow-y-auto px-5 py-4 space-y-3">
            @forelse($messages as $msg)
                @if($msg->sender_role === 'customer')
                    <div class="flex justify-start gap-2.5">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 text-xs font-bold text-white"
                             style="background: linear-gradient(135deg, #3b82f6, #6366f1);">
                            {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                        </div>
                        <div class="max-w-xs lg:max-w-md">
                            <div class="rounded-2xl rounded-tl-sm px-4 py-2.5 text-sm text-gray-200"
                                 style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.08);">
                                {!! nl2br(e($msg->body)) !!}
                            </div>
                            <p class="text-xs text-gray-600 mt-1">{{ $user->name }} · {{ $msg->created_at->format('d.m H:i') }}</p>
                        </div>
                    </div>
                @else
                    <div class="flex justify-end">
                        <div class="max-w-xs lg:max-w-md">
                            <div class="rounded-2xl rounded-tr-sm px-4 py-2.5 text-sm text-white"
                                 style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                                {!! nl2br(e($msg->body)) !!}
                            </div>
                            <p class="text-right text-xs text-gray-600 mt-1">Siz · {{ $msg->created_at->format('d.m H:i') }}</p>
                        </div>
                    </div>
                @endif
            @empty
                <div class="flex flex-col items-center justify-center h-full text-center py-16">
                    <p class="text-gray-500 text-sm">Hələ mesaj yoxdur</p>
                </div>
            @endforelse
        </div>

        {{-- Reply input --}}
        <div class="border-t p-4" style="border-color: rgba(255,255,255,0.07);">
            <form method="POST" action="{{ route('admin.support.reply', $user) }}" class="flex gap-3">
                @csrf
                <input name="body" required maxlength="2000"
                       placeholder="Cavab yazın..."
                       autocomplete="off"
                       class="flex-1 bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-200 placeholder-gray-600
                              focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all">
                <button type="submit"
                        class="w-10 h-10 rounded-xl flex items-center justify-center transition-all hover:scale-105 shrink-0"
                        style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const el = document.getElementById('chat-messages');
        if (el) { el.scrollTop = el.scrollHeight; }
    });
</script>
@endsection
