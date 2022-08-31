<div class="mb-4 ">
    {{$date}}
</div>
<div class="flex flex-row">
    <div class="w-32 mr-4">
        <button class="button mb-4" wire:click="long_request" >wire:loading</button>
        <div class="mb-4" wire:loading>loading...</div>
    </div>

    <div class="w-32" x-data="{show_loader: false}" x-on:loader_off.window="show_loader = false;">
        <button class="button mb-4" wire:click="execute" @click="show_loader = true;">via alpine.js</button>
        <div class="mb-4" x-show="show_loader">loading...</div>
    </div>
</div>
