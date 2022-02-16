<div class="relative | pt-1">
    <div class="flex items-center justify-between | mb-2">
        <div class="text-right">
            <span class="text-xs font-semibold inline-block text-blue-600">
                {{ $currentPercentage }}%
            </span>
        </div>
    </div>
    <div class="h-2 overflow-hidden rounded | text-xs flex  bg-blue-200 | mb-4">
        <div
            class="flex flex-col | shadow-none text-center whitespace-nowrap text-white justify-center bg-blue-500"
            style="width: {{ $currentPercentage }}%;"
        ></div>
    </div>
</div>
