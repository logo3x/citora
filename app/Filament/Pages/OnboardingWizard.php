<?php

namespace App\Filament\Pages;

use App\Jobs\SendWhatsAppNotification;
use App\Mail\BusinessCreatedAdminMail;
use App\Models\Business;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Page;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\MediaLibrary\HasMedia;

/**
 * @property Schema $form
 */
class OnboardingWizard extends Page
{
    protected string $view = 'filament.pages.onboarding-wizard';

    protected static ?string $title = 'Configura tu negocio';

    protected static ?string $slug = 'onboarding';

    protected static bool $shouldRegisterNavigation = false;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $user = auth()->user();

        if ($user->hasRole('super_admin') || $user->business_id !== null) {
            $this->redirect(filament()->getUrl());

            return;
        }

        $this->form->fill([
            'schedules' => $this->getDefaultSchedules(),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Información')
                        ->icon('heroicon-o-building-storefront')
                        ->description('Datos de tu negocio, horarios e imágenes')
                        ->schema([
                            TextInput::make('name')
                                ->label('Nombre del negocio')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                            TextInput::make('slug')
                                ->label('URL pública')
                                ->prefix('citora.com/')
                                ->required()
                                ->maxLength(255)
                                ->unique(Business::class, 'slug')
                                ->alphaDash(),
                            TextInput::make('email')
                                ->label('Correo electrónico')
                                ->email()
                                ->maxLength(255),
                            TextInput::make('phone')
                                ->label('Teléfono / WhatsApp')
                                ->tel()
                                ->maxLength(20),
                            TextInput::make('slogan')
                                ->label('Eslogan')
                                ->placeholder('Ej: Los mejores cortes de la ciudad')
                                ->maxLength(255),
                            TextInput::make('address')
                                ->label('Dirección')
                                ->placeholder('Ej: Calle 31 #51-13, Bucaramanga')
                                ->maxLength(500),
                            Textarea::make('description')
                                ->label('Descripción del negocio')
                                ->placeholder('Cuéntale a tus clientes sobre tu negocio...')
                                ->rows(3)
                                ->maxLength(1000)
                                ->columnSpanFull(),
                            FileUpload::make('logo')
                                ->label('Logo (opcional)')
                                ->helperText('Cuadrado, 400x400px recomendado')
                                ->image()
                                ->disk('public')
                                ->directory('tmp-uploads')
                                ->maxSize(2048),
                            FileUpload::make('banner')
                                ->label('Banner (opcional)')
                                ->helperText('Horizontal, 1200x400px recomendado')
                                ->image()
                                ->disk('public')
                                ->directory('tmp-uploads')
                                ->maxSize(5120),
                            Repeater::make('schedules')
                                ->label('Horario de atención')
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
                                ->defaultItems(0)
                                ->addable(false)
                                ->deletable(false)
                                ->reorderable(false)
                                ->columnSpanFull(),
                        ])
                        ->columns(2),

                    Step::make('Servicios')
                        ->icon('heroicon-o-clipboard-document-list')
                        ->description('Agrega los servicios que ofreces (opcional)')
                        ->schema([
                            Repeater::make('services')
                                ->label('')
                                ->schema([
                                    TextInput::make('name')
                                        ->label('Nombre del servicio')
                                        ->required()
                                        ->maxLength(255),
                                    Textarea::make('description')
                                        ->label('Descripción')
                                        ->rows(2)
                                        ->maxLength(1000),
                                    FileUpload::make('image')
                                        ->label('Imagen (opcional)')
                                        ->image()
                                        ->disk('local')
                                        ->directory('livewire-tmp')
                                        ->maxSize(2048),
                                    Select::make('duration_minutes')
                                        ->label('Duración')
                                        ->options([
                                            15 => '15 minutos',
                                            30 => '30 minutos',
                                            45 => '45 minutos',
                                            60 => '1 hora',
                                            90 => '1 hora 30 min',
                                            120 => '2 horas',
                                        ])
                                        ->required()
                                        ->default(30),
                                    TextInput::make('price')
                                        ->label('Precio')
                                        ->numeric()
                                        ->prefix('$')
                                        ->required()
                                        ->default(0),
                                ])
                                ->columns(2)
                                ->defaultItems(0)
                                ->addActionLabel('Agregar servicio')
                                ->columnSpanFull(),
                        ]),

                    Step::make('Empleados')
                        ->icon('heroicon-o-user-group')
                        ->description('Agrega tu equipo de trabajo (opcional)')
                        ->schema([
                            Repeater::make('employees')
                                ->label('')
                                ->schema([
                                    TextInput::make('name')
                                        ->label('Nombre')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('position')
                                        ->label('Cargo')
                                        ->maxLength(255),
                                    TextInput::make('phone')
                                        ->label('Teléfono / WhatsApp')
                                        ->tel()
                                        ->maxLength(20),
                                    FileUpload::make('image')
                                        ->label('Foto (opcional)')
                                        ->image()
                                        ->disk('local')
                                        ->directory('livewire-tmp')
                                        ->maxSize(2048),
                                    Select::make('service_names')
                                        ->label('Servicios que realiza')
                                        ->multiple()
                                        ->options(function (callable $get): array {
                                            $services = $get('../../services') ?? [];
                                            $options = [];
                                            foreach ($services as $service) {
                                                if (! empty($service['name'])) {
                                                    $options[$service['name']] = $service['name'];
                                                }
                                            }

                                            return $options;
                                        })
                                        ->dehydrated()
                                        ->columnSpanFull(),
                                ])
                                ->columns(3)
                                ->defaultItems(0)
                                ->addActionLabel('Agregar empleado')
                                ->columnSpanFull(),
                        ]),
                ])
                    ->submitAction(view('filament.pages.onboarding-submit-button')),
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        $data = $this->form->getState();
        $user = auth()->user();

