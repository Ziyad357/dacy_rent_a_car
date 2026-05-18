@extends('admin.layout')
@section('title', 'Dəstək Mesajları')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-white">Müştəri Mesajları</h1>
        <p class="text-sm text-gray-500 mt-0.5">Müştərilərin dəstək sorğuları</p>
    </div>
</div>

@if($conversations->isEmpty())
    <div class="theme-card rounded-2xl p-12 text-center">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-3"
             style="background: rgba(99,102,241,0.1);">
            <svg class="w-7 h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
            </svg>
        </div>
        <p class="text-gray-400 font-medium">Heç bir müştəri mesajı yoxdur</p>
    </div>
@else
    <div class="space-y-2">
        @foreach($conversations as $customer)
            @php $last = $customer->supportMessages->first(); @endphp
            <a href="{{ route('admin.support.show', $customer) }}"
               class="theme-card rounded-xl px-5 py-4 flex items-center gap-4 transition-all duration-200 hover:-translate-y-0.5 block">
                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 text-sm font-bold text-white"
                     style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                    {{ mb_strtoupper(mb_substr($customer->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="font-semibold text-gray-200 text-sm truncate">{{ $customer->name }}</p>
                        @if($customer->unread_count > 0)
                            <span class="px-1.5 py-0.5 rounded-full text-xs font-bold text-white"
                                  style="background: #ef4444;">{{ $customer->unread_count }}</span>
                        @endif
                    </div>
                    @if($last)
                        <p class="text-xs text-gray-500 truncate mt-0.5">
                            {{ $last->sender_role === 'admin' ? 'Siz: ' : '' }}{{ $last->body }}
                        </p>
                    @endif
                </div>
                <p class="text-xs text-gray-600 shrink-0">
                    {{ $last?->created_at->diffForHumans() }}
                </p>
            </a>
        @endforeach
    </div>
@endif
@endsection
