<?php
use App\Http\Controllers\StickerController;

Route::get('/smartsecure', [StickerController::class, 'index'])->name('smartsecure.index');
Route::post('/smartsecure/sticker/create', [StickerController::class, 'sticker_create'])->name('smartsecure.sticker_create');
Route::post('/smartsecure/sticker/update', [StickerController::class, 'sticker_update'])->name('smartsecure.sticker_update');
Route::post('/smartsecure/sticker/destroy', [StickerController::class, 'sticker_destroy'])->name('smartsecure.sticker_destroy');