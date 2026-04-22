<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-skew inline-flex items-center justify-center px-6 py-3 bg-transparent border-2 border-red-500 text-red-500 font-bold text-xs uppercase tracking-widest hover:bg-red-500 hover:text-black focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition-all duration-200']) }}>
    <span>{{ $slot }}</span>
</button>
