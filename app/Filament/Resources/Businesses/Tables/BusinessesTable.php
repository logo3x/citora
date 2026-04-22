<?php

namespace App\Filament\Resources\Businesses\Tables;

use App\Enums\AppointmentStatus;
use App\Models\Business;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BusinessesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('logo')
                    ->label('')
                    ->collection('logo')
                    ->circular()
                    ->size(40),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Business $record) => $record->slug),
                TextColumn::make('owner')
                    ->label('Propietario')
                    ->getStateUsing(function (Business $record) {
                        $owner = $record->users()->first();

                        return $owner ? ($owner->display_name ?: $owner->name).' · '.$owner->email : '—';
                    })
                    ->wrap()
                    ->searchable(query: function ($query, string $search) {
                        $query->whereHas('users', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                                ->orWhere('display_name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                    }),
                TextColumn::make('phone')
                    ->label('Teléfono')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('services_count')
                    ->label('Servicios')
                    ->counts('services')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('employees_count')
                    ->label('Empleados')
                    ->counts('employees')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('appointments_count')
                    ->label('Citas/mes')
                    ->getStateUsing(fn (Business $record) => $record->getMonthlyAppointmentCount().' / '.$record->monthly_appointment_limit)
                    ->color(fn (Business $record) => $record->hasReachedMonthlyLimit() ? 'danger' : 'success')
                    ->alignCenter(),
                TextColumn::make('revenue_month')
                    ->label('Ingresos del mes')
                    ->getStateUsing(function (Business $record): string {
                        $start = now()->startOfMonth();
                        $end = now()->endOfMonth();

                        $total = $record->appointments()
                            ->where('status', AppointmentStatus::Completed)
                            ->whereBetween('starts_at', [$start, $end])
                            ->join('services', 'appointments.service_id', '=', 'services.id')
                            ->sum('services.price');

                        return '$'.number_format((int) $total, 0, ',', '.');
                    })
                    ->alignEnd()
                    ->toggleable(),
                TextColumn::make('plan_status')
                    ->label('Plan')
                    ->getStateUsing(function (Business $record): string {
                        if ($record->isUnlockedForPeriod()) {
                            return 'Ilimitado';
                        }
                        if ($record->hasReachedMonthlyLimit()) {
                            return 'Bloqueado';
                        }

                        return 'Gratuito';
                    })
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'Ilimitado' => 'success',
                        'Bloqueado' => 'danger',
                        default => 'gray',
                    }),
                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Registro')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Estado')
                    ->trueLabel('Activos')
                    ->falseLabel('Inactivos'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
