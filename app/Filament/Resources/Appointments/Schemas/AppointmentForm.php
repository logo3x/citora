<?php

namespace App\Filament\Resources\Appointments\Schemas;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Business;
use App\Models\Employee;
use App\Models\Service;
use App\Services\TimeSlotService;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;

class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        $businessId = auth()->user()->business_id;

        return $schema
            ->components([
                Section::make('Detalle de la cita')
                    ->schema([
                        Select::make('service_id')
                            ->label('Servicio')
                            ->options(fn () => Service::where('business_id', $businessId)->where('is_active', true)->pluck('name', 'id'))
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set): void {
                                $set('employee_id', null);
                                $set('time_slot', null);
                            })
                            ->searchable(),
                        Select::make('employee_id')
                            ->label('Empleado')
                            ->options(function (callable $get) use ($businessId): array {
                                $serviceId = $get('service_id');

                                $query = Employee::where('business_id', $businessId)->where('is_active', true);

                                if ($serviceId) {
                                    $query->whereHas('services', fn (Builder $q) => $q->where('services.id', $serviceId));
                                }

                                return $query->pluck('name', 'id')->all();
                            })
                            ->live()
                            ->afterStateUpdated(fn (callable $set) => $set('time_slot', null))
                            ->searchable(),
                        Select::make('customer_id')
                            ->label('Cliente')
                            ->relationship('customer', 'name')
                            ->searchable(['name', 'email'])
                            ->preload()
                            ->required(),
                        Select::make('status')
                            ->label('Estado')
                            ->options(collect(AppointmentStatus::cases())
                                ->mapWithKeys(fn (AppointmentStatus $case) => [$case->value => $case->label()])
                                ->all())
                            ->default(AppointmentStatus::Pending)
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Horario')
                    ->schema([
                        DatePicker::make('appointment_date')
                            ->label('Fecha')
                            ->required()
                            ->minDate(fn (string $operation): ?string => $operation === 'create' ? now()->toDateString() : null)
                            ->live()
                            ->afterStateUpdated(fn (callable $set) => $set('time_slot', null))
                            ->default(now()->toDateString()),
                        Select::make('time_slot')
                            ->label('Hora disponible')
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->options(function (callable $get, string $operation, ?Appointment $record) use ($businessId): array {
                                $date = $get('appointment_date');
                                $serviceId = $get('service_id');

                                if (! $date || ! $serviceId) {
                                    return [];
                                }

                                $business = Business::find($businessId);
                                $service = Service::find($serviceId);

                                if (! $business || ! $service) {
                                    return [];
                                }

                                $employeeId = $get('employee_id');
                                $employee = $employeeId ? Employee::find($employeeId) : null;

                                $slots = app(TimeSlotService::class)->getAvailableSlots(
                                    $business,
                                    $date,
                                    $service,
                                    $employee,
                                );

                                if ($operation === 'edit' && $record?->starts_at) {
                                    $currentTime = Carbon::parse($record->starts_at)->format('H:i');
                                    $currentLabel = Carbon::parse($record->starts_at)->format('g:i A').' (actual)';

                                    if (! isset($slots[$currentTime])) {
                                        $slots = [$currentTime => $currentLabel] + $slots;
                                    }
                                }

                                return $slots;
                            })
                            ->searchable()
                            ->helperText(fn (callable $get): string => $get('service_id')
                                ? 'Solo muestra horarios disponibles'
                                : 'Selecciona servicio y fecha primero'),
                    ])
                    ->columns(2),

                Section::make('Notas')
                    ->schema([
                        Textarea::make('notes')
                            ->label('Notas')
                            ->rows(2)
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function prepareData(array $data): array
    {
        $service = Service::find($data['service_id']);
        $date = $data['appointment_date'] ?? now()->toDateString();
        $time = $data['time_slot'] ?? '08:00';

        $startsAt = Carbon::parse("{$date} {$time}");
        $endsAt = $startsAt->copy()->addMinutes($service?->duration_minutes ?? 30);

        $data['starts_at'] = $startsAt;
        $data['ends_at'] = $endsAt;

        unset($data['appointment_date'], $data['time_slot']);

        return $data;
    }

    public static function validateNoOverlap(array $data, ?Appointment $ignoreRecord = null): void
    {
        if (empty($data['employee_id']) || empty($data['starts_at']) || empty($data['ends_at'])) {
            return;
        }

        $query = Appointment::where('employee_id', $data['employee_id'])
            ->where('status', '!=', AppointmentStatus::Cancelled->value)
            ->where('starts_at', '<', $data['ends_at'])
            ->where('ends_at', '>', $data['starts_at']);

        if ($ignoreRecord) {
            $query->where('id', '!=', $ignoreRecord->id);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'data.time_slot' => 'Este empleado ya tiene una cita en ese horario.',
            ]);
        }
    }
}
