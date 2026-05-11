<?php

namespace App\Filament\Resources\EmailCampaigns\Schemas;

use App\Services\CampaignTemplateLibrary;
use App\Services\UserSegmentResolver;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class EmailCampaignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Empezar desde una plantilla (opcional)')
                    ->description('Plantillas prearmadas con asunto, cuerpo y segmento ya configurados. Al seleccionar una, los campos de abajo se rellenan — luego puedes editar libremente.')
                    ->schema([
                        Select::make('_template_picker')
                            ->label('Plantilla')
                            ->options(CampaignTemplateLibrary::options())
                            ->placeholder('Empezar en blanco')
                            ->dehydrated(false)
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set): void {
                                if (! $state) {
                                    return;
                                }

                                $tpl = CampaignTemplateLibrary::find($state);
                                if (! $tpl) {
                                    return;
                                }

                                $set('subject', $tpl['subject']);
                                $set('body_markdown', $tpl['body']);
                                $set('segment', $tpl['segment']);
                            }),
                    ])
                    ->columns(1)
                    ->collapsible()
                    ->collapsed(),

                Section::make('Mensaje')
                    ->schema([
                        TextInput::make('subject')
                            ->label('Asunto')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ej: 🚀 Nueva función disponible en Citora')
                            ->helperText('Aparece como subject del correo. Mantén bajo los 60 caracteres para que no se corte.'),

                        MarkdownEditor::make('body_markdown')
                            ->label('Cuerpo (markdown)')
                            ->required()
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold', 'italic', 'strike', 'link',
                                'bulletList', 'orderedList', 'blockquote',
                                'heading',
                                'undo', 'redo',
                            ])
                            ->helperText('Soporta **negrita**, *itálica*, listas, links [texto](url) y encabezados ## Título'),
                    ])
                    ->columns(1),

                Section::make('Destinatarios')
                    ->schema([
                        Select::make('segment')
                            ->label('Segmento')
                            ->options(UserSegmentResolver::options())
                            ->required()
                            ->default(UserSegmentResolver::SEGMENT_ALL)
                            ->live()
                            ->helperText('Filtra a quiénes se enviará. El conteo se calcula al elegir.'),

                        Placeholder::make('segment_count')
                            ->label('Total de destinatarios estimados')
                            ->content(function (Get $get): string {
                                $segment = $get('segment') ?: UserSegmentResolver::SEGMENT_ALL;
                                try {
                                    $count = app(UserSegmentResolver::class)->count($segment);

                                    return "📊 {$count} usuario(s) con correo válido recibirán este mensaje.";
                                } catch (\Throwable $e) {
                                    return '⚠️ No se pudo calcular el conteo.';
                                }
                            }),
                    ])
                    ->columns(1),

                Section::make('Programación (opcional)')
                    ->description('Si dejas la fecha vacía, podrás enviar manualmente desde la lista. Si la fijas, el cron disparará el envío cuando llegue la hora (revisa cada 5 min).')
                    ->schema([
                        DateTimePicker::make('scheduled_at')
                            ->label('Enviar el...')
                            ->seconds(false)
                            ->native(false)
                            ->minDate(now())
                            ->placeholder('Sin programar'),
                    ])
                    ->columns(1)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
