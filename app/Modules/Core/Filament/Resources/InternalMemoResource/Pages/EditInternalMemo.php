<?php

namespace App\Modules\Core\Filament\Resources\InternalMemoResource\Pages;

use App\Modules\Core\Actions\DecideMemo;
use App\Modules\Core\Actions\SubmitMemo;
use App\Modules\Core\Enums\ApprovalStatus;
use App\Modules\Core\Exceptions\ApprovalException;
use App\Modules\Core\Filament\Resources\InternalMemoResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditInternalMemo extends EditRecord
{
    protected static string $resource = InternalMemoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('submit')
                ->label('Submit for approval')
                ->visible(fn (): bool => $this->record->status === ApprovalStatus::Draft)
                ->requiresConfirmation()
                ->action(function (SubmitMemo $submit): void {
                    try {
                        $submit->handle($this->record, auth()->user());
                        Notification::make()->title('Note sheet submitted')->success()->send();
                    } catch (ApprovalException $exception) {
                        Notification::make()->title($exception->getMessage())->danger()->send();
                    }
                }),
            Action::make('approve')
                ->label('Approve')
                ->color('success')
                ->visible(fn (): bool => $this->record->status === ApprovalStatus::Pending)
                ->schema([
                    Textarea::make('comment')->label('Comment'),
                ])
                ->action(function (array $data, DecideMemo $decide): void {
                    $this->decide($decide, 'approved', $data['comment'] ?? null);
                }),
            Action::make('reject')
                ->label('Reject')
                ->color('danger')
                ->visible(fn (): bool => $this->record->status === ApprovalStatus::Pending)
                ->schema([
                    Textarea::make('comment')->label('Reason')->required(),
                ])
                ->action(function (array $data, DecideMemo $decide): void {
                    $this->decide($decide, 'rejected', $data['comment'] ?? null);
                }),
        ];
    }

    private function decide(DecideMemo $decide, string $decision, ?string $comment): void
    {
        try {
            $decide->handle($this->record, auth()->user(), $decision, $comment);
            Notification::make()->title('Decision recorded')->success()->send();
        } catch (ApprovalException $exception) {
            Notification::make()->title($exception->getMessage())->danger()->send();
        }
    }
}