        DB::transaction(function () use ($data, $user): void {
            $business = Business::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'slogan' => $data['slogan'] ?? null,
                'address' => $data['address'] ?? null,
                'description' => $data['description'] ?? null,
            ]);

            $this->attachMedia($business, $data['logo'] ?? null, 'logo');
            $this->attachMedia($business, $data['banner'] ?? null, 'banner');

            $schedules = collect($data['schedules'] ?? [])->map(fn (array $schedule) => [
                'day_of_week' => $schedule['day_of_week'],
                'open_time' => $schedule['open_time'],
                'close_time' => $schedule['close_time'],
                'is_active' => $schedule['is_active'],
            ])->all();
            $business->schedules()->createMany($schedules);

            $serviceNameToId = [];
            foreach ($data['services'] ?? [] as $serviceData) {
                $service = $business->services()->create([
                    'name' => $serviceData['name'],
                    'description' => $serviceData['description'] ?? null,
                    'duration_minutes' => $serviceData['duration_minutes'],
                    'price' => $serviceData['price'],
                ]);
                $this->attachMedia($service, $serviceData['image'] ?? null, 'image');
                $serviceNameToId[$serviceData['name']] = $service->id;
            }

            foreach ($data['employees'] ?? [] as $employeeData) {
                $employee = $business->employees()->create([
                    'name' => $employeeData['name'],
                    'position' => $employeeData['position'] ?? null,
                    'phone' => $employeeData['phone'] ?? null,
                ]);
                $this->attachMedia($employee, $employeeData['image'] ?? null, 'photo');

                $serviceIds = collect($employeeData['service_names'] ?? [])
                    ->map(fn (string $name) => $serviceNameToId[$name] ?? null)
                    ->filter()
                    ->values()
                    ->all();

                if ($serviceIds) {
                    $employee->services()->attach($serviceIds);
                }
            }

            $user->business_id = $business->id;
            $user->save();

            if ($user->hasRole('customer')) {
                $user->removeRole('customer');
            }

            $user->assignRole('business_owner');

            // Notificaciones
            if ($business->phone) {
                SendWhatsAppNotification::dispatch('business.created', null, [
                    'phone' => $business->phone,
                    'business_name' => $business->name,
                    'slug' => $business->slug,
                ]);
            }

            foreach ($business->employees as $emp) {
                if ($emp->phone) {
                    SendWhatsAppNotification::dispatch('employee.registered', null, [
                        'phone' => $emp->phone,
                        'name' => $emp->name,
                        'business_name' => $business->name,
                    ]);
                }
            }

            $this->sendAdminNotification($business);
        });

        $this->dispatch('business-created');
    }

    private function sendAdminNotification(Business $business): void
    {
        $recipients = collect(explode(',', (string) config('mail.admin_email')))
            ->map(fn (string $email) => trim($email))
            ->filter()
            ->values()
            ->all();

        if (empty($recipients)) {
            return;
        }

        try {
            Mail::to($recipients)->send(new BusinessCreatedAdminMail($business));
        } catch (\Throwable $e) {
            Log::error('BusinessCreatedAdminMail failed: '.$e->getMessage(), [
                'business_id' => $business->id,
            ]);
        }
    }

    private function attachMedia(HasMedia $model, mixed $filePath, string $collection): void
    {
        if (empty($filePath)) {
            return;
        }

        if (is_array($filePath)) {
            $filePath = reset($filePath);
        }

        if ($filePath instanceof TemporaryUploadedFile) {
            $model->addMedia($filePath->getRealPath())
                ->usingFileName($filePath->getClientOriginalName())
                ->toMediaCollection($collection);

            return;
        }

        if (! is_string($filePath) || empty($filePath)) {
            return;
        }

        $paths = [
            Storage::disk('public')->path($filePath),
            Storage::disk('local')->path($filePath),
            storage_path('app/livewire-tmp/'.$filePath),
            storage_path('app/'.$filePath),
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                $model->addMedia($path)->toMediaCollection($collection);

                return;
            }
        }

        Log::warning('attachMedia: file not found', ['filePath' => $filePath, 'collection' => $collection]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function getDefaultSchedules(): array
    {
        $days = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            0 => 'Domingo',
        ];

        $schedules = [];
        foreach ($days as $dayNumber => $dayName) {
            $isWeekday = $dayNumber >= 1 && $dayNumber <= 5;
            $schedules[] = [
                'day_of_week' => $dayNumber,
                'open_time' => '08:00',
                'close_time' => '18:00',
                'is_active' => $isWeekday,
            ];
        }

        return $schedules;
    }
}
