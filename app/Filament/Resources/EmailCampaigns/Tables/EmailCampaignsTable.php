<?php

namespace App\Filament\Resources\EmailCampaigns\Tables;

use App\Jobs\SendCampaignJob;
use App\Mail\CampaignMail;
use App\Models\EmailCampaign;
use App\Services\UserSegmentResolver;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;

class EmailCampaignsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subject')
                    ->label('Asunto')
                    ->searchable()
                    ->wrap()
                    ->description(fn (EmailCampaign $record) => $record->creator?->email),

                TextColumn::make('segment')
                    ->label('Segmento')
                    ->formatStateUsing(fn (string $state) => UserSegmentResolver::options()[$state] ?? $state)
                    ->wrap(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'draft' => 'Borrador',
                        'scheduled' => 'Programada',
                        'sending' => 'Enviando',
                        'sent' => 'Enviada',
                        'failed' => 'Falló',
                        default => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'draft' => 'gray',
                        'scheduled' => 'info',
                        'sending' => 'warning',
                        'sent' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('scheduled_at')
                    ->label('Programada')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('sent_at')
                    ->label('Enviada')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('—'),

                TextColumn::make('recipients_count')
                    ->label('Enviados')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('opened_count')
                    ->label('Aperturas')
                    ->alignCenter()
                    ->formatStateUsing(function (EmailCampaign $record): string {
                        $rate = $record->openRate();

                        return $record->opened_count.' ('.$rate.'%)';
                    }),

                TextColumn::make('created_at')
                    ->label('Creada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'draft' => 'Borrador',
                        'scheduled' => 'Programada',
                        'sending' => 'Enviando',
                        'sent' => 'Enviada',
                        'failed' => 'Falló',
                    ]),
            ])
            ->recordActions([
                Action::make('preview')
                    ->label('Previsualizar')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->modalHeading(fn (EmailCampaign $record) => "Vista previa: {$record->subject}")
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar')
                    ->modalContent(fn (EmailCampaign $record) => view('emails.campaign', [
                        'subject' => $record->subject,
                        'bodyMarkdown' => $record->body_markdown,
                        'campaignId' => $record->id,
                        'recipientUserId' => null,
                        'pixelUrl' => null,
                        'unsubscribeUrl' => url('/mis-citas'),
                    ])),

                Action::make('test_to_me')
                    ->label('Enviarme test')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalDescription(fn () => 'Se enviará una copia de este correo solo a tu email: '.auth()->user()->email)
                    ->action(function (EmailCampaign $record): void {
                        try {
                            Mail::to(auth()->user()->email)->send(new CampaignMail($record, auth()->id()));
                            Notification::make()
                                ->success()
                                ->title('Test enviado')
                                ->body('Revisa tu bandeja (y spam por si acaso).')
                                ->send();
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->danger()
                                ->title('No se pudo enviar')
                                ->body($e->getMessage())
                                ->send();
                        }
                    }),

                Action::make('send_now')
                    ->label('Enviar al segmento')
                    ->icon('heroicon-o-rocket-launch')
                    ->color('success')
                    ->visible(fn (EmailCampaign $record) => in_array($record->status, ['draft', 'scheduled', 'failed']))
                    ->requiresConfirmation()
                    ->modalDescription(function (EmailCampaign $record): string {
                        $count = app(UserSegmentResolver::class)->count($record->segment);

                        return "Vas a enviar este correo a {$count} usuario(s). Esta acción no se puede deshacer.";
                    })
                    ->action(function (EmailCampaign $record): void {
                        SendCampaignJob::dispatchSync($record->id);

                        Notification::make()
                            ->success()
                            ->title('Campaña encolada')
                            ->body('El envío se está procesando. Refresca la página en unos minutos para ver el conteo final.')
                            ->send();
                    }),

                EditAction::make()
                    ->visible(fn (EmailCampaign $record) => $record->status === 'draft'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
