@extends('customer.layout')
@section('title', 'Dəstək')

@section('content')
<div class="max-w-2xl mx-auto">

    <h1 class="text-xl font-bold text-white mb-1">Dəstək Mərkəzi</h1>
    <p class="text-sm text-gray-500 mb-5">Adminlə birbaşa yazışın</p>

    {{-- Chat window --}}
    <div class="theme-card rounded-2xl overflow-hidden flex flex-col" style="height: 520px;">

        {{-- Messages --}}
        <div id="chat-messages" class="flex-1 overflow-y-auto px-5 py-4 space-y-3">
            @forelse($messages as $msg)
                @if($msg->sender_role === 'customer')
                    <div class="flex justify-end">
                        <div class="max-w-xs lg:max-w-md">
                            <div class="rounded-2xl rounded-tr-sm px-4 py-2.5 text-sm text-white"
                                 style="background: linear-gradient(135deg, #3b82f6, #6366f1);">
                                {!! nl2br(e($msg->body)) !!}
                            </div>
                            <p class="text-right text-xs text-gray-600 mt-1">{{ $msg->created_at->format('d.m H:i') }}</p>
                        </div>
                    </div>
                @else
                    <div class="flex justify-start gap-2.5">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 text-xs font-bold text-white"
                             style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">A</div>
                        <div class="max-w-xs lg:max-w-md">
                            <div class="rounded-2xl rounded-tl-sm px-4 py-2.5 text-sm text-gray-200"
                                 style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.08);">
                                {!! nl2br(e($msg->body)) !!}
                            </div>
                            <p class="text-xs text-gray-600 mt-1">Admin · {{ $msg->created_at->format('d.m H:i') }}</p>
                        </div>
                    </div>
                @endif
            @empty
                <div class="flex flex-col items-center justify-center h-full text-center py-16">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3"
                         style="background: rgba(59,130,246,0.1);">
                        <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                    <p class="text-gray-400 font-medium text-sm">Hələ mesaj yoxdur</p>
                    <p class="text-gray-600 text-xs mt-1">Admin ilə söhbətə başlayın</p>
                </div>
            @endforelse
        </div>

        {{-- Input --}}
        <div class="border-t p-4" style="border-color: rgba(255,255,255,0.07);">
            <form method="POST" action="{{ route('customer.support.store') }}" class="flex gap-3">
                @csrf
                <input name="body" required maxlength="2000"
                       placeholder="Mesajınızı yazın..."
                       autocomplete="off"
                       class="flex-1 bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-200 placeholder-gray-600
                              focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                <button type="submit"
                        class="w-10 h-10 rounded-xl flex items-center justify-center transition-all hover:scale-105 shrink-0"
                        style="background: linear-gradient(135deg, #3b82f6, #6366f1);">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </form>
            @error('body')
                <p class="text-xs text-red-400 mt-1.5">{{ $message }}</p>
            @enderror
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
