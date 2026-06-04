<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Constants\OrderStatus;
use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification; // ✅ Notification ইম্পোর্ট করা হলো
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; // ✅ DB ইম্পোর্ট করা হলো (ট্রানজ্যাকশনের জন্য)

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    // 🚀 মূল পরিবর্তন: handleRecordUpdate ফাংশনে রিফান্ড লজিক
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // যদি অর্ডারের স্ট্যাটাস 'processing' বা 'auto-processing' থেকে 'cancel' এ পরিবর্তিত হয়
        if (
            ($record->status === OrderStatus::PROCESSING || $record->status === OrderStatus::AUTOPROCESSING) && 
            $data['status'] === OrderStatus::CANCEL 
        ) {
            // ডাটাবেস ট্রানজ্যাকশন ব্যবহার করা হলো
            DB::transaction(function () use ($record, $data) {
                
                // প্রথমে স্ট্যাটাস আপডেট করা হলো
                $record->fill($data)->save(); 
                
                $user = $record->user;
                $refundAmount = $record->amount;

                if ($user && $refundAmount > 0) {
                    // User-এর ব্যালেন্স আপডেট
                    // ⚠️ ধরে নেওয়া হচ্ছে User মডেলে 'balance' কলাম আছে
                    $user->increment('balance', $refundAmount);
                    
                    // সফলতার নোটিফিকেশন
                    Notification::make()
                        ->title("Order #{$record->id} Canceled & Refunded Successfully! ✅")
                        ->body("৳" . number_format($refundAmount, 2) . " has been refunded to User: {$user->name} (ID: {$user->id}).")
                        ->success()
                        ->send();
                } else {
                    // ব্যর্থতার নোটিফিকেশন (যদি টাকা না থাকে বা ইউজার না পাওয়া যায়)
                    Notification::make()
                        ->title("Order #{$record->id} Canceled, but Refund Failed!")
                        ->body("Refund amount was ৳0 or user not found. Only status changed to 'cancel'.")
                        ->warning()
                        ->send();
                }
            });
            // ট্রানজ্যাকশন শেষে রেকর্ডটি রিটার্ন করা হলো
            return $record;
            
        } else {
            // যদি Cancel স্ট্যাটাস না হয় বা Completed/Canceled অর্ডারে পরিবর্তন করা হয়, তবে স্বাভাবিক সেভ করা
            $record->fill($data)->save();
            return $record;
        }
    }
}