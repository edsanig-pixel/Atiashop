<div class="px-4 space-y-1">
    @php
        $menus = \App\Models\Menu::with('children')->whereNull('parent_id')->orderBy('order')->get();
    @endphp

    @foreach($menus as $menu)
        @php
            $hasChildren = $menu->children->count() > 0;
            $isActiveSelf = !$hasChildren && $menu->route && request()->routeIs($menu->route);
            $isChildActive = false;
            foreach ($menu->children as $child) {
                if ($child->route && request()->routeIs($child->route)) {
                    $isChildActive = true;
                    break;
                }
            }
            $defaultOpen = ($hasChildren && ($isActiveSelf || $isChildActive));
        @endphp

        <div x-data="{ open: {{ $defaultOpen ? 'true' : 'false' }} }">
            {{-- آیتم اصلی منو --}}
            <div @click="{{ $hasChildren ? 'open = !open' : '' }}"
                 class="group flex items-center justify-between px-3 py-2.5 rounded-lg cursor-pointer transition-all duration-200
                        {{ ($isActiveSelf || $isChildActive) ? 'bg-blue-50 text-[#0284C7] border-r-4 border-[#0284C7]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#0EA5E9]' }}">
                <div class="flex items-center gap-3">
                    <i class="fas {{ $menu->icon ?? 'fa-circle' }} w-5 text-center text-lg"></i>
                    <span class="text-sm font-medium">
                        @if($hasChildren || !$menu->route)
                            {{ $menu->title }}
                        @else
                            <a href="{{ route($menu->route) }}" class="block w-full">{{ $menu->title }}</a>
                        @endif
                    </span>
                </div>
                @if($hasChildren)
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                @endif
            </div>

            {{-- زیرمنوها --}}
            @if($hasChildren)
                <div x-show="open" x-collapse class="mr-6 mt-1 space-y-1">
                    @foreach($menu->children as $child)
                        @php
                            $isCurrentChildActive = $child->route && request()->routeIs($child->route);
                        @endphp
                        <a href="{{ $child->route ? route($child->route) : '#' }}"
                           class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-all
                                  {{ $isCurrentChildActive ? 'bg-blue-50 text-[#0284C7]' : 'text-gray-500 hover:bg-gray-50 hover:text-[#0EA5E9]' }}">
                            <div class="flex items-center gap-3">
                                <i class="fas {{ $child->icon ?? 'fa-circle' }} w-5 text-center text-sm"></i>
                                <span>{{ $child->title }}</span>
                            </div>
                            @if($isCurrentChildActive)
                                <i class="fas fa-circle text-[8px] text-[#0284C7]"></i>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
</div>