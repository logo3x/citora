<?php

namespace App\Filament\Pages;

use App\Models\Business;
use App\Models\BusinessSchedule;
use BackedEnum;
use Filament\Actions\Action as HeaderAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * @property Schema $form
 */
class EditMyBusiness extends Page
{
    protected string $view = 'filament.pages.edit-my-business';

    protected static ?string $title = 'Mi Negocio';

    protected static ?string $navigationLabel = 'Mi Negocio';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static ?int $navigationSort = 1;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user->hasRole('business_owner') && $user->business_id !== null;
    }

    protected function getHeaderActions(): array
    {
        $business = $this->getBusiness();
        $publicUrl = rtrim(config('app.url'), '/').'/'.$business->slug;

        return [
            HeaderAction::make('view_public')
                ->label('Ver mi página')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->url($publicUrl, shouldOpenInNewTab: true),
            HeaderAction::make('copy_link')
                ->label('Copiar enlace')
                ->icon('heroicon-o-clipboard-document')
                ->color('gray')
                ->action(function () use ($publicUrl): void {
                    $this->js('navigator.clipboard.writeText('.json_encode($publicUrl).')');

                    Notification::make()
                        ->success()
                        ->title('¡Enlace copiado!')
                        ->body($publicUrl)
                        ->send();
                }),
            HeaderAction::make('reset_tutorial')
                ->label('Ver tutorial de nuevo')
                ->icon('heroicon-o-academic-cap')
                ->color('warning')
                ->action(fn () => $this->js('window.CitoraTour && window.CitoraTour.reset()')),
        ];
    }

    public function mount(): void
    {
        $business = $this->getBusiness();
        $user = auth()->user();

        $businessData = $business->attributesToArray();
        $businessData['owner_display_name'] = $user->display_name;
        $businessData['schedules'] = $business->schedules()
            ->orderByRaw('CASE WHEN day_of_week = 0 THEN 7 ELSE day_of_week END')
            ->get()
            ->map(fn (BusinessSchedule $schedule) => $schedule->attributesToArray())
            ->all();

        $this->form->fill($businessData);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    Section::make('Propietario')
                        ->description('Este nombre aparece en el saludo del panel y en comunicaciones internas. Si lo dejas vacío, se usará tu nombre de Google.')
                        ->schema([
                            TextInput::make('owner_display_name')
                                ->label('Nombre del propietario')
                                ->placeholder(auth()->user()->name)
                                ->helperText('Puede ser tu nombre comercial, apodo o razón social.')
                                ->maxLength(150),
                        ])
                        ->columns(1),

                    Section::make('Información general')
                        ->schema([
                            TextInput::make('name')
                                ->label('Nombre')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('slogan')
                                ->label('Eslogan')
                                ->placeholder('Ej: Los mejores cortes de la ciudad')
                                ->maxLength(255),
                            TextInput::make('slug')
                                ->label('URL pública')
                                ->prefix('citora.com/')
                                ->required()
                                ->maxLength(255)
                                ->unique(Business::class, 'slug', fn () => $this->getBusiness())
                                ->alphaDash(),
                            TextInput::make('email')
                                ->label('Correo electrónico')
                                ->email()
                                ->maxLength(255),
                            TextInput::make('phone')
                                ->label('Teléfono / WhatsApp')
                                ->tel()
                                ->maxLength(20),
                            Textarea::make('address')
                                ->label('Dirección')
                                ->rows(2)
                                ->maxLength(500)
                                ->columnSpanFull()
                                ->helperText('Aparecerá en tu página pública y en Google Maps'),
                            Textarea::make('description')
                                ->label('Descripción del negocio')
                                ->placeholder('Cuéntale a tus clientes sobre tu negocio, experiencia y lo que te hace especial...')
                                ->rows(3)
                                ->maxLength(1000)
                                ->columnSpanFull(),
                        ])
                        ->columns(2),

                    Section::make('Políticas de cancelación')
                        ->description('Tiempo mínimo de anticipación que tus clientes necesitan para cancelar o reprogramar. Pasado ese plazo tendrán que contactarte directamente.')
                        ->schema([
                            TextInput::make('cancellation_min_hours')
                                ->label('Horas mínimas para cancelar')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(168)
                                ->default(2)
                                ->suffix('horas')
                                ->helperText('0 = se puede cancelar en cualquier momento'),
                            TextInput::make('reschedule_min_hours')
                                ->label('Horas mínimas para reprogramar')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(168)
                                ->default(2)
                                ->suffix('horas')
                                ->helperText('0 = se puede reprogramar en cualquier momento'),
                        ])
                        ->columns(2),

                    Section::make('Imágenes')
                        ->description('Logo: 400x400px recomendado · Banner: 1200x400px recomendado')
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('logo')
                                ->label('Logo')
                                ->helperText('Cuadrado, 400x400px')
                                ->collection('logo')
                                ->disk('public')
                                ->image()
                                ->imageEditor()
                                ->maxSize(2048),
                            SpatieMediaLibraryFileUpload::make('banner')
                                ->label('Banner')
                                ->helperText('Horizontal, 1200x400px')
                                ->collection('banner')
                                ->disk('public')
                                ->image()
                                ->imageEditor()
                                ->maxSize(5120),
                        ])
                        ->columns(2),

                    Section::make('Horario de atención')
                        ->description('Al cambiar estos horarios, se actualizarán automáticamente los empleados que no tengan un horario personalizado.')
                        ->schema([
                            Repeater::make('schedules')
                                ->label('')
                                ->schema([
                                    Select::make('day_of_week')
                                        ->label('Día')
                                        ->options([
                                            1 => 'Lunes',
                                            2 => 'Martes',
                                            3 => 'Miércoles',
                                            4 => 'Jueves',
                                            5 => 'Viernes',
                                            6 => 'Sábado',
                                            0 => 'Domingo',
                                        ])
                                        ->required()
                                        ->disabled()
                                        ->dehydrated(),
                                    TimePicker::make('open_time')
                                        ->label('Apertura')
                                        ->required()
                                        ->seconds(false),
                                    TimePicker::make('close_time')
                                        ->label('Cierre')
                                        ->required()
                                        ->seconds(false),
                                    Toggle::make('is_active')
                                        ->label('Abierto')
                                        ->default(true),
                                ])
                                ->columns(4)
                                ->addable(false)
                                ->deletable(false)
                                ->reorderable(false),
                        ]),
                ])
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            HeaderAction::make('save')
                                ->label('Guardar cambios')
                                ->submit('save'),
                        ]),
                    ]),
            ])
            ->record($this->getBusiness())
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $business = $this->getBusiness();

        $oldSchedules = $business->schedules()
            ->get()
            ->keyBy('day_of_week')
            ->map(fn ($s) => [
                'open_time' => $s->open_time,
                'close_time' => $s->close_time,
                'is_active' => $s->is_active,
            ])
            ->all();

        $ownerDisplayName = isset($data['owner_display_name']) ? trim((string) $data['owner_display_name']) : '';
        auth()->user()->update([
            'display_name' => $ownerDisplayName !== '' ? $ownerDisplayName : null,
        ]);

        $business->update([
            'name' => $data['name'],
            'slogan' => $data['slogan'] ?? null,
            'description' => $data['description'] ?? null,
            'slug' => $data['slug'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'cancellation_min_hours' => (int) ($data['cancellation_min_hours'] ?? 2),
            'reschedule_min_hours' => (int) ($data['reschedule_min_hours'] ?? 2),
        ]);

        $this->form->record($business)->saveRelationships();

        $newSchedules = [];
        foreach ($data['schedules'] ?? [] as $scheduleData) {
            $business->schedules()->updateOrCreate(
                ['day_of_week' => $scheduleData['day_of_week']],
                [
                    'open_time' => $scheduleData['open_time'],
                    'close_time' => $scheduleData['close_time'],
                    'is_active' => $scheduleData['is_active'],
                ],
            );
            $newSchedules[$scheduleData['day_of_week']] = $scheduleData;
        }

        $syncedCount = $this->syncEmployeeSchedules($business, $oldSchedules, $newSchedules);

        $message = 'Negocio actualizado correctamente.';
        if ($syncedCount > 0) {
            $message .= " Se actualizó el horario de {$syncedCount} empleado(s) que usaban el horario anterior del negocio.";
        }

        Notification::make()
            ->success()
            ->title('Negocio actualizado')
            ->body($message)
            ->duration(8000)
            ->send();
    }

    /**
     * @param  array<int, array<string, mixed>>  $oldSchedules
     * @param  array<int, array<string, mixed>>  $newSchedules
     */
    private function syncEmployeeSchedules(Business $business, array $oldSchedules, array $newSchedules): int
    {
        $syncedCount = 0;

        foreach ($business->employees()->with('schedules')->get() as $employee) {
            $wasUpdated = false;

            foreach ($newSchedules as $dayOfWeek => $newSched) {
                $empSched = $employee->schedules->firstWhere('day_of_week', $dayOfWeek);
                $oldBizSched = $oldSchedules[$dayOfWeek] ?? null;

                if (! $empSched) {
                    $employee->schedules()->create([
                        'day_of_week' => $dayOfWeek,
                        'start_time' => $newSched['open_time'],
                        'end_time' => $newSched['close_time'],
                        'is_active' => $newSched['is_active'],
                    ]);
                    $wasUpdated = true;

                    continue;
                }

                $matchesOldBusiness = $oldBizSched
                    && $empSched->start_time === $oldBizSched['open_time']
                    && $empSched->end_time === $oldBizSched['close_time']
                    && $empSched->is_active === $oldBizSched['is_active'];

                if ($matchesOldBusiness) {
                    $empSched->update([
                        'start_time' => $newSched['open_time'],
                        'end_time' => $newSched['close_time'],
                        'is_active' => $newSched['is_active'],
                    ]);
                    $wasUpdated = true;
                }
            }

            if ($wasUpdated) {
                $syncedCount++;
            }
        }

        return $syncedCount;
    }

    private function getBusiness(): Business
    {
        return auth()->user()->business;
    }
}
